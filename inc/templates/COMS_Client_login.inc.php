<div class="modal fade" id="login_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <?php if (isset($error)) { ?>
                <div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
            <?php } ?>
            <h2>Login <?php echo $USER_TYPE; ?></h2>
            <form method="post" action="" id="login" class="needs-validation">
                <div class="form-group row">
                    <label for="booking-pw" class="col-lg-4 col-sm-6">Password</label>
                    <input type="password" name="booking-pw" class="form-control col-lg-8" required />
                </div>
                <div class="form-group row">
                    <input type="hidden" name="code" value="<?php echo $code; ?>" />
                    <input type="hidden" name="captcha-image" value="<?php echo $captchaImage; ?>" />
                    <label for="result" class="col-lg-4">Captcha</label>
                    <img src="<?php echo '/' . $captchaImage; ?>" class="captcha-image col-lg-4 col-4" />
                    <input type="text" name="result" class="form-control col-lg-4 col-8" required />
                </div>
                <input type="submit" class="login-submit" value="Login" name="login" />
            </form>
        </div>
    </div>
</div>
<script type="text/javascript" src="/js/COMS_Client_login.js"></script>