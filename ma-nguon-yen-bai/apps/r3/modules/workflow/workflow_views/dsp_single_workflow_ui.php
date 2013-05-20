<?php if (!defined('SERVER_ROOT')) {exit('No direct script access allowed');} ?>
<?php
//display header
$this->template->title = 'Quản trị quy trình xử lý hồ sơ';
$this->template->display('dsp_header.php');
?>
<form name="frmMain" method="post" id="frmMain" action="">
    <?php
    echo $this->hidden('controller', $this->get_controller_url() . 'ui');
    echo $this->hidden('hdn_tbl_id', '');
    echo $this->hidden('hdn_group_code', '');
    echo $this->hidden('hdn_next_task_code', '');

    echo $this->hidden('hdn_record_type_code', $v_record_type_code);
    ?>
    <!-- filter -->
    <div id="div_filter">
        (1)&nbsp;<label>Mã loại hồ sơ</label>
        <input type="text" name="txt_record_type_code" id="txt_record_type_code"
               value="<?php echo $v_record_type_code; ?>"
               class="inputbox upper_text" size="10" maxlength="10"
               onkeypress="txt_record_type_code_onkeypress(event);"
               autofocus="autofocus"
               accesskey="1"
               />&nbsp;
        <select name="sel_record_type" id="sel_record_type" style="width:75%; color:#000000;"
                onchange="sel_record_type_onchange(this)">
            <option value="">-- Chọn loại hồ sơ --</option>
            <?php echo $this->generate_select_option($arr_all_record_type, $v_record_type_code); ?>
        </select>
    </div>
    <input type="text" name="noname" style="visibility: hidden"/>
    <?php if ($v_record_type_code != ''): ?>
        <?php $xml_flow_file_path = $this->get_xml_config($v_record_type_code, 'workflow');?>
        <input type="hidden" name="hdn_sorting_info" id="hdn_sorting_info" value="" />
        <div>
            <?php
            if (is_file($xml_flow_file_path))
            {
                $v_xml_flow = xml_add_declaration(file_get_contents($xml_flow_file_path));
            }
            else
            {
                $v_xml_flow  = str_replace('#CODE#', $v_record_type_code, $v_default_xml_flow);
            }
            session::set('v_current_xml_flow', $v_xml_flow);
            $dom = simplexml_load_string($v_xml_flow);
            $r = $dom->xpath("/process");
            $proc = $r[0];
            $steps = $proc->step;
            ?>
            <h6>Quy trình: <?php echo $proc->attributes()->code; ?> - <?php echo $proc->attributes()->name; ?></h6>
            <h6>Tổng số ngày thực hiện: <label id="lbl_totaltime"><?php echo $proc->attributes()->totaltime; ?></label> ngày</h6>
            <h6>Phí, lệ phí: <label id="lbl_fee"><?php echo $proc->attributes()->fee; ?></label> (đ)</h6>

            <?php echo $this->hidden('hdn_record_type_code', strval($proc->attributes()->code));?>
            <?php echo $this->hidden('hdn_record_type_name', strval($proc->attributes()->name));?>
            <?php echo $this->hidden('hdn_total_time', strval($proc->attributes()->totaltime));?>
            <?php echo $this->hidden('hdn_fee', strval($proc->attributes()->fee));?>

            <input type="button" value="Sửa" name="btn_edit_process_attributes" onclick="btn_edit_process_attributes_onclick()"/>
        </div>
        <div id="contentWrap">
            <div id="contentLeft">
                Kéo và thả để sắp xếp thứ tự bước
                <ul class="ui-sortable" id="all_step">
                    <?php
                        $v_position = 1;
                        foreach ($steps as $step):
                            $v_exec_group = $step->attributes()->group;
                            $v_step_name = $step->attributes()->name;
                            $v_step_id= 'step_'. $v_position;
                            ?>
                            <li id="<?php echo $v_step_id;?>" style="opacity: 1; z-index: 0;" class="ui-state-disabled">
                                <div class="step-header">
                                    <div class="step-name quick_action">
                                    <h6>
                                        <a href="javascript:void(0)" title="Công việc trong bước" class="quick_action" onclick="dsp_all_task_in_step(<?php echo $v_position;?>)">
                                            <img src="<?php echo SITE_ROOT;?>public/images/config.png" width="24px"/>
                                        </a>
                                        <a href="javascript:void(0)" title="Hiệu chỉnh thông tin bước" class="quick_action" onclick="btn_dsp_single_step_onclick(<?php echo $v_position;?>)">
                                            <img src="<?php echo SITE_ROOT;?>public/images/edit-32x32.png" width="24px"/>
                                        </a>
                                        <a href="javascript:void(0)" title="Xoá bước" class="quick_action" onclick="btn_delete_step_onclick(<?php echo $v_position;?>)">
                                            <img src="<?php echo SITE_ROOT;?>public/images/delete-24x24.png" width="24px"/>
                                        </a>
                                        <label id="<?php echo $v_step_id;?>_name"><?php echo $v_step_name;?></label>
                                        </h6>
                                    </div>
                                </div>
                                <div class="step-info"
                                     data-time="<?php echo $step->attributes()->time;?>"
                                     data-group="<?php echo $step->attributes()->group;?>">
                                    - Tổng số ngày quy định: <label id="<?php echo $v_step_id;?>_time"><?php echo $step->attributes()->time;?></label>
                                    <br/>- Bộ phận thực hiện: <label id="<?php echo $v_step_id;?>_group"><?php echo $step->attributes()->group;?></label>
                                <div>
                            </li>
                            <?php $v_position++;?>
                        <?php endforeach;?>
                </ul>
            </div>

            <div id="contentRight">
                <input type="button" name="btn_save" onclick="btn_save_onclick()" value="Ghi lại" />
                <input type="button" name="btn_add_step" onclick="btn_add_step_onclick()" value="Thêm bước" />
                <div id="response"></div>

            </div>
        </div>
    <?php endif; ?>
