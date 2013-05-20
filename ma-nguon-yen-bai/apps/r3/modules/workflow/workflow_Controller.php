<?php if (!defined('SERVER_ROOT')) exit('No direct script access allowed');

class workflow_Controller extends Controller {

    function __construct()
    {
        parent::__construct('r3', 'workflow');
        $this->view->template->show_left_side_bar = FALSE;
        $this->view->template->app_name = 'R3';

        //Kiem tra session
        session::init();
        $login_name = session::get('login_name');
        if ($login_name == NULL)
        {
            session::destroy();
            header('location:' . SITE_ROOT . 'login.php');
            exit;
        }
    }

    public function main()
    {
        $this->dsp_single_workflow();
    }

    public function dsp_single_workflow()
    {
        $v_record_type_code = trim(get_request_var('sel_record_type'));

        $VIEW_DATA['arr_all_record_type']   = $this->model->qry_all_record_type_option();
        $VIEW_DATA['record_type_code']      = $v_record_type_code;

        $VIEW_DATA['xml_user_task']         = $this->model->qry_all_user_task($v_record_type_code);

        $this->view->render('dsp_single_workflow', $VIEW_DATA);
    }

    public function assign_user_on_task()
    {
        $this->model->assign_user_on_task();
    }
    function remove_user_on_task()
    {
        $this->model->remove_user_on_task();
    }

    public function ui()
    {
        $v_record_type_code = trim(get_post_var('sel_record_type'));

        $VIEW_DATA['v_record_type_code']    = strtoupper($v_record_type_code);
        $VIEW_DATA['arr_all_record_type']   = $this->model->qry_all_record_type_option();

        $this->view->render('dsp_single_workflow_ui', $VIEW_DATA);
    }

    public function do_update_step_order_by_ui()
    {
        $arr_step_order = get_post_var('step',NULL);
        $v_xml_config_dir = SERVER_ROOT . "apps\\r3\\xml-config\\";

        if ($arr_step_order == NULL)
        {
            //Khong thay doi buoc, cong viec trong quy trinh
            $v_new_xml_flow = session::get('v_current_xml_flow');
        }
        else
        {
            //Khoi tao lai quy trinh moi
            $docDest = new DOMDocument();
            $docDest->loadXML(session::get('v_current_xml_flow'));
            $dels=$docDest->getElementsByTagName('process');
            foreach($dels as $del)
            {
                while($del->hasChildNodes())
                {
                    $del->removeChild($del->childNodes->item(0));
                }
            }

            $docSource = new DOMDocument();
            $docSource->loadXML(session::get('v_current_xml_flow'));
            $xpath = new DOMXPath($docSource);
            foreach ($arr_step_order as $v_step_order)
            {
                $result = $xpath->query("//step[position()=$v_step_order]")->item(0);
                $result = $docDest->importNode($result, true);
                $items = $docDest->getElementsByTagName('process')->item(0);
                $items->appendChild($result);
            }

            //renext
            $xpath = new DOMXPath($docDest);
            $v_count_task = $xpath->query("//task")->length;
            for ($i=1; $i<= $v_count_task; $i++)
            {
                $current_task = $xpath->query("//task")->item($i-1);
                if ($i<$v_count_task)
                {
                    $next_task = $xpath->query("//task")->item($i);
                    $v_next_task_code = $next_task->getAttribute('code');
                }
                else
                {
                    $v_next_task_code = 'NULL';
                }

                $current_task->setAttribute('next', $v_next_task_code);
            }

            $v_new_xml_flow = $docDest->saveXML();
        }

        //ghi file
        $dom = simplexml_load_string(xml_add_declaration($v_new_xml_flow));
        $v_record_type_code = get_xml_value($dom, '/process/@code');
        $v_rt_dir = $v_xml_config_dir . $v_record_type_code . DS;
        $v_new_flow_file_path = $v_rt_dir . $v_record_type_code . '_workflow.xml';
        if (file_put_contents($v_new_flow_file_path, $v_new_xml_flow))
        {
            echo 'Cập nhật thành công';
        }
        else
        {
            echo 'Cập nhật thất bại';
        }
    }

