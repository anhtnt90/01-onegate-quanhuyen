<?php 
/**
  	* o	Chương trình: Xây dựng phần mềm một cửa điện tử nguồn mở cho các quận huyện.
	* o	Thực hiện: Ban Quản lý các dự án công nghiệp công nghệ thông tin-Bộ Thông tin và Truyền thông.
	* o	Thuộc dự án: Hỗ trợ địa phương xây dựng, hoàn thiện một số sản phẩm phần mềm nguồn mở theo Quyết định 112/QĐ-TTg ngày 20/01/2012 của Thủ tướng Chính phủ.
	* o	Tác giả: Công ty Cổ phần Đầu tư và Phát triển Công nghệ Tâm Việt
	* o	Email: LTBinh@gmail.com
	* o	Điện thoại: 0936.114411
	* 
*/

if (!defined('SERVER_ROOT')) exit('No direct script access allowed');

//View data
$arr_all_record_type    = $VIEW_DATA['arr_all_record_type'];
$v_record_type_code     = $VIEW_DATA['record_type_code'];
$arr_all_record         = $VIEW_DATA['arr_all_record'];

//header
$this->template->title = 'Chuyển thông báo thuế về bộ phận một cửa';
$this->template->display('dsp_header.php');

?>
<form name="frmMain" id="frmMain" action="" method="POST">
    <?php
    echo $this->hidden('controller',$this->get_controller_url());
    echo $this->hidden('hdn_item_id','0');
    echo $this->hidden('hdn_item_id_list','');

    echo $this->hidden('hdn_dsp_single_method','dsp_single_record');
    echo $this->hidden('hdn_dsp_all_method', 'dsp_all_record');
    echo $this->hidden('hdn_update_method','update_record');
    echo $this->hidden('hdn_delete_method','delete_record');
    echo $this->hidden('hdn_submit_tax_method','do_submit_tax');

    echo $this->hidden('record_type_code', $v_record_type_code);

    ?>
    <?php echo $this->dsp_div_notice($VIEW_DATA['active_role_text'] );?>
    <!-- filter -->
    <?php $this->dsp_div_filter($v_record_type_code, $arr_all_record_type);?>

    <div id="solid-button">
        <input type="button" class="solid transfer" value="Chuyển"
               onclick="btn_submit_tax_onclick();" />
        <input type="button" class="solid print" value="In Phiếu"
               onclick="print_record_ho_for_bu();" />
    </div>
    <div class="clear"></div>

    <div id="procedure">
        <?php
        if ($this->load_abs_xml($this->get_xml_config($v_record_type_code, 'list')))
        {
            echo $this->render_form_display_all_record($arr_all_record, FALSE);
        }
        ?>
    </div>
	<div><?php echo $this->paging2($arr_all_record);?></div>
    <div class="button-area">
        <input type="button" name="btn_submit_tax" class="button transfer" value="Chuyển" onclick="btn_submit_tax_onclick();"/>
        <input type="button" name="btn_print" class="button print" value="In Phiếu" onclick="print_record_ho_for_bu();"/>
    </div>

    <!-- Context menu -->
    <ul id="myMenu" class="contextMenu">
        <li class="submit_tax">
            <a href="#submit_tax">Chuyển thông báo thuế về bộ phận "Một-cửa"</a>
        </li>
        <li class="statistics">
            <a href="#statistics">Xem tiến độ</a>
        </li>
    </ul>
</form>
<script>

    $(function() {
         //Show context on each row
        $(".adminlist tr[role='presentation']").contextMenu({
            menu: 'myMenu'
        }, function(action, el, pos) {
            v_record_id = $(el).attr('data-item_id');
            switch (action){
                case 'submit_tax':
                    btn_submit_tax_onclick(v_record_id);
                    break;

                case 'statistics':
                    dsp_single_record_statistics(v_record_id);
                    break;
            }
        });
    });

    function btn_submit_tax_onclick(record_id)
    {
        var f = document.frmMain;

        //Danh sach ID Ho so da chon
        if (typeof(record_id) == 'undefined')
        {
            //Lay danh sach HS da chon
            v_selected_record_id_list = get_all_checked_checkbox(f.chk, ',');
        }
        else
        {
            v_selected_record_id_list = record_id;
        }
        $("#hdn_item_id_list").val(v_selected_record_id_list);

        if ( $("#hdn_item_id_list").val() == '')
        {
            alert('Chưa có hồ sơ nào được chọn!');
            return;
        }

        m = $("#controller").val() + $("#hdn_submit_tax_method").val();
        $("#frmMain").attr("action", m);

        f.submit();

    }
</script>
<?php $this->template->display('dsp_footer.php');