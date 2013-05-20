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
?>
<html>
    <?php echo $v_record_list;?>
<table cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center" style="font-weight: bold">
                UBND HUYỆN LẠNG GIANG
                <br />
                <u>
                    Phòng Tài nguyên - Môi trường</u>
            </td>
            <td align="center">
                CỘNG HOÀ XÃ HỘI CHỦ NGHĨA VIỆT NAM
                <br />
                <strong><u>Độc lập - Tự do - Hạnh phúc</u></strong>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center" style="font-weight: bold">
                <br />
                <h3>
                    PHIẾU BỒ SUNG HỒ SƠ</h3>
                <br />
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <b><u>Kính gửi:</u></b><i> Bộ phận Một cửa</i>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                &nbsp;&nbsp;&nbsp;&nbsp;Phòng Tài nguyên - Môi trường nhận được hồ sơ Tách thửa, hợp thửa đất đã có giấy chứng nhận quyền sử dụng đất (Liên thông với cấp xã) do Bộ phận Một cửa chuyển xuống <br/> &nbsp;&nbsp;&nbsp;&nbsp;Sau khi kiểm tra hồ sơ, đối chiếu với quy định pháp luật  về Tách thửa, hợp thửa đất đã có giấy chứng nhận quyền sử dụng đất (Liên thông với cấp xã), Phòng Tài nguyên - Môi trường trả lại hồ sơ cho Bộ phận Một cửa để bổ sung.
                <br/>&nbsp;&nbsp;&nbsp;&nbsp; Cụ thể như sau:
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table cellpadding='0' cellspacing='0' width='100%' class='print-addition'>
                    <script>
                        var count_record = <?php echo substr_count($v_record_list ,',');?>;
                    </script>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="border-solid">
                *Lý do:<br />
                <textarea class="text ui-widget-content ui-corner-all" cols="20" id="lydo" name="lydo" rows="2" style="width:100%;height:150px;border:none;">
</textarea>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                &nbsp;&nbsp;&nbsp;&nbsp; Vậy, đề nghị Bộ phận Một cửa hoàn thiện theo quy định.
            </td>
        </tr>
        <tr>
            <td >
                <b>Nơi nhận: </b> <br/> - Như trên; <br/>- TT UBND TP; <br/> - Bộ phận "Một cửa"; <br/> - Lưu VT;
            </td>
            <td align="center">
                <b>KT.TRƯỞNG PHÒNG <br/> PHÓ TRƯỞNG PHÒNG</b>
            </td>
        </tr>
    </table>
</html>