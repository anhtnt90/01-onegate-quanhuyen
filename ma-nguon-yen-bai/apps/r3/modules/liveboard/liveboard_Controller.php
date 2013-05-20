<?php if (!defined('SERVER_ROOT')) exit('No direct script access allowed');

class liveboard_Controller extends Controller {
    function __construct()
    {
        parent::__construct('r3', 'liveboard');
        $this->view->template->show_left_side_bar = FALSE;
        
        /*
        //Kiem tra session
        session::init();
        $login_name = session::get('login_name');
        if ($login_name == NULL)
        {
            session::destroy();
            header('location:' . SITE_ROOT . 'login.php');
            exit;
        }
        */
    }
    
    function main()
    {
        $this->dsp_liveboard();
    }
    
    public function dsp_liveboard()
    {
        $this->view->render('dsp_liveboard');
    }
    
    public function arp_liveboard()
    {
        //Lay danh sach loai hs
        $arr_all_record_type = $this->model->qry_all_record_type();
        
        //Tiep nhan trong ngay
        $arr_count_today_receive_record = $this->model->qry_count_today_receive_record();
        
        //Da ban giao trong ngay
        $arr_count_today_handover_record = $this->model->qry_count_today_handover_record();
        
        //Dang xu ly
        $arr_count_execing_record = $this->model->qry_count_execing_record();
        
        //Dung tien do
        $arr_count_in_schedule_record = $this->model->qry_count_in_schedule_record();
        
        //Cham tien do
        $arr_count_over_deadline_record = $this->model->qry_count_over_deadline_record();
        
        //Qua han
        $arr_count_expried_record = $this->model->qry_count_expried_record();
        
        //Bo sung
        $arr_count_supplement_record = $this->model->qry_count_supplement_record();
        
        //Cho tra kq
        $arr_count_waiting_for_return_record = $this->model->qry_count_waiting_for_return_record();
        
        //Da tra kq
        $arr_count_returned_record = $this->model->qry_count_returned_record();
        
        $i=1;
        ?>
        <?php foreach ($arr_all_record_type as $v_record_type_id => $arr_record_type):
            $v_record_type_code = $arr_record_type['C_CODE'];
            $v_record_type_name = $arr_record_type['C_NAME'];
            $v_tooltip = $v_record_type_code . ' - ' . $v_record_type_name;
            ?>
            <tr>
                <td><?php echo $i;?></td>
                <td class="code" onmouseover="Tip('<?php echo $v_tooltip;?>');" onmouseout="UnTip()"><?php echo $v_record_type_code;?></td>
                <td><?php echo (isset($arr_count_today_receive_record[$v_record_type_id])) ? $arr_count_today_receive_record[$v_record_type_id] : '';?></td>
                <td><?php echo (isset($arr_count_today_handover_record[$v_record_type_id])) ? $arr_count_today_handover_record[$v_record_type_id] : '';?></td>
                <td class="e"><?php echo (isset($arr_count_execing_record[$v_record_type_id])) ? $arr_count_execing_record[$v_record_type_id] : '';?></td>
                <td class="is"><?php echo (isset($arr_count_in_schedule_record[$v_record_type_id])) ? $arr_count_in_schedule_record[$v_record_type_id] : '';?></td>
                <td class="od"><?php echo (isset($arr_count_over_deadline_record[$v_record_type_id])) ? $arr_count_over_deadline_record[$v_record_type_id] : '';?></td>
                <td class="od"><?php echo (isset($arr_count_expried_record[$v_record_type_id])) ? $arr_count_expried_record[$v_record_type_id] : '';?></td>
                <td><?php echo (isset($arr_count_supplement_record[$v_record_type_id])) ? $arr_count_supplement_record[$v_record_type_id] : '';?></td>
                <td><?php echo (isset($arr_count_waiting_for_return_record[$v_record_type_id])) ? $arr_count_waiting_for_return_record[$v_record_type_id] : '';?></td>
                <td class="r left"><?php echo (isset($arr_count_returned_record[$v_record_type_id])) ? $arr_count_returned_record[$v_record_type_id] : '';?></td>
            </tr>
            <?php $i++;?>
        <?php endforeach;?>
        <?php 
    }//end func
}