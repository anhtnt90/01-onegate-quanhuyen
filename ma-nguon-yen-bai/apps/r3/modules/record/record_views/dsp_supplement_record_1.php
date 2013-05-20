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
$this->template->title = 'Hồ sơ phải bổ sung, đã thông báo, chưa nhận giấy tờ bổ sung';
$this->template->display('dsp_header_pop_win.php');

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
    echo $this->hidden('hdn_handover_method','do_handover_record');
    echo $this->hidden('hdn_announce_method','do_receive_supplement_record');

    echo $this->hidden('record_type_code', $v_record_type_code);

    echo $this->hidden('hdn_supplement_status', 1);

    ?>
    <div class="page-title">Hồ sơ phải bổ sung, đã thông báo, chưa nhận giấy tờ bổ sung</div>
    <div class="page-notice">
        <div id="notice">
            <div class="notice-title">Thống kê hồ sơ</div>
            <div class="notice-container" id="notice-container"><ul></ul></div>
        </div>
    </div>
    <!-- filter -->
    <?php $this->dsp_div_filter($v_record_type_code, $arr_all_record_type);?>

    <div class="clear"></div>
    <div id="procedure">
        <?php
        if ($this->load_abs_xml($this->get_xml_config($v_record_type_code, 'list')))
        {
            echo $this->render_form_display_all_record($arr_all_record, 1);
        }
        ?>
    </div>
    <div><?php echo $this->paging2($arr_all_record);?></div>

    <!-- Context menu -->
    <ul id="myMenu" class="contextMenu">
        <li class="receive_supplement">
            <a href="#receive_supplement">Bổ sung hồ sơ</a>
        </li>
        <li class="print">
            <a href="#print">In phiếu biên nhận hồ sơ bổ sung</a>
        </li>
        <li class="statistics">
            <a href="#statistics">Xem tiến độ</a>
        </li>
    </ul>
</form>
<script>
    get_supplement_notice();
    setInterval(get_supplement_notice, <?php echo _CONST_GET_NEW_RECORD_NOTICE_INTERVAL;?>);

    $(document).ready( function() {
        //Khong thongbao thoi han
    	$('.days-remain').html('');
    	
        //Show context on each row
        $(".adminlist tr[role='presentation']").contextMenu({
            menu: 'myMenu'
        }, function(action, el, pos) {
            v_record_id = $(el).attr('data-item_id');
            switch (action){
                case 'receive_supplement':
                    btn_receive_supplement_onclick(v_record_id);
                    break;

                case 'print':
                	print_record_ho_for_citizen(v_record_id);
                    break;

                case 'statistics':
                    dsp_single_record_statistics(v_record_id);
                    break;
            }
        });

        //Quick action
        $('.adminlist tr[role="presentation"] td[role="action"] .quick_action').each(function(index) {
            v_item_id =   $(this).attr('data-item_id');

            html = '';

            /*
            //In phieu biên nhận hồ sơ bổ sung
            html += '<a href="javascript:void(0)" onclick="print_record_ho_for_citizen(\'' + v_item_id + '\')" class="quick_action" >';
            html += '<img src="' + SITE_ROOT + 'public/images/print_24x24.png" title="In phiếu biên nhận hồ sơ bổ sung" /></a>';
            */

            //Thong tin tien do
            html += '<a href="javascript:void(0)" onclick="dsp_single_record_statistics(\'' + v_item_id + '\')" class="quick_action" >';
            html += '<img src="' + SITE_ROOT + 'public/images/statistics-16x16.png" title="Xem tiến độ" /></a>';

            $(this).html(html);
        });
    });

    function btn_announce_onclick(record_id)
    {
        var f=document.frmMain;
        if (typeof(record_id) == 'undefined')
        {
            record_id = get_all_checked_checkbox(f.chk, ',');
        }

        $("#hdn_item_id_list").val(record_id);
        var m = $("#controller").val() + 'do_receive_supplement_record';
        $("#frmMain").attr('action', m);
        f.submit();
    }

    //Overload row_onclick -> Bo sung
    function row_onclick(id)
    {
        var url = '<?php echo $this->get_controller_url();?>dsp_single_record_supplement/' + id
                    + '/&pop_win=1&hdn_item_id=' + id;
         window.parent.showPopWin(url, 1000, 600, refresh_me);
    }
    function refresh_me()
    {
        $("#frmMain").submit();
    }

    function print_record_ho_for_citizen(p_record_id)
    {
        var url = '<?php echo $this->get_controller_url();?>dsp_print_supplement_ho_for_citizen/' + p_record_id;

        showPopWin(url, 700, 450, null, true);
    }

</script>
<?php $this->template->display('dsp_footer_pop_win.php');