<?php if (!defined('SERVER_ROOT')) exit('No direct script access allowed');

//header
$this->template->title = 'Tập quy luật lọc hồ sơ';
$this->template->display('dsp_header.php');
?>
<form name="frmMain" id="frmMain" action="" method="POST">
    <?php
    echo $this->hidden('controller',$this->get_controller_url());
    echo $this->hidden('hdn_item_id','0');
    echo $this->hidden('hdn_item_id_list','');
    
    echo $this->hidden('hdn_dsp_single_method','dsp_single_rule');
    echo $this->hidden('hdn_dsp_all_method', 'dsp_all_rule');
    echo $this->hidden('hdn_update_method','update_rule');
    echo $this->hidden('hdn_delete_method','delete_rule');
    ?>
    <div id="solid-button">
        <input type="button" class="solid add" value="<?php echo __('add new');?>"
               onclick="btn_addnew_onclick();" accesskey="2"/>
        <input type="button" name="addnew" class="solid delete" value="<?php echo __('delete');?>"
               onclick="btn_delete_onclick();" />
        <input type="button" name="batchrule" class="solid batchrule" value="<?php echo __('Các luật xử lý theo lô');?>"
               onclick="btn_batchrule_onclick();" />
    </div>
    <div class="clear"></div>
    <div id="procedure">
        <?php if ($this->load_xml('xml_rule_list.xml')):?>
            <?php echo $this->render_form_display_all($arr_all_rule);?>
        <?php endif;?>
    </div>
    <?php echo $this->paging2($arr_all_rule);?>
</form>
<script>
function btn_batchrule_onclick()
{
	var url = '<?php echo $this->get_controller_url();?>dsp_plaintext_auto_lock_unlock/';

	url += '&pop_win=1';

    showPopWin(url, 650, 550);
}
    </script>
<?php $this->template->display('dsp_footer.php');