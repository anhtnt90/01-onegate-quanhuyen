<?php if (!defined('SERVER_ROOT')) { exit('No direct script access allowed');}
error_reporting(0);

//Cau hinh phan ky
$v_xml_signer_file_path = CONST_APPS_DIR . $this->app_name . DS . 'modules'. DS . $this->module_name . DS
                        . $this->module_name . '_views' . DS . 'xml' . DS . $VIEW_DATA['report_code'] . '_signer.xml';
if (!file_exists($v_xml_signer_file_path))
{
    $v_xml_signer_file_path = CONST_APPS_DIR . $this->app_name . DS . 'modules'. DS . $this->module_name . DS
                        . $this->module_name . '_views' . DS . 'xml' . DS . 'report_signer.xml';
}

//Cau hinh Phan than bao cao
$v_xml_report_file_path = CONST_APPS_DIR . $this->app_name . DS . 'modules'. DS . $this->module_name . DS
                        . $this->module_name . '_views' . DS . 'xml' . DS . $VIEW_DATA['report_code'] . '.xml';
if (!file_exists($v_xml_report_file_path))
{
    die('Chưa cấu hình xml báo cáo cho mẫu này!');
}
$dom_xml_report = simplexml_load_file($v_xml_report_file_path);

include SERVER_ROOT . 'libs' . DS . 'tcpdf' . DS . 'config' . DS . 'lang' . DS . 'vn.php';

//VIEW_DATA
$v_count = count($arr_all_report_data);

// create new PDF document
$v_layout = strtoupper(get_xml_value($dom_xml_report, '/report/@layout')) == 'P' ? 'P': 'L';
$pdf      = new ZREPORT($v_layout, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Ngo Duc Lien');

// set header and footer fonts
$pdf->setPrintHeader(0);
$pdf->SetHeaderData('', PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 023', '');
//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, 'B', 16));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', 13));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
//$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------
// add a page
$pdf->AddPage($v_layout);

$pdf->SetFont('liennd.times', '', 16);
$dom_unit_info = simplexml_load_file('public/xml/xml_unit_info.xml');

$v_unit_name = mb_strtoupper(get_xml_value($dom_unit_info,'/unit/full_name'), 'UTF-8') . "\nVĂN PHÒNG \n ________";
$txt = "Cộng hoà xã hội chủ nghĩa Việt Nam \n Độc lập - Tự do - Hạnh phúc \n _________";

if ($v_layout == 'L')
{
    $pdf->MultiCell(140, 3, $v_unit_name, 0, 'C', 0, 0, '', '', true);
    $pdf->MultiCell(140, 5, $txt, 0, 'C', 0, 0, '', '', true);
}
else
{
    $pdf->MultiCell(100, 3, $v_unit_name, 0, 'C', 0, 0, '', '', true);
    $pdf->MultiCell(100, 5, $txt, 0, 'C', 0, 0, '', '', true);
}

$v_unit_short_name = get_xml_value($dom_unit_info,'/unit/name');
$pdf->report_date($v_unit_short_name);

$pdf->report_title($report_title, $report_subtitle);

$pdf->SetFont('liennd.times', '', 12);
$pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'round', 'dash' => 5, 'color' => array(0, 0, 0)));

//INIT TOTAL
$totals = $dom_xml_report->xpath('//total/item');
foreach ($totals as $total)
{
    if (isset($total->attributes()->id))
    {
        $id     = str_replace('xml/', '',$total->attributes()->id);
        $$id    = 0;
    }
}

//Create HTML string
//CSS
$html = get_xml_value(simplexml_load_file($v_xml_signer_file_path), '//css');
//The header
$v_first_page_head     = '<tr>'; //header cho trang dau tien
$v_cont_page_head      = '<tr>'; //header cho cac trang tiep theo
$cols = $dom_xml_report->xpath("//list/item");
$i=1;
foreach ($cols as $col)
{
    $v_first_page_head .= '<td width="' . strval($col->attributes()->size) . '" align="center"><b>' . str_replace('[br]', '<br/>', trim($col->attributes()->name)) . '</b></td>';
    $v_cont_page_head .= '<td width="' . strval($col->attributes()->size) . '" align="center" class="sub-header"><i>(' . $i . ')</i></td>';
    $i++;
}
$v_first_page_head .= '</tr>';
$v_cont_page_head .= '</tr>';

$v_thead = '<tr><td colspan="1000"><table border="1" cellpadding="5" cellspacing="0">';
$v_thead .= $v_first_page_head . $v_cont_page_head;
//$v_thead .= $v_cont_page_head;
$v_thead .= '</table></td></tr>';
$pdf->set_thead($v_thead);

//The Body

