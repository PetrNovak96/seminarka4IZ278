<?php

$formData = [];

if (isset($_POST['email'])) {
    $formData = [
        'email' => $_POST['email'],
    ];

    require __DIR__.'/validateEmail.php';

    if (empty($errors) ) {
        $this->info = 'Na Vaši emailovou adresu byly poslány pokyny pro změnu hesla.';
        $usersModel = new \app\model\UsersModel();
        $user = $usersModel->findUser($_POST['email']);
        if (!empty($user)) {
            $usersModel->sendForgottenPasswordEmail($_POST['email']);
        }
    }
}

$this->header(false);
?>
    <div class="row mt-3">
        <div class="offset-lg-4 offset-md-4 offset-sm-1 offset-xs-1"></div>
        <div class="col-lg-4 col-md-4 col-sm-11 col-xs-11">
            <form method="post">
                <div class="form-group">
                    <label for="email">Zadejte Vaši emailovou adresu</label>
                    <input type="email" name="email" id="email" class="form-control
                            <?php echo (isset($classes['email'])) ? $classes['email'] : ''; ?>
                         " placeholder="@"
                           value="<?php echo isset($formData['email'])? htmlspecialchars($formData['email']) : '@'; ?>"
                           required autofocus>
                    <?php if(isset($errors['email'])) invalid_feedback($errors['email']); ?>
                </div>
                <button class="btn btn-lg btn-secondary btn-block" type="submit">Potvrdit</button>
            </form>
        </div>
        <div class="offset-lg-4 offset-md-4 offset-sm-1 offset-xs-1"></div>
    </div>
    <div class="row mt-3">
        <div class="offset-lg-4 offset-md-4 offset-sm-1 offset-xs-1"></div>
        <div class="col-lg-4 col-md-4 col-sm-11 col-xs-11">
            <?php $this->render('components/statusBar'); ?>
        </div>
        <div class="offset-lg-4 offset-md-4 offset-sm-1 offset-xs-1"></div>
    </div>
<?php $this->footer(false);?>