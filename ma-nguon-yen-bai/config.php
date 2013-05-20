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

if (!defined('SERVER_ROOT')) exit('No direct script access allowed');?>
<?php
define('DEBUG_MODE', 0);
define('SITE_ROOT','/yen-bai/');

define('DATABASE_TYPE','MYSQL');//Be MSSQL or ORACLE or MYSQL

//Oracle Setting
define('CONST_ORACLE_DSN','oci8://mvcxml:mvcxml@172.16.1.252/XE');

//SQL Server Setting
define('CONST_MSSQL_DSN','PROVIDER=SQLOLEDB;DRIVER={SQL Server};SERVER=172.16.1.252;DATABASE=Go-Office;UID=sa;PWD=P@ssw0rd;');

//MySQL Server Setting
define('CONST_MYSQL_DSN','mysql://go-office:123456@172.16.1.200/yen-bai');

//Tien to bang
define('_CONST_TABLE_PREFIX','t_');

//Dung Cache
define('CONST_USE_ADODB_CACHE_FOR_REPORT',0);

//DB tren cung server
define('CONST_DATABASE_IS_SAME_SERVER',1);
//Pear libs
define('_PATH_TO_PEAR', SERVER_ROOT . 'libs/PEAR/');
@ini_set('include_path', _PATH_TO_PEAR);

//apps dir
define('CONST_APPS_DIR', SERVER_ROOT . 'apps' . DS);

//DOC upload folder
define('CONST_SERVER_DOC_FILE_UPLOAD_DIR', SERVER_ROOT . 'uploads/');
define('CONST_SITE_DOC_FILE_UPLOAD_DIR', SITE_ROOT . 'uploads/');

//ADLDAP
define('AUTH_MODE', 'AD');
define('AD_DOMAIN_NAME', 'zinxu.net');
define('AD_ACCOUNT_SUFFIX', '@zinxu.net');
define('AD_BASEDN', 'DC=zinxu,DC=net');
define('AD_DC_LIST', 'dc01.zinxu.net');
define('AD_ADMIN_USER','Administrator');
define('AD_ADMIN_PASSWORD','P@ssw0rd');

/******************************************************************************/
@require_once _PATH_TO_PEAR . 'Var_Dump.php';
if (DEBUG_MODE > 0)
{
    error_reporting(E_ALL);
}
else
{
    error_reporting(0);
}