<?php if (!defined('SERVER_ROOT')) exit('No direct script access allowed');

class nop_ho_so_qua_mang_internet_Controller extends Controller {

    const CONST_NOP_HO_SO_URL_REWRITE = 'nop_ho_so/';

    function __construct()
    {
        parent::__construct('r3', 'nop_ho_so_qua_mang_internet');
        $this->view->template->show_left_side_bar = 1;

        $this->view->template->controller_url = $this->view->get_controller_url();


        include_once SERVER_ROOT . 'libs' . DS . 'recaptchalib.php';

        @session::init();
    }
    private function _check_init_session()
    {
        if (Session::get('nop_ho_so') != 1)
        {
            echo "Chào mừng blah blah";
            echo '<br/>';
            echo "Xin mời bấm <a href='" . SITE_ROOT . self::CONST_NOP_HO_SO_URL_REWRITE . "'>vào đây</a> để tiếp tục";
            Session::set('nop_ho_so',1);
            die();
        }
    }

    function main()
    {
        $this->nhap_thong_tin();
    }

    public function nhap_thong_tin($record_type_id=0)
    {
        $this->_check_init_session();

        $record_type_id = replace_bad_char($record_type_id);
        if (!is_id_number($record_type_id)) $record_type_id = 0;

        //Danh sách loại thủ tục tiếp nhận HỒ SƠ qua mạng
        $VIEW_DATA['arr_all_record_type_option'] = $this->model->qry_all_send_over_internet_record_type();
        $VIEW_DATA['active_record_type_id'] = $record_type_id;
        if ($record_type_id > 0)
        {
            $VIEW_DATA['v_record_type_code'] = $this->model->db->getOne('Select C_CODE From t_r3_record_type Where PK_RECORD_TYPE=?',array($record_type_id));
        }
        $this->view->render('main', $VIEW_DATA);
    }

    public function do_send()
    {
        $this->_check_init_session();

        $this->model->do_send();
    }
}