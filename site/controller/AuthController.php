<?php 
class AuthController {
    function login() {
        $email = $_POST['email'];
        $customerRepository = new CustomerRepository();
        $customer = $customerRepository->findEmail($email);
        // kiểm tra email này tồn tại trong hệ thống
        if(empty($customer)) {
            $_SESSION['error'] = "Email $email không tồn tại trong hệ thống.";
            header('location: /');
            exit;
        }

        // kiểm tra đúng mật khẩu
        $password = $_POST['password'];
        if(!password_verify($password, $customer->getPassword())) {
            $_SESSION['error'] = "Sai mật khẩu.";
            header('location: /');
            exit;
        }
        echo 'continue...';

        // kiểm tra tài khoản đã được kích hoạt
        
        if(!$customer->getIsActive()) {
            $_SESSION['error'] = "Tài khoản chưa được kích hoạt, vui lòng vào email $email để được kích hoạt tài khoản.";
            header('location: /');
            exit;
        }

        // login thành công
        $_SESSION['email'] = $email;
        $_SESSION['name'] = $customer->getName();

        header('location: ?c=customer&a=show');// trang thông tin tài khoản
    }

    function logout() {
        session_destroy();
        header('location: /');
    }
}
 ?>