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

if (!defined('SERVER_ROOT')) exit('No direct script access allowed');

$arr_single_record = $VIEW_DATA['arr_single_record'];

$v_record_no                = $arr_single_record['C_RECORD_NO'];
$v_record_type_code         = $arr_single_record['RECORD_TYPE_CODE'];
$v_record_type_name         = $arr_single_record['RECORD_TYPE_NAME'];
$v_receive_date             = $arr_single_record['C_RECEIVE_DATE'];
$v_return_date              = $arr_single_record['C_RETURN_DATE'];
$v_xml_data                 = $arr_single_record['C_XML_DATA'];
$v_return_phone_number      = $arr_single_record['C_RETURN_PHONE_NUMBER'];

$v_receive_date         = jwDate::yyyymmdd_to_ddmmyyyy($v_receive_date, TRUE);
$v_return_date          = jwDate::yyyymmdd_to_ddmmyyyy($v_return_date, TRUE);
$v_return_date_by_text  = $this->return_date_by_text($arr_single_record['C_RETURN_DATE']);

$v_xml_form_struct_full_path = str_replace('\\', '/', $this->get_xml_config($v_record_type_code, 'form_struct'));
$v_xslt_full_path            = $this->get_xsl_ho_teplate($v_record_type_code);

$xml_string = '<root>';
$xml_string .= '<static_data>';
$xml_string .= '<record_type_code>' . $v_record_type_code . '</record_type_code>';
$xml_string .= '<record_type_name>' . $v_record_type_name . '</record_type_name>';
$xml_string .= '<record_no>' . $v_record_no . '</record_no>';
$xml_string .= '<receive_date>' . $v_receive_date . '</receive_date>';
$xml_string .= '<return_date>' . $v_return_date_by_text . '</return_date>';
$xml_string .= '<return_phone_number>' . $v_return_phone_number . '</return_phone_number>';
$xml_string .= '<xml_form_struct_full_path><![CDATA[' . $v_xml_form_struct_full_path . ']]></xml_form_struct_full_path>';
$xml_string .= '</static_data>';
$xml_string .= xml_remove_declaration($v_xml_data);
$xml_string .= '</root>';

$xsl_string = file_get_contents($v_xslt_full_path);

$xslt = new Xslt();
$xslt->setXmlString($xml_string);
$xslt->setXslString($xsl_string);

$xslt->setParameter(array(
    'p_site_root' => SITE_ROOT
    , 'p_current_date' => date('d/m/Y')
    )
);

if ($xslt->transform()) {
    $html = $xslt->getOutput();
    $xslt->destroy();
} else {
    $html = '';
}

echo $html;