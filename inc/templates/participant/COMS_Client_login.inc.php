<div class="modal fade" id="participant_login_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <?php if (isset($error)) { ?>
                <div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
            <?php } ?>
            <div class="row modal-header">
                <div class="col-lg-2">
                    <a href="/"><img class="img-fluid" style="margin: 0 auto;" src="https://via.placeholder.com/180x180.png?text=LOGO" alt=""></a>
                </div>
                <div class="col-lg-8">
                    <div class="headline">
                        <h2 class="modal-title" id="iso27001Lable">Participant form</h2>
                    </div>
                </div>
                <div class="col-lg-2 text-right">
                    <a class="btn btn-link" href="/" role="button" data-toggle="tooltip" title="home">
                        <i class="far fa-times-circle fa-2x" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
            <div class="modal-body">
                <form method="post" action="" id="login" class="needs-validation">
                    <div class="form-group row">
                        <label for="booking-pw" class="col-lg-6 float-right">Password</label>
                        <input type="password" name="booking-pw" class="form-control col-lg-6" required />
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="code" value="<?php echo $code; ?>" />
                        <input type="hidden" name="captcha-image" value="<?php echo $captchaImage; ?>" />
                        <label for="result" class="col-lg-6 float-right">Captcha</label>
                        <img src="<?php echo '/' . $captchaImage; ?>" class="captcha-image col-lg-3 col-4" />
                        <input type="text" name="result" class="form-control col-lg-3 col-8" required />
                    </div>
                    <button type="button" class="btn register float-right">Register</button>
                    <button type="button" class="btn reset float-right">Forgot password</button>
                    <button class="btn btn-primary float-right" type="submit" name="login">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/js/COMS_Client_login.js"></script>