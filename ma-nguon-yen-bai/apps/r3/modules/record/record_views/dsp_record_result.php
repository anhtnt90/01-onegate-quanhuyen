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

//display header
$this->template->title = 'Kết quả hồ sơ';

$v_pop_win = isset($_REQUEST['pop_win']) ? '_pop_win' : '';
$this->template->display('dsp_header' . $v_pop_win . '.php');
?>
<form name="frmMain" id="frmMain" action="<?php echo $this->get_controller_url();?>do_return_record" method="POST">
	<?php
	echo $this->hidden('controller',$this->get_controller_url());
	echo $this->hidden('hdn_item_id',$v_record_id);
	echo $this->hidden('hdn_item_id_list',$v_record_id);
	echo $this->hidden('sel_record_type',$record_type);
	echo $this->hidden('hdn_update_method','do_return_record');
	echo $this->hidden('pop_win','1');

    echo $this->hidden('XmlData','');
    ?>
    <div id="record_result">
        <?php echo $this->transform($this->get_xml_config(NULL, 'result')); ?>
	</div>
    <!-- Button -->
	<div class="button-area">
		<input type="button" name="update" class="button save" value="<?php echo __('update'); ?> (Alt+2)" onclick="btn_update_onclick();" accesskey="2" />
        <?php $v_back_action = ($v_pop_win === '') ? 'btn_back_onclick();' : 'try{window.parent.hidePopWin();}catch(e){window.close();};';?>
        <input type="button" name="cancel" class="button close" value="<?php echo __('close window'); ?>" onclick="<?php echo $v_back_action;?>"/>
	</div>
</form>
<?php $this->template->display('dsp_footer' .$v_pop_win . '.php');