    public function do_update_task_order_by_ui()
    {
    }

    /**
     * Hien thi form edit thong tin quy trinh
     */
    public function dsp_single_process()
    {
        $VIEW_DATA['v_record_type_code'] = get_request_var('record_type_code');
        $VIEW_DATA['v_record_type_name'] = get_request_var('record_type_name');
        $VIEW_DATA['v_total_time']       = get_request_var('total_time',0);
        $VIEW_DATA['v_fee']              = get_request_var('fee',0);

        $this->view->render('dsp_single_process', $VIEW_DATA);
    }

    public function do_update_process()
    {
        $v_xml_flow = session::get('v_current_xml_flow');
        $dom = simplexml_load_string($v_xml_flow);
        $p = $dom->xpath('/process');
        $dom_p = $p[0];

        $v_new_total_time = get_post_var('txt_total_time');
        $v_new_fee = get_post_var('txt_fee');
        $dom_p->attributes()->totaltime = $v_new_total_time;
        $dom_p->attributes()->fee = $v_new_fee;

        $v_xml_flow = $dom_p->saveXML();
        session::set('v_current_xml_flow', $v_xml_flow);

        $this->model->popup_exec_done('[' . $v_new_total_time . ',' . $v_new_fee .']');
    }
    function dsp_single_step()
    {
        $this->view->render('dsp_single_step');
    }

    public function do_update_step()
    {
        $v_step_id = get_request_var('hdn_step_id');
        $v_xml_flow = session::get('v_current_xml_flow');
        $dom = simplexml_load_string($v_xml_flow);
        $p = $dom->xpath("//step[position()=$v_step_id]");
        $dom_p = $p[0];

        $v_new_name     = get_post_var('txt_name');
        $v_new_group    = get_post_var('txt_group');
        $v_new_time     = get_post_var('txt_time');

        $dom_p->attributes()->name = $v_new_name;
        $dom_p->attributes()->group = $v_new_group;
        $dom_p->attributes()->time = $v_new_time;

        $v_xml_flow = $dom->saveXML();
        session::set('v_current_xml_flow', $v_xml_flow);
        $this->model->popup_exec_done('[' . $v_step_id . ',"' . $v_new_name . '","' . $v_new_group . '",' . $v_new_time . ']');
    }

    public function dsp_all_task_in_step($step_id)
    {
        $VIEW_DATA['v_step_id'] = $step_id;
        $this->view->render('dsp_all_task_in_step', $VIEW_DATA);
    }

    public function dsp_single_task($step_id, $task_id)
    {

    }

    public function dsp_plaintext_workflow()
    {
        $this->view->render('dsp_plaintext_workflow');
    }

