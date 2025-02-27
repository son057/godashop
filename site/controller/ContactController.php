<?php
class ContactController {
    // chính sách đổi trả
    function form() {
        require 'view/contact/form.php';
    }

    function sendEmail() {
        $name = $_POST['fullname'];
        $email = $_POST['email'];
        $mobile = $_POST['mobile'];
        $message = $_POST['content'];
        $to = SHOP_OWNER;
        $subject = APP_NAME.' - Liên hệ';
        $website = get_domain();
        $content = "Xin chào chủ shop,<br>
        Dưới đây là thông tin khách hàng liên hệ:<br>
        Tên: $name <br>
        Email: $email <br>
        Mobile: $mobile <br>
        Nội dung: $message <br>
        -----------------------<br>
        Được gởi từ trang web $website
        ";
        $emailservice = new EmailService();
        $emailservice->send($to, $subject, $content);
        echo 'Đã gởi email thành công';
    }
}
 ?>