</form>
<script type="text/javascript">
    $(document).ready(function(){
        $(function() {
            $("#contentLeft ul").sortable({ opacity: 0.6, cursor: 'move', update: function() {
            var order = $(this).sortable("serialize")+ '&action=updateRecordsListings';
            $("#hdn_sorting_info").val(order);
            }
            });
        });
    });

    function btn_save_onclick()
    {
        var v_sorting_info = $("#hdn_sorting_info").val();
        v_sorting_info += '&pop_win=1';
        v_sorting_info += '&record_type_code=' + $("#hdn_record_type_code").val();
        v_sorting_info += '&record_type_name=' + encodeURIComponent($("#hdn_record_type_name").val());
        v_sorting_info += '&total_time=' + $("#lbl_totaltime").html();
        v_sorting_info += '&fee=' + $("#lbl_fee").html();

        $.post("<?php echo $this->get_controller_url() . 'do_update_step_order_by_ui';?>", v_sorting_info , function(theResponse){
            $("#response").html('<pre>' + theResponse + '</pre>');
        });
    }


    function btn_add_step_onclick()
    {
        var v_new_step_id = $("#all_step li").length + 1;
        var v_new_step_name = v_new_step_id + '. Đây là step ' + v_new_step_id;
        var v_new_step_html = '<li id="step_' + v_new_step_id + '" style="opacity: 1; z-index: 0;" class="">' + v_new_step_name + '</li>';

        var v_new_step_html = '<li id="step_' + v_new_step_id + '" style="opacity: 1; z-index: 0;" class="">';
        v_new_step_html += '<div class="step-header">';
        v_new_step_html += '<h6>' + v_new_step_name + '</h6>';
        v_new_step_html += '</div>';
        v_new_step_html += '<div id="task_' + v_new_step_id + '" class="step-info">';
        v_new_step_html += '- Tổng số ngày quy định: xxx';
        v_new_step_html += '- <br/>- Bộ phận thực hiện: xxx';
        v_new_step_html += '</li>';

        $("#all_step").append(v_new_step_html);
    }

    function btn_edit_process_attributes_onclick()
    {
        var url = '<?php echo $this->get_controller_url();?>dsp_single_process/';
        url += '&pop_win=1';
        url += '&record_type_code=' + $("#hdn_record_type_code").val();
        url += '&record_type_name=' + encodeURIComponent($("#hdn_record_type_name").val());
        url += '&total_time=' + $("#lbl_totaltime").html();
        url += '&fee=' + $("#lbl_fee").html();
        showPopWin(url, 450, 350, do_assign);
    }

    function do_assign(returnVal)
    {
        myObject = eval('(' + returnVal + ')');
        $("#lbl_totaltime").html(myObject[0]);
        $("#lbl_fee").html(myObject[1]);
    }

    function btn_delete_step_onclick(id)
    {
        if (confirm('Bạn chắc chắn xoá bước này?'))
        {
            alert('Send Ajax request to remove step from process');
        }
    }

    function btn_dsp_single_step_onclick(id)
    {
    	var url = '<?php echo $this->get_controller_url();?>dsp_single_step/';
        url += '&pop_win=1';
        url += '&step_id='+id;
        url += '&record_type_code=' + $("#hdn_record_type_code").val();
        url += '&record_type_name=' + encodeURIComponent($("#hdn_record_type_name").val());
        showPopWin(url, 600, 400, do_update_step);
    }

    function do_update_step(returnVal)
    {
    	myObject = eval('(' + returnVal + ')');
        v_step_id = myObject[0];

        $("#step_" + v_step_id + "_name").html(myObject[1]);
        $("#step_" + v_step_id + "_group").html(myObject[2]);
        $("#step_" + v_step_id + "_time").html(myObject[3]);
    }

    function dsp_all_task_in_step(step_id)
    {
    	var url = '<?php echo $this->get_controller_url();?>dsp_all_task_in_step/' + step_id + '/';

    	url += '&pop_win=1';
    	url += '&record_type_code=' + $("#hdn_record_type_code").val();
        url += '&record_type_name=' + encodeURIComponent($("#hdn_record_type_name").val());

        showPopWin(url, 650, 600, do_update_task_in_step);
    }
    function do_update_task_in_step()
    {
        alert('reload');
    }


</script>
<?php
$this->template->display('dsp_footer.php');