    public function update_plaintext_workflow()
    {
        $v_xml_flow_file_path = get_post_var('hdn_xml_flow_file_path', '',0);
        $v_xml_string         = get_post_var('txt_plaintext_workflow','<process/>',0);

        $ok = TRUE;
        $v_message = 'Cập nhật dữ liệu thất bại!. ';
        //Kiem tra xml welform
        $dom_flow = @simplexml_load_string($v_xml_string);
        if ($dom_flow != FALSE)
        {
            //Kiem tra task noi tiep nhau
            $v_next_task_is_valid = TRUE;
            //$tasks = $dom_flow->xpath('//task');
            $tasks = $dom_flow->xpath("//task[not(../@no_chain = 'true')]");
            for ($i=0, $n=count($tasks); $i<$n; $i++)
            {
                $task = $tasks[$i];
                $v_next = strval($task->attributes()->next);
                //Chi lay task chinh
				$v_next = trim(preg_replace('/\[([A-Z0-9::_]*)\]/', '', $v_next));
				$v_next = str_replace(_CONST_FINISH_NO_CHAIN_STEP_TASK, NULL, $v_next);
                if ($i < ($n - 1))
                {
                    $next_task_obj = $tasks[$i + 1];
                    $v_next_task_code = strval($next_task_obj->attributes()->code);
                }
                else
                {
                    $v_next_task_code = NULL;
                }
                if ( ($v_next != $v_next_task_code) && ($v_next_task_code != NULL))
                {
                    $v_message .= $v_next . ' -> ' . $v_next_task_code;
                    $ok = FALSE;
                    break;
                }
            }
            
            //CHECK NO_CHAIN task
            $tasks = $dom_flow->xpath("//task[../@no_chain = 'true']");
            for ($i=0, $n=count($tasks); $i<$n; $i++)
            {
                $task = $tasks[$i];
                $v_next = strval($task->attributes()->next);
                //Chi lay task chinh
				$v_next = trim(preg_replace('/\[([A-Z0-9::_]*)\]/', '', $v_next));
				$v_next = str_replace(_CONST_FINISH_NO_CHAIN_STEP_TASK, NULL, $v_next);
                if ($i < ($n - 1))
                {
                    $next_task_obj = $tasks[$i + 1];
                    $v_next_task_code = strval($next_task_obj->attributes()->code);
                }
                else
                {
                    $v_next_task_code = NULL;
                }
                if ( ($v_next != $v_next_task_code) && ($v_next_task_code != NULL))
                {
                    $v_message .= $v_next . ' -> ' . $v_next_task_code;
                    $ok = FALSE;
                    break;
                }
            }
            
            //Kiem tra totaltime
            $v_totaltime = (float)get_xml_value($dom_flow, '/process/@totaltime');
            $v_time_by_sum = 0;
            $steps = $dom_flow->xpath('//step');
            foreach ($steps as $step)
            {
                $v_time_by_sum += (float)$step->attributes()->time;
            }
            if ($v_totaltime != $v_time_by_sum)
            {
                $ok = FALSE;
                $v_message .= 'Sai TotalTime';
            }
        }
        else
        {
            $ok = FALSE;
            $v_message .= 'XML không well-form';
        }

        //Ghi file
        if ($ok)
        {
            $v_dir = dirname ($v_xml_flow_file_path);
            if (!is_dir($v_dir))
            {
                @mkdir($v_dir);
            }
            $r = @file_put_contents($v_xml_flow_file_path, $v_xml_string);
            if ($r === FALSE OR $r === 0)
            {
                $ok = FALSE;
                $v_message .= 'Không thể ghi được file dữ liệu!';
            }
        }

        if ($ok)
        {
            //Xoa het thong tin phan cong hien tai
            $stmt = "Delete From t_r3_user_task Where C_RECORD_TYPE_CODE=?";
            $params = array(get_xml_value($dom_flow,'/process/@code'));
            @$this->model->db->Execute($stmt, $params);
            
            $this->model->popup_exec_done();
        }
        else
        {
            $this->model->popup_exec_fail($v_message);
        }
    }
    
    public function check_workflow_time()
    {
        $arr_all_rt = $this->model->db->getAssoc("SELECT TRIM(C_CODE) C_CODE, C_NAME FROM t_r3_record_type WHERE C_STATUS>0 Order By C_CODE");
    
        $v_xml_config_dir = SERVER_ROOT . "apps\\r3\\xml-config\\";
        foreach ($arr_all_rt as $code => $name)
        {
            $v_flow_file_path = $v_xml_config_dir . $code . DS . $code . '_workflow.xml';
            $dom_workflow = simplexml_load_file($v_flow_file_path);
    
            $v_totaltime = get_xml_value($dom_workflow, '/process/@totaltime');
            $steps = $dom_workflow->xpath('//step');
            $v_time_by_sum = 0;
            foreach ($steps as $step)
            {
                $v_time_by_sum += (float)$step->attributes()->time;
            }
    
            echo '<br>-------------------------------------------';
            echo '<br>++' . $code;
            echo '<br>++Totaltime=' . $v_totaltime;
            echo '<br>++Sum=' . $v_time_by_sum;
            echo '<br>=>';
            if ($v_totaltime == $v_time_by_sum)
            {
                echo '<font style="color:blue;font-weight:bold">OK</font>';
            }
            else
            {
                echo '<font style="color:red;font-weight:bold">FAIL</font>';
            }
        }
    }
}
