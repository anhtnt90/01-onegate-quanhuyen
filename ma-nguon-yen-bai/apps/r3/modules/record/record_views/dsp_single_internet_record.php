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

 
if (!defined('SERVER_ROOT')) {exit('No direct script access allowed');
}

//View data
$arr_all_record_type    = $VIEW_DATA['arr_all_record_type'];
$v_record_type_code     = $VIEW_DATA['record_type_code'];
$arr_single_record      = $VIEW_DATA['arr_single_record'];
$MY_TASK                = $VIEW_DATA['MY_TASK'];

if (isset($arr_single_record['PK_RECORD']))
{
    $v_record_id            = $arr_single_record['PK_RECORD'];
    $v_record_no            = $arr_single_record['C_RECORD_NO'];
    $v_receive_date         = $arr_single_record['C_RECEIVE_DATE'];
    $v_return_phone_number  = $arr_single_record['C_RETURN_PHONE_NUMBER'];
    $v_return_email         = $arr_single_record['C_RETURN_EMAIL'];

    $v_xml_data             = $arr_single_record['C_XML_DATA'];

    //Convert date
    $v_receive_date         = jwDate::yyyymmdd_to_ddmmyyyy($v_receive_date, TRUE);
}
else
{
   exit;

}

//display header
$this->template->title = 'Xác nhận hồ sơ nộp qua Internet';
$this->template->display('dsp_header.php');

?>
<form name="frmMain" id="frmMain" action="" method="POST"
	enctype="multipart/form-data">
	<?php
	echo $this->hidden('controller',$this->get_controller_url());
	echo $this->hidden('hdn_item_id',$v_record_id);
	echo $this->hidden('hdn_item_id_list',$v_record_id);

	echo $this->hidden('hdn_dsp_single_method','dsp_single_internet_record');
	echo $this->hidden('hdn_dsp_all_method', 'ho_so/xac_nhan_ho_so_nop_qua_internet ');
	echo $this->hidden('hdn_update_method','update_internet_record');
	echo $this->hidden('hdn_delete_method','delete_internet_record');

	echo $this->hidden('XmlData',$v_xml_data);

	echo $this->hidden('MY_TASK', $MY_TASK);

	echo $this->hidden('hdn_deleted_file_id_list', '');
	?>

	<div class="page-title">Xác nhận hồ sơ nộp qua internet</div>

	<div class="panel_color">Thông tin chung</div>
	<table style="width: 100%;" class="none-border-table">
		<tr>
			<td width="20%"><label>Mã loại hồ sơ</label> (Alt+1)</td>
			<td colspan="3"><input type="text" name="txt_record_type_code"
				id="txt_record_type_code" value="<?php echo $v_record_type_code; ?>"
				class="inputbox upper_text" size="10" maxlength="10"
				onkeypress="txt_record_type_code_onkeypress(event);"
				autofocus="autofocus" accesskey="1" data-allownull="no"
				data-validate="text" data-name="Loại hồ sơ" data-xml="no"
				data-doc="no" />&nbsp; <select name="sel_record_type"
				id="sel_record_type" style="width: 77%; color: #000000;"
				onchange="sel_record_type_onchange(this)" data-allownull="no"
				data-validate="text" data-name="Loại hồ sơ" data-xml="no"
				data-doc="no">
					<option value="">-- Chọn loại hồ sơ --</option>
					<?php echo $this->generate_select_option($arr_all_record_type, $v_record_type_code); ?>
			</select>
			</td>
		</tr>
		<tr>
			<td>Mã hồ sơ: <span class="required">(*)</span>
			</td>
			<td><input readonly="readonly" name="txt_record_no"
				id="txt_record_no" maxlength="50" style="width: 200px" type="text"
				value="<?php echo $v_record_no;?>" data-allownull="no"
				data-validate="text" data-name="M&atilde; h&#7891; s&#417;"
				data-xml="no" data-doc="no" />
			</td>
			<td>Số điện thoại nhận SMS:</td>
			<td><input name="txt_return_phone_number"
				id="txt_return_phone_number" maxlength="20" style="width: 200px"
				type="text" value="<?php echo $v_return_phone_number;?>"
				data-allownull="yes" data-validate="text"
				data-name="S&#7889; &#273;i&#7879;n tho&#7841;i nh&#7853;n SMS"
				data-xml="no" data-doc="no" />
			</td>
		</tr>
		<tr>
			<td>Ngày giờ nộp:</span>
			</td>
			<td><input readonly="readonly" id="txt_receive_date"
				name="txt_receive_date" style="width: 200px" type="text"
				value="<?php echo $v_receive_date;?>" data-allownull="no"
				data-validate="text" data-name="Ngày giờ tiếp nhận" data-xml="no"
				data-doc="no" />
			</td>
			<td>Email:</td>
			<td><input name="txt_return_email" id="txt_return_email"
				maxlength="255" style="width: 200px" type="text"
				value="<?php echo $v_return_email;?>" data-allownull="yes"
				data-validate="email" data-name="Địa chỉ email" data-xml="no"
				data-doc="no" />
			</td>
		</tr>
		<tr>
			<td>Thêm file đính kèm:</td>
			<td colspan="2">
                <?php if (isset($VIEW_DATA['arr_all_record_file']))
				{
				    $arr_all_record_file = $VIEW_DATA['arr_all_record_file'];
				    for ($i=0; $i<sizeof($arr_all_record_file); $i++)
				    {
				        $v_file_id      = $arr_all_record_file[$i]['PK_RECORD_FILE'];
				        $v_file_name    = $arr_all_record_file[$i]['C_FILE_NAME'];
				        $v_file_path    = SITE_ROOT . 'uploads/r3/internet/' . $v_file_name;

				        echo '<span id="file_' . $v_file_id . '">';
				        echo '<img src="' . SITE_ROOT . 'public/images/trash.png" style="cursor:pointer" onclick="delete_file(' . $v_file_id . ')">&nbsp;';
				        echo '<a href="' . $v_file_path .'" target="_blank">' .$v_file_name . '</a><br/>';
				        echo '</span>';
				    }
				}?>
            </td>

			<td style="text-align: right">
                <?php dsp_button();?>
			</td>
		</tr>
	</table>

	<div id="record_detail">
		<div id="xml_part">
			<?php echo $this->transform($this->get_xml_config($v_record_type_code, 'form_struct')); ?>
		</div>
	</div>

	<!-- Button -->
	<div class="button-area">
		<?php dsp_button();?>
	</div>
