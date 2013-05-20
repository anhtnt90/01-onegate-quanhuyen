<?php if (!defined('SERVER_ROOT')) exit('No direct script access allowed'); ?>
<?php
//header
$this->template->title = LANG_LISTTYPE_MANAGE_LABEL;
$this->template->display('dsp_header.php');
?>
<form name="frmMain" id="frmMain" action="" method="POST"><?php
    echo $this->hidden('controller',$this->get_controller_url());
    echo $this->hidden('hdn_item_id','0');
    echo $this->hidden('hdn_item_id_list','');

    echo $this->hidden('hdn_dsp_single_method','dsp_single_listtype');
    echo $this->hidden('hdn_dsp_all_method','dsp_all_listtype');
    echo $this->hidden('hdn_update_method','update_listtype');
    echo $this->hidden('hdn_delete_method','delete_listtype');

    //Luu dieu kien loc
    $v_filter 			= isset($_POST['txt_filter']) ?($_POST['txt_filter']) : '';
    ?>
    <!-- filter -->
    <div id="div_filter">
    	<?php echo LANG_FILTER_LISTTYPE_NAME_LABEL?>
		<input type="text" name="txt_filter"
            value="<?php echo $v_filter;?>"
            class="inputbox" size="30" autofocus="autofocus"
            onkeypress="txt_filter_onkeypress(this.form.btn_filter,event);"
		/>
		<input type="button" class="filter_button" onclick="btn_filter_onclick();"
		      name="btn_filter" value="<?php echo _LANG_FILTER_BUTTON;?>"
		/>
	</div>
	<!-- /filter -->
    <?php
    //==========================================================================
    $arr_all_listtype = $VIEW_DATA['arr_all_listtype'];
    $this->load_xml('xml_listtype.xml');
    echo $this->render_form_display_all($arr_all_listtype);

    //Phan trang
    $v_rows_per_page = isset($_POST['sel_rows_per_page']) ? Model::replace_bad_char($_POST['sel_rows_per_page']) : _CONST_DEFAULT_ROWS_PER_PAGE;
    if (isset($arr_all_listtype[1]['TOTAL_RECORD'])){
        $v_page = isset($_POST['sel_goto_page']) ? Model::replace_bad_char($_POST['sel_goto_page']) : 1;
        $v_total_record = $arr_all_listtype[1]['TOTAL_RECORD'];
    } else {
        $v_page = 1;
        $v_total_record = $v_rows_per_page;
    }
    echo $this->paging($v_page, $v_rows_per_page, $v_total_record);
    ?>
    <div class="button-area">
	    <input type="button" name="btn_addnew" class="button" value="<?php echo __('add new');?>" onclick="btn_addnew_onclick();"/>
	    <input type="button" name="btn_delete" class="button" value="<?php echo __('delete');?>" onclick="btn_delete_onclick();"/>
	</div>
</form>
<?php $this->template->display('dsp_footer.php');