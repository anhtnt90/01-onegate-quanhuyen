<?php
/**
 * @copyright	Copyright (C) 2012 Tam Viet Tech. All rights reserved.
 * 
 * @author		Ngo Duc Lien <liennd@gmail.com>
 * @author		Luong Thanh Binh <ltbinh@gmail.com>
 */

if (!defined('SERVER_ROOT')) exit('No direct script access allowed');?>
<?php

class Controller {
    public $view;
    public $model;

    protected $app_name ='';
    protected $module_name = '';

    function __construct($app,$module)
    {
        //Load custom functions
        $func = SERVER_ROOT . 'apps' . DS . $app . DS . 'functions.php';
        if (is_file($func))
        {
            @require_once($func);
        }

        $this->app_name =$app;
        $this->module_name = $module;

        $v = $app . '_View';
        $this->view = (class_exists($v)) ? new $v($app, $module) : new View($app,$module);

        $this->_load_model($app, $module);
        $this->model->app_name = $app;
        $this->model->module_name = $module;
    }

    private function _load_model()
    {
        $path = 'apps'.DS . $this->app_name . DS .'modules'.DS . $this->module_name . DS . $this->module_name. '_Model.php';
        if (file_exists($path))
        {
            require $path;
            $model_name = $this->module_name . '_Model';
            $this->model = new $model_name;
            $this->model->db->debug = DEBUG_MODE;
        }
        else
        {
            $this->error(1);
        }
    }

    public static function check_login()
    {
        session::init();
        $login_name = session::get('login_name');
        if ($login_name == NULL)
        {
            session::destroy();
            header('location:' . SITE_ROOT . 'login.php');
            return FALSE;
        }

        return TRUE;
    }

    public function check_permission($function_code, $app_name='')
    {
        @Session::init();
        $app_name = ($app_name != '') ? strtoupper($app_name) : strtoupper($this->app_name);
        $function_code =  $app_name . '::' . $function_code;
        return in_array($function_code, Session::get('arr_function_code'));
    }

    public static function get_post_var($html_object_name, $defaul='', $is_replace_bad_char=TRUE)
    {
        $var = isset($_POST[$html_object_name]) ? $_POST[$html_object_name] : $defaul;

        if ($is_replace_bad_char)
        {
            return replace_bad_char ($var);
        }

        return $var;
    }

    public function error($error_code)
    {
        switch ($error_code)
        {
            case 1: //Ma loi 1: Không thấy file Model!
                die('Không thấy file Model!');
                break;
            case 2:
                die('xxx');
                break;
        }
    }

    public function access_denied()
    {
        die('Bạn không có quyền thực hiện chức năng này!');
    }
}