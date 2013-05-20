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
if (!defined('SERVER_ROOT')) { exit('No direct script access allowed');}

//display header
$this->template->title = 'Thêm ý kiến';

$v_pop_win = isset($_REQUEST['pop_win']) ? '_pop_win' : '';
$this->template->display('dsp_header' . $v_pop_win . '.php');

$v_record_id = isset($_REQUEST['hdn_item_id']) ? $_REQUEST['hdn_item_id'] : 0;

($v_record_id > 0) OR DIE();

?>
<form name="frmMain" id="frmMain" method="POST" action="<?php echo $this->get_controller_url();?>do_add_comment">
    <?php echo $this->hidden('hdn_item_id', $v_record_id);?>
    <br/>
    <table class="none-border-table" style="width:100%">
        <tr>
            <td width="25%" valign="top" style="vertical-align:top">Nội dung ý kiến: <span class="required">*</span></td>
            <td>
                <textarea style="width: 320px; height: 161px; margin: 0px;" rows="2" 
                    name="txt_content" id="txt_content" cols="20" maxlength="400"
                    ></textarea>
           </td>
        </tr>
    </table>
    <div class="clear">&nbsp;</div>
    <!-- Buttons -->
    <div class="button-area">
        <input type="button" name="btn_do_add_comment" class="button save" value="<?php echo __('update');?>" onclick="btn_do_add_comment_onclick();" />
        <?php $v_back_action = ($v_pop_win === '') ? 'btn_back_onclick();' : 'try{window.parent.hidePopWin();}catch(e){window.close();};';?>
        <input type="button" name="cancel" class="button close" value="<?php echo __('close window'); ?>" onclick="<?php echo $v_back_action;?>"/>
    </div>
</form>
<script>
    var f=document.frmMain;
    f.txt_content.focus();
    function btn_do_add_comment_onclick()
    {
        var v_content = trim(f.txt_content.value);

        if (v_content == '')
        {
            alert('Bạn chưa nhập nội dung!');
            f.txt_content.focus();
            return false;
        }
        //add comment, reload comment list, close window
        //jQuery.post( url, [data], [callback], [type] )
        jQuery.post(
                '<?php echo $this->get_controller_url();?>do_add_comment'
                ,{ hdn_item_id: $("#hdn_item_id").val()
                    ,txt_content: $("#txt_content").val()
                    ,sid:'<?php echo session_id();?>'
                 }
                ,function( data ) {
                    html = '';
                    for (i=0; i<data.length; i++)
                    {
                        v_comment_id = data[i].comment_id;
                        v_user_code = data[i].user_code;
                        v_user_name = data[i].user_name;
                        v_job_title = data[i].job_title;
                        v_create_date = data[i].date;
                        v_content = data[i].content;
                        v_type = data[i].type;

                        v_html_class = (v_type == 1) ? ' class="bod_comment"' : '';

                        html += '<tr data-cid="c_' + v_comment_id + '"' + v_html_class + '>';
                        html += '<td class="center"><input type="checkbox" name="chk_comment" value="' + v_comment_id + '" data-user="' + v_user_code + '"/></td>';
                        html += '<td>' + v_content + '</td>';
                        html += '<td>' + v_user_name + '(' + v_job_title + ')</td>';
                        html += '<td>' + v_create_date + '</td>';
                        html += '</tr>';
                    }

                    //$('#tbl_comment_header', window.parent.document).prepend(html);
                    $('#tbl_comment_header', window.parent.document).after(html);
                    window.parent.hidePopWin();
                }
                , "json"
       );
    }
</script>
<?php $this->template->display('dsp_footer' .$v_pop_win . '.php');