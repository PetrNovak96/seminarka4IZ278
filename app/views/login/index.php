<?php

use app\CurrentUser;

$googleAPI = new \app\GoogleAPI();
$googleUrl = $googleAPI->getAuthUrl();
$formData = [];

if (isset($_POST['email']) && isset($_POST['password'])) {
    $formData = [
        'email' => $_POST['email'],
        'password' => $_POST['password'],
    ];

    $usersModel = new \app\model\UsersModel();
    $user = $usersModel->findUser($_POST['email']);

    require __DIR__.'/validate.php';

    if (empty($errors) ) {
        if (!empty($user) &&
            $usersModel->isValidPassword($_POST['password'], $user['ID'])) {
            CurrentUser::login($user['EMAIL'], $user['ID']);
        } else {
            $this->error = true;
            $this->info = 'Neplatná kombinace emailu a hesla...';
        }
    }
}

$this->header(false);
?>
    <div class="row mt-3">
        <div class="offset-lg-4 offset-md-4 offset-sm-1 offset-xs-1"></div>
        <div class="col-lg-4 col-md-4 col-sm-11 col-xs-11">
            <?php $this->render('components/statusBar'); ?>
        </div>
        <div class="offset-lg-4 offset-md-4 offset-sm-1 offset-xs-1"></div>
    </div>
    <div class="row mt-3">
        <div class="offset-lg-4 offset-md-4 offset-sm-1 offset-xs-1"></div>
        <div class="col-lg-4 col-md-4 col-sm-11 col-xs-11">
            <form method="post" action="/~novp19/login/">
                <h1 class="h3 mb-3 font-weight-normal">Přihlášení</h1>
                <div class="form-group">
                    <label for="email">Emailová adresa</label>
                    <input type="email" name="email" id="email" class="form-control
                            <?php echo (isset($classes['email'])) ? $classes['email'] : ''; ?>
                         " placeholder="@"
                           value="<?php echo isset($formData['email'])? htmlspecialchars($formData['email']) : '@'; ?>"
                           required autofocus >
                    <?php if(isset($errors['email'])) invalid_feedback($errors['email']); ?>
                </div>
                <div class="form-group">
                    <label for="password">Heslo</label>
                    <input type="password" name="password"
                           value="<?php echo isset($formData['password'])? htmlspecialchars($formData['password']) : ''; ?>"
                           id="password" class="form-control
                           <?php echo (isset($classes['password'])) ? $classes['password'] : ''; ?>
                            " required>
                    <?php if(isset($errors['password'])) invalid_feedback($errors['password']); ?>
                </div>
                <button class="btn btn-lg btn-secondary btn-block" type="submit">Přihlásit</button>
            </form>
            <button class="btn btn-lg btn-primary btn-block" onclick="signUp();" role="button">Registrace</button>
            <button class="btn btn-lg btn-light btn-block" onclick="forgottenPassword();" role="button">Zapomenuté heslo</button>
            <button class="btn btn-lg btn-danger btn-block" onclick="google('<?php echo $googleUrl; ?>');" role="button">Přihlásit se přes Gmail</button>
        </div>
        <div class="offset-lg-4 offset-md-4 offset-sm-1 offset-xs-1"></div>
    </div>
<?php $this->footer(false);?>