if (isset($arr_report_filter) && sizeof($arr_report_filter) > 0)
{
    $html_filter = '<table class="report_filter" border="0" cellspacing="0" width="500">';
    foreach ($arr_report_filter as $filter)
    {
        $html_filter .= '<tr>';
        $html_filter .= '<td>' . $filter[0] . '</td>';
        $html_filter .= '<td>' . $filter[1] . '</td>';
        $html_filter .= '</tr>';
    }
    $html_filter .= '</table>';

    $pdf->writeHtmlReport($html_filter);
}
$html .= '<table class="report_list" border="1" cellpadding="4" cellspacing="0">'. $v_first_page_head . $v_cont_page_head;
for ($i=0; $i<$v_count; $i++)
{
    $v_xml_data     = isset($arr_all_report_data[$i]['C_XML_DATA']) ? $arr_all_report_data[$i]['C_XML_DATA'] : '<root/>';
    $dom_xml_data   = simplexml_load_string($v_xml_data);

    reset($cols);
    $html .= '<tr>';
    foreach ($cols as $col)
    {
        //Cell data
        $v_col_id   = strval($col->attributes()->id);
        $v_align    = isset($col->attributes()->align) ? ' align="' . $col->attributes()->align . '"' : '';

        if ($v_col_id == 'RN')
        {
            $html .= '<td width="' . $col->attributes()->size . '"' . $v_align . '>' . strval($i+1) . '</td>';
        }
        else
        {
            if (strpos($v_col_id , 'xml/') !== FALSE) //Cot du lieu nam trong XML
            {
                $v_col_id = str_replace('xml/', '', $v_col_id);
                $r = $dom_xml_data->xpath("/data/item[@id='" . $v_col_id . "']/value");
                $v_col_data = sizeof($r) ? $r[0] : '';
            }
            elseif (strpos($v_col_id , 'xml_processing/') !== FALSE) //Cot du lieu nam trong XML Processing
            {

            }
            else //Cot tuong minh
            {
                $v_col_data = isset($arr_all_report_data[$i][$v_col_id]) ? $arr_all_report_data[$i][$v_col_id] : '';

                if ($v_col_id == 'C_RECEIVE_DATE')
                {
                    $v_col_data = $this->break_date_string(jwDate::yyyymmdd_to_ddmmyyyy($v_col_data,1));
                }
                elseif ($v_col_id == 'C_RETURN_DATE')
                {
                    $v_col_data = $this->break_date_string($this->return_date_by_text($v_col_data));
                }
            }

            //TOTAL ???
            $r = $dom_xml_report->xpath("//total/item[@id='$v_col_id']/@id");
            if (sizeof($r) > 0)
            {
                $$v_col_id += floatval($v_col_data);
            }

            //format_number??
            if (isset($col->attributes()->number_format) && parse_boolean($col->attributes()->number_format))
            {
                $v_col_data = number_format($v_col_data, 0, ',', '.');
            }


            $html .= '<td width="' . $col->attributes()->size . '"' . $v_align . '>' . strval($v_col_data) . '</td>';
        }//end if ($v_col_id == 'RN')
    }//end foreach $cols
    $html .= '</tr>';
}//end for $i

//Total
if (sizeof($totals) > 0)
{
    $html .= '<tr class="summary">';
    reset($totals);
    foreach ($totals as $total)
    {
        $colspan        = isset($total->attributes()->colspan) ? ' colspan="' . strval($total->attributes()->colspan) . '"': '';
        $align          = isset($total->attributes()->align) ? ' align="' . strval($total->attributes()->align) . '"': '';

        if (isset($total->attributes()->id))
        {
            $id = strval($total->attributes()->id);
            $v_cell_data = $$id;
        }
        else
        {
            $v_cell_data    = isset($total->attributes()->name) ? strval($total->attributes()->name) : '';
        }
        //format_number??
        if (isset($total->attributes()->number_format) && parse_boolean($total->attributes()->number_format))
        {
            $html .= '<td ' . $colspan . $align . '>' . number_format($v_cell_data, 0, ',', '.') . '</td>';
        }
        else
        {
            $html .= '<td ' . $colspan . $align . '>' . $v_cell_data . '</td>';
        }
    } //end foreach $totals
    $html .= '</tr>';
}
$html .= '</table>';

//Chu ky
$html .= get_xml_value(simplexml_load_file($v_xml_signer_file_path), '//item');

$pdf->writeHtmlReport($html);
$pdf->lastPage();

//echo 'Line: ' . __LINE__ . '<br>File: ' . __FILE__;
//var_dump::display($html);exit;

//Change To Avoid the PDF Error
@ob_end_clean();
//Close and output PDF document
$v_attach_file_path = $VIEW_DATA['report_code'] .  '.pdf';
$pdf->Output($v_attach_file_path, 'I');