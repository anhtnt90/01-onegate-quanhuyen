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


if (!defined('SERVER_ROOT')) {exit('No direct script access allowed');}?>
                </div><!-- #content_right-->
            <?php if ($this->show_left_side_bar): ?>
                </div> <!-- .container_24 #wrapper -->
            <?php endif; ?>
            <div class="clear">&nbsp;</div>
            <div class="grid_24">
                <div id="footer">
                    <hr>
                    <?php echo get_xml_value(simplexml_load_file(SERVER_ROOT . 'public/xml/xml_unit_info.xml'), '//full_name')?>-Bộ phận tiếp nhận và trả hồ sơ <br/>
                    Thực hiện cải cách thủ tục hành chính theo cơ chế "một cửa"
                </div>
            </div>
            <div class="clear">&nbsp;</div>
        </div> <!-- class="container_24" #main -->
    </body>
</html>