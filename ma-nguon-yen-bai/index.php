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
 
ini_set('date.timezone','Asia/Ho_Chi_Minh');

define ('DS', DIRECTORY_SEPARATOR);
define('SERVER_ROOT', __DIR__ . DS);

require_once ('config.php');
require_once ('const.php');

//library
require_once (SERVER_ROOT . 'libs' . DS . 'PEAR' . DS . 'PEAR.php');
require_once (SERVER_ROOT . 'libs' . DS . 'PEAR' . DS . 'Savant3.php');
require_once (SERVER_ROOT . 'libs' . DS . 'adodb5' . DS . 'adodb.inc.php');
require_once (SERVER_ROOT . 'libs' . DS . 'jwdate.class.php');
require_once (SERVER_ROOT . 'libs' . DS . 'functions.php');
require_once (SERVER_ROOT . 'libs' . DS . 'session.php');
require_once (SERVER_ROOT . 'libs' . DS . 'lang.php');

//TCPDF
require_once (SERVER_ROOT . 'libs' . DS . 'tcpdf' . DS . 'config' . DS . 'lang/vn.php');
require_once (SERVER_ROOT . 'libs' . DS . 'tcpdf' . DS . 'config' . DS . 'tcpdf_config_alt.php');
define("K_TCPDF_EXTERNAL_CONFIG", true);
require_once (SERVER_ROOT . 'libs' . DS . 'tcpdf' . DS . 'zreport.php');

//MVC
require_once (SERVER_ROOT . 'libs' . DS . 'model.php');
require_once (SERVER_ROOT . 'libs' . DS . 'view.php');
require_once (SERVER_ROOT . 'libs' . DS . 'controller.php');
require_once (SERVER_ROOT . 'libs' . DS . 'bootstrap.php');

//Kiem session ngon ngu
Lang::load_lang('lang_vi');

$mvc_xml = new Bootstrap();
