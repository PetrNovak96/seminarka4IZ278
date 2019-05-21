<?php

use app\Token;

$formData = [];

if (isset($_POST)) {
    $formData = [
        'password1' => $_POST['password1'],
        'password2' => $_POST['password2'],
    ];

    require __DIR__.'/validateChange.php';

    if (empty($errors) ) {
        $usersModel = new \app\model\UsersModel();
        $user = $usersModel->findUser($_POST['email']);
        if (!empty($user)) {
            $usersModel->setPassword($user['EMAIL'], $_POST['password1']);
            \app\CurrentUser::login($user['EMAIL'], $user['ID']);
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
                    <label for="password1">Nové heslo</label>
                    <input type="password" name="password1" id="password1" class="form-control
                    <?php echo (isset($classes['password1'])) ? $classes['password1'] : ''; ?>
                    " required
                           value="<?php echo isset($formData['password1'])? htmlspecialchars($formData['password1']) : ''; ?>"
                    >
                    <?php if(isset($errors['password1'])) invalid_feedback($errors['password1']); ?>
                </div>
                <div class="form-group">
                    <label for="password2">Nové heslo znovu</label>
                    <input type="password" name="password2" id="password2" class="form-control
                    <?php echo (isset($classes['password2'])) ? $classes['password2'] : ''; ?>
                    " required
                           value="<?php echo isset($formData['password2'])? htmlspecialchars($formData['password2']) : ''; ?>"
                    >
                    <?php if(isset($errors['password2'])) invalid_feedback($errors['password2']); ?>
                </div>
                <button class="btn btn-lg btn-secondary btn-block" type="submit">Potvrdit</button>
            </form>
        </div>
        <div class="offset-lg-4 offset-md-4 offset-sm-1 offset-xs-1"></div>
    </div>
<?php $this->footer(false);?>