<?php if (!defined('SERVER_ROOT')) {exit('No direct script access allowed');}?>
<?php
//header
$this->template->title = 'Danh sách đối tượng danh mục';
$this->template->display('dsp_header.php');
?>
<h2><?php echo LANG_LIST_MANAGE_LABEL;?></h2>
<form name="frmMain" id="frmMain" action="" method="POST">
    <?php
    echo $this->hidden('controller',$this->get_controller_url());
    echo $this->hidden('hdn_item_id','0');
    echo $this->hidden('hdn_item_id_list','');

    echo $this->hidden('hdn_dsp_single_method','dsp_single_list');
    echo $this->hidden('hdn_dsp_all_method','dsp_all_list');
    echo $this->hidden('hdn_update_method','update_list');
    echo $this->hidden('hdn_delete_method','delete_list');

    //Luu dieu kien loc
    $v_filter 			= isset($_POST['txt_filter']) ?($_POST['txt_filter']) : '';
    $v_listtype_id 		= isset($_POST['sel_listtype_filter']) ? ($_POST['sel_listtype_filter']) : '0';

    echo $this->hidden('hdn_listtype_id', $v_listtype_id);
    ?>
    <!-- filter -->
    <div id="div_filter">
    	<?php echo LANG_FILTER_LISTTYPE_LABEL?>
        <select name="sel_listtype_filter" onchange="sel_listtype_filter_onchange(this.value);" style="Z-INDEX:-1;">
            <?php echo $this->generate_select_option($VIEW_DATA['arr_all_listtype_option'],$v_listtype_id);?>
        </select>

        <?php echo LANG_FILTER_LIST_NAME_LABEL;?>
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
    $arr_all_list = $VIEW_DATA['arr_all_list'];
    $this->load_xml('xml_list.xml');
    echo $this->render_form_display_all($arr_all_list);

    //Phan trang
    $v_rows_per_page = isset($_POST['sel_rows_per_page']) ? Model::replace_bad_char($_POST['sel_rows_per_page']) : _CONST_DEFAULT_ROWS_PER_PAGE;
    if (isset($arr_all_list[1]['TOTAL_RECORD'])){
        $v_page = isset($_POST['sel_goto_page']) ? Model::replace_bad_char($_POST['sel_goto_page']) : 1;
        $v_total_record = $arr_all_list[1]['TOTAL_RECORD'];
    } else {
        $v_page = 1;
        $v_total_record = $v_rows_per_page;
    }
    echo $this->paging($v_page, $v_rows_per_page, $v_total_record);
    ?>

    <div class="button-area">
	    <input type="button" name="addnew" class="button" value="<?php echo __('add new');?>" onclick="btn_addnew_onclick();"/>
	    <input type="button" name="trash" class="button" value="<?php echo __('delete');?>" onclick="btn_delete_onclick();"/>
	</div>
</form>
<?php $this->template->display('dsp_footer.php');