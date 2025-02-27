<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
class CustomerController
{
    // trang thông tin tài khoản
    public function show()
    {
        $email = $_SESSION['email'];
        $customerRepository = new CustomerRepository();
        $customer = $customerRepository->findEmail($email);
        require 'view/customer/show.php';
    }

    public function updateInfo()
    {
        //
        var_dump($_POST);
    }

    // trang địa chỉ giao hàng mặc định
    public function shippingDefault()
    {
        require 'view/customer/shippingDefault.php';
    }

    // trang danh sách đơn hàng
    public function orders()
    {
        $email = $_SESSION['email'];
        $customerRepository = new CustomerRepository();
        $customer = $customerRepository->findEmail($email);
        $customer_id = $customer->getId();
        $orderRepository = new OrderRepository();
        // lấy đơn hàng của người đăng nhập
        $orders = $orderRepository->getByCustomerId($customer_id);
        require 'view/customer/orders.php';
    }

    // trang chi tiết đơn hàng
    public function orderDetail()
    {
        $id = $_GET['id'];
        $orderRepository = new OrderRepository();
        $order = $orderRepository->find($id);
        require 'view/customer/orderDetail.php';
    }

    public function register()
    {
        $secret = GOOGLE_RECAPTCHA_SECRET;

        $domain = get_host_name_without_port();
        $gRecaptchaResponse = $_POST['g-recaptcha-response'];
        $remoteIp = "127.0.0.1";
        $recaptcha = new \ReCaptcha\ReCaptcha($secret);
        $resp = $recaptcha->setExpectedHostname($domain)
            ->verify($gRecaptchaResponse, $remoteIp);
        if (!$resp->isSuccess()) {
            $errors = $resp->getErrorCodes();
            // impplode là hàm nối các phần tử trong array thành 1 chuỗi
            $_SESSION['error'] = 'Lỗi:' . implode('<br>', $errors);
            header('location: /');
            exit;
        }
        $data["name"] = $_POST['fullname'];
		$data["password"] = password_hash($_POST['password'], PASSWORD_BCRYPT);
		$data["mobile"] = $_POST['mobile'];
		$data["email"]= $_POST['email'];
		$data["login_by"] = 'form';
		$data["shipping_name"] = $_POST['fullname'];
		$data["shipping_mobile"] = $_POST['mobile'];
		$data["ward_id"] = null;
		$data["is_active"] = 0;
		$data["housenumber_street"] = '';

        $customerRepository = new CustomerRepository();
        if ($customerRepository->save($data)) {
            $_SESSION['success'] = 'Đã đăng ký tài khoản thành công. Vui lòng check mail để kích hoạt tài khoản';
            
            // Gởi email active account
            $emailService = new EmailService();
            $to = $_POST['email'];
            $subject = APP_NAME . ' - Active Account';
            $name = $_POST['fullname'];
            $website = get_domain();
            $key = JWT_KEY;
            $payload = [
            'email' => $to,
            ];
            $token = JWT::encode($payload, $key, 'HS256');
            $linkActive = get_domain_site() . '?c=customer&a=active&token=' . $token;
            $content = "Dear $name, <br>
            Vui lòng click vào link bên dưới để active account <br>
            <a href='$linkActive'>Active Account</a>
            -------------<br>
            Được gởi từ website $website";
            $emailService->send($to, $subject, $content);
            header('location:/');
            exit;
        }
        $_SESSION['error'] = $customerRepository->getError();
        header('location: /');
        exit;
    }
    public function notExistingEmail()
    {
        $email = $_GET['email'];
        $customerRepository = new CustomerRepository();
        $customer = $customerRepository->findEmail($email);
        if (!empty($customer)) {
            // tồn tại email thì trả về trình duyệt web chữ false
            echo 'false';
            return;
        }
        // không tồn tại email
        echo 'true';
    }
    // mã hóa
    function test1() 
    {
        $key = 'con bò đang phá làng phá xóm';
        $payload = [
        'email' => 'abc@gmail.com'
        ];
    $jwt = JWT::encode($payload, $key, 'HS256'); //cơ chế mã hóa và giả mã
    echo $jwt;
    }
    // giải mã 
    function test2() 
    {
        $key = 'con bò đang phá làng phá xóm';
        $jwt = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6ImFiY0BnbWFpbC5jb20ifQ.gIMoU39HIAbqzGqnB5V-mrhbfLT26o7B3yg4KowYaVk';
        $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
        var_dump($decoded);
    }

    function active() {
        $token = $_GET['token'];
        $key = JWT_KEY;
        $decoded = JWT::decode($token, new Key($key, 'HS256'));
        $email = $decoded->email;
        $customerRepository = new CustomerRepository();
        $customer = $customerRepository->findEmail($email);
        if(empty($customer)) {
            $_SESSION['error'] = 'Email không tồn tại trong hệ thống';
            header('location: /');
            exit;
        }
        $customer->setIsActive(1);
        if(!$customerRepository->update($customer)) {
            $_SESSION['error'] = $customerRepository->getError();
            header('location: /');
            exit;
        }

        $_SESSION['success'] = 'Tài khoản đã được kích hoạt thành công';
        $_SESSION['email'] = $email;
        $_SESSION['name'] = $customer->getName();
        header('location: ?c=customer&a=show'); //điều hướng vào trang tài khoản cá nhân
        exit;
    }

}