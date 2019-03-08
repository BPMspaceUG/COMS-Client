<div class="modal fade" id="liam2_change_password_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <?php if (isset($error)) : ?>
                <div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if (isset($success)) : ?>
                <div class="alert alert-success" role="alert"><?php echo $success; ?></div>
            <?php endif; ?>
            <h2>Change password</h2>
            <form method="post" action="" class="needs-validation">
                <div class="form-group row">
                    <label for="liam2_User_password_old" class="col-lg-4 col-sm-6">Old password *</label>
                    <input type="password" name="liam2_User_password_old" class="form-control col-lg-8" required />
                </div>
                <div class="form-group row">
                    <label for="liam2_User_password_new" class="col-lg-4 col-sm-6">New password *</label>
                    <input type="password" name="liam2_User_password_new" class="form-control col-lg-8" minlength="10" required />
                </div>
                <div class="form-group row">
                    <label for="liam2_User_password_new_confirm" class="col-lg-4 col-sm-6">Confirm new password *</label>
                    <input type="password" name="liam2_User_password_new_confirm" class="form-control col-lg-8" minlength="10" required />
                </div>
                <a class="form-submit btn btn-primary" href="">Go Back</a>
                <input type="submit" class="form-submit btn btn-primary" value="Change Password" name="liam2_change_password" />
            </form>
        </div>
    </div>
</div>