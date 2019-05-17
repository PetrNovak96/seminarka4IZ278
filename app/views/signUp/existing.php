<?php

use app\CurrentUser;

$usersModel = new \app\model\UsersModel();
$formData = [];

if (isset($this->formData)) $formData = $this->formData;

if(!empty($_POST)) {

    $formData = [
            'email' => $_POST['email'],
            'password1' => $_POST['password1'],
            'password2' => $_POST['password2'],
    ];

    require __DIR__.'/validateExisting.php';

    if(empty($errors)) {
        $id = $usersModel->setPassword($_POST['email'], $_POST['password1']);
        CurrentUser::login($_POST['email'], $id);
    }
}

$this->header(false);
?>
    <div class="row mt-3">
        <div class="offset-lg-4 offset-md-4 offset-sm-1 offset-xs-1"></div>
        <div class="col-lg-4 col-md-4 col-sm-11 col-xs-11">
            <form method="post" action="/~novp19/signUp/existing/">
                <h1 class="h3 mb-3 font-weight-normal">Registrace</h1>
                <div class="form-group">
                    <label for="email">Emailová adresa</label>
                    <input type="email" name="email" id="email" class="form-control
                    <?php echo (isset($classes['email'])) ? $classes['email'] : ''; ?>
                    " placeholder="@" required autofocus
                    value="<?php echo isset($formData['email'])? htmlspecialchars($formData['email']) : '@'; ?>"
                    >
                    <?php if(isset($errors['email'])) invalid_feedback($errors['email']); ?>
                </div>
                <div class="form-group">
                    <label for="password1">Heslo</label>
                    <input type="password" name="password1" id="password1" class="form-control
                    <?php echo (isset($classes['password1'])) ? $classes['password1'] : ''; ?>
                    " required
                    value="<?php echo isset($formData['password1'])? htmlspecialchars($formData['password1']) : ''; ?>"
                    >
                    <?php if(isset($errors['password1'])) invalid_feedback($errors['password1']); ?>
                </div>
                <div class="form-group">
                    <label for="password2">Heslo znovu</label>
                    <input type="password" name="password2" id="password2" class="form-control
                    <?php echo (isset($classes['password2'])) ? $classes['password2'] : ''; ?>
                    " required
                    value="<?php echo isset($formData['password2'])? htmlspecialchars($formData['password2']) : ''; ?>"
                    >
                    <?php if(isset($errors['password2'])) invalid_feedback($errors['password2']); ?>
                </div>
                <button class="btn btn-lg btn-secondary btn-block" type="submit">Registrovat</button>
            </form>
        </div>
        <div class="offset-lg-4 offset-md-4 offset-sm-1 offset-xs-1"></div>
    </div>
<?php $this->footer(false);?>