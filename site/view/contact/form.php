<?php require 'layout/header.php' ?>

<!-- CONTAINER -->
<main id="maincontent" class="page-main">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="/" target="_self">Trang chủ</a></li>
                    <li><span>/</span></li>
                    <li class="active"><span>Liên hệ</span></li>
                </ol>
            </div>
        </div>
        <div class="row contact">
            <div class="col-md-6">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15674.354406441767!2d106.77669249387198!3d10.842762051015862!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3175270e8f6c7f07%3A0x4adb16bdf1ad5dd4!2sPark%20District%209!5e0!3m2!1sen!2s!4v1740685887593!5m2!1sen!2s"
                    width="550" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <div class="col-md-6">
                <h4>Thông tin liên hệ</h4>
                <form class="form-contact" action="#" method="POST">
                    <div class="form-group">
                        <input type="text" class="form-control" name="fullname" placeholder="Họ và tên" required
                            oninvalid="this.setCustomValidity('Vui lòng nhập tên của bạn')"
                            oninput="this.setCustomValidity('')">
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <input type="email" class="form-control" name="email" placeholder="Email" required
                                oninvalid="this.setCustomValidity('Vui lòng nhập email')"
                                oninput="this.setCustomValidity('')">
                        </div>
                        <div class="form-group col-sm-6">
                            <input type="tel" class="form-control" name="mobile" placeholder="Số điện thoại" required
                                pattern="[0][0-9]{9,}"
                                oninvalid="this.setCustomValidity('Vui lòng nhập số điện thoại bắt đầu bằng số 0 và ít nhất 9 con số theo sau')"
                                oninput="this.setCustomValidity('')">
                        </div>

                        <div class="form-group col-sm-12">

                            <textarea class="form-control" placeholder="Nội dung" name="content" rows="10"
                                required></textarea>
                        </div>

                        <div class="form-group col-sm-12">
                            <!-- ..message.alert.alert-success  -->
                            <div class="message alert alert-success" style="display:none"></div>
                        </div>
                        <div class="form-group col-sm-12">
                            <button type="submit" class="btn btn-sm btn-primary pull-right">Gửi</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
</main>
<!-- END CONTAINER -->
<?php require 'layout/footer.php' ?>