</form>
<?php function dsp_button()
{
    ?><input type="button" name="update" class="button approval"
        value="Chấp nhận hồ sơ"
        onclick="btn_accept_internet_record_onclick();" accesskey="3"
    />

    <input type="button" name="update" class="button save"
        value="<?php echo __('update'); ?> (Alt+2)"
        onclick="btn_update_onclick();" accesskey="2"
    />
    <input type="button" name="delete" class="button delete"
        value="Xoá hồ sơ này"
        onclick="btn_delete_internet_record_onclick();" accesskey="2"
    />

    <input
        type="button" name="cancel" class="button close"
        value="<?php echo __('go back'); ?>"
        onclick="btn_back_onclick();" accesskey="9"
    /><?php
}?>
<script>
    $(document).ready(function() {
        //Fill data
        var formHelper = new DynamicFormHelper('','',document.frmMain);
        formHelper.BindXmlData();

        try{$("#txtName").focus();}catch (e){;}

    });

    function txt_record_type_code_onkeypress(evt)
    {
        if (IE()){
            theKey=window.event.keyCode
        } else {
            theKey=evt.which;
        }

        if(theKey == 13){
            v_record_type_code = trim($("#txt_record_type_code").val()).toUpperCase();
            $("#sel_record_type").val(v_record_type_code);
            if ($("#sel_record_type").val() != '')
            {
                $("#frmMain").submit();
            }
            else
            {
                $("#record_detail").hide();
            }
        }
        return false;
    }

    function sel_record_type_onchange(e)
    {
        e.form.txt_record_type_code.value = e.value;
        if (trim(e.value) != '')
        {
            $("#frmMain").submit();
        }
        else
        {
            $("#record_detail").hide();
        }
    }

    function delete_file(id)
    {
        var f = document.frmMain;
        s = document.getElementById('file_' + id);
        s.style.display = "none";

        f.hdn_deleted_file_id_list.value = $("#hdn_deleted_file_id_list").val() + ','  + id;
    }

    /**
     * Comment
     */
    function btn_accept_internet_record_onclick()
    {
         var url = '<?php echo $this->get_controller_url();?>dsp_accept_internet_record/' + $("#hdn_item_id").val()
            + '/?hdn_item_id=' + $("#hdn_item_id").val()
            + '&record_type_code=' + $("#sel_record_type").val()
            + '&pop_win=1';

        showPopWin(url, 1000, 600, null, true);
    }

    /**
     * btn_delete_internet_record_onclick
     */
    function btn_delete_internet_record_onclick()
    {
        if (confirm('Bạn chắc chắn xoá hồ sơ này?'))
        {
            $("#frmMain").attr('action', $("#controller").val() + 'do_delete_internet_record');
            $("#frmMain").submit();
        }
    }

</script>
<?php $this->template->display('dsp_footer.php');