<div class="modal fade" id="self_register_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <?php if (isset($error)) : ?>
                <div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if (isset($success)) : ?>
                <div class="alert alert-success" role="alert"><?php echo $success; ?></div>
            <?php else : ?>
                <?php if ($show_form) : ?>
                    <h2>Register</h2>
                    <form method="post" action="" class="needs-validation">
                        <div class="form-group row">
                            <label for="firstname" class="col-lg-4 col-sm-6">Firstname *</label>
                            <input type="text" id="firstname" name="firstname" class="form-control col-lg-8" required/>
                        </div>
                        <div class="form-group row">
                            <label for="lastname" class="col-lg-4 col-sm-6">Lastname *</label>
                            <input type="text" id="lastname" name="lastname" class="form-control col-lg-8" required/>
                        </div>
                        <div class="form-group row">
                            <label for="password" class="col-lg-4 col-sm-6">Password *</label>
                            <input type="password" name="password" class="form-control col-lg-8 password" minlength="10" required/>
                        </div>
                        <input type="submit" value="OK" class="form-submit btn btn-primary" name="register"/>
                    </form>
                <?php endif;
            endif; ?>
        </div>
    </div>
</div>