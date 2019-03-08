<div class="modal fade" id="self_register_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <?php if (isset($error)) : ?>
                <div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if (isset($success)) : ?>
                <div class="alert alert-success" role="alert"><?php echo $success; ?></div>
            <?php else : ?>
                <h2>Register</h2>
                <form method="post" action="" class="needs-validation">
                    <div class="form-group row">
                        <label for="email" class="col-lg-4 col-sm-6">E-Mail</label>
                        <input type="text" id="email" name="email" class="form-control col-lg-8" required />
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="code" value="<?php echo $code; ?>" />
                        <input type="hidden" name="captcha-image" value="<?php echo $captchaImage; ?>" />
                        <label for="result" class="col-lg-4">Captcha</label>
                        <img src="<?php echo $captchaImage; ?>" class="captcha-image col-lg-4 col-sm-4" />
                        <input type="text" name="result" class="form-control col-lg-4 col-sm-8" required />
                    </div>
                    <input type="submit" value="OK" class="form-submit btn btn-primary" name="self_register" />
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>