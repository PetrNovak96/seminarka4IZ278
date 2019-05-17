<?php

use app\CurrentUser;

$usersModel = new \app\model\UsersModel();
$departmentsModel = new \app\model\DepartmentsModel();
$departments = $departmentsModel->getDepartments();
$formData = [];
if (isset($this->formData)) $formData = $this->formData;

if (!empty($_POST)) {
    $formData = [
        'NAME' => $_POST['NAME'],
        'BIRTH' => $_POST['BIRTH'],
        'SURNAME' => $_POST['SURNAME'],
        'ENTERED' => $_POST['ENTERED'],
        'departments' => @$_POST['departments'],
        'email' => $_POST['EMAIL'],
        'password1' => $_POST['password1'],
        'password2' => $_POST['password2'],
    ];
    require __DIR__.'/validateNew.php';

    if (empty($errors)) {
        $id = $usersModel->registerNew($_POST);
        CurrentUser::login($_POST['EMAIL'], $id);
    }
}
$this->header(false);
?>
<div class="row mt-3">
    <div class="offset-lg-4 offset-md-4 offset-sm-1 offset-xs-1"></div>
    <div class="col-lg-4 col-md-4 col-sm-11 col-xs-11">
        <form method="post" action="/~novp19/signUp/newUser/">
            <h1 class="h3 mb-3 font-weight-normal">Registrace</h1>

            <div class="form-group">
                <label for="name">Křestní jméno</label>
                <input type="text" name="NAME" id="name" class="
                <?php echo (isset($classes['NAME'])) ? $classes['NAME'] : ''; ?>
                form-control" placeholder="Petr" required maxlength="50" autofocus
                value="<?php echo isset($formData['NAME'])? htmlspecialchars($formData['NAME']) : ''; ?>"
                >
                <?php if(isset($errors['NAME'])) invalid_feedback($errors['NAME']); ?>
            </div>

            <div class="form-group">
                <label for="surname">Příjmení</label>
                <input type="text" name="SURNAME" id="surname"
                value="<?php echo isset($formData['SURNAME'])? htmlspecialchars($formData['SURNAME']) : ''; ?>"
                class="form-control
                <?php echo (isset($classes['SURNAME'])) ? $classes['SURNAME'] : ''; ?>
                " placeholder="Novák" required maxlength="50">
                <?php if(isset($errors['SURNAME'])) invalid_feedback($errors['SURNAME']); ?>
            </div>

            <div class="form-group">
                <label for="birth_date">Datum narození</label>
                <div class="input-group">
                    <input type="date"
                           name="BIRTH"
                           id="birth_date"
                           value="<?php echo isset($formData['BIRTH'])? htmlspecialchars($formData['BIRTH']) : '1985-01-01'; ?>"
                           class="form-control
                           <?php echo (isset($classes['BIRTH'])) ? $classes['BIRTH'] : ''; ?>
                           " required
                           aria-describedby="bd_prepend">
                    <div class="input-group-append">
                        <span class="input-group-text" id="bd_prepend"><i class="fa fa-calendar"></i></span>
                    </div>
                    <?php if(isset($errors['BIRTH'])) invalid_feedback($errors['BIRTH']); ?>
                </div>
            </div>

            <div class="form-group">
                <label for="entered_date">Datum nástupu</label>
                <div class="input-group">
                    <input type="date"
                           name="ENTERED"
                           id="entered_date"
                           value="<?php echo isset($formData['ENTERED'])? htmlspecialchars($formData['ENTERED']) : date('Y-m-d'); ?>"
                           class="form-control
                           <?php echo (isset($classes['ENTERED'])) ? $classes['ENTERED'] : ''; ?>
                           "
                           aria-describedby="entered_prepend">
                    <div class="input-group-append">
                        <span class="input-group-text" id="entered_prepend"><i class="fa fa-calendar"></i></span>
                    </div>
                    <?php if(isset($errors['ENTERED'])) invalid_feedback($errors['ENTERED']); ?>
                </div>
            </div>

            <div class="form-group">
                <label for="departments">Oddělení</label>
                <select multiple name="departments[]"
                        class="form-control
                    <?php echo (isset($classes['departments'])) ? $classes['departments'] : ''; ?>
                    "
                        size="<?php echo (count($departments) < 10)? count($departments) : 10; ?>"
                        id="departments">
                    <?php
                    foreach ($departments as $department) {
                        echo '<option value="'.$department['ID'].'"';
                        echo @in_array($department['ID'], $formData['departments'])?
                            'selected="selected"' : '';
                        echo '>'.htmlspecialchars($department['NAME']);
                        echo '</option>';
                    }
                    ?>
                </select>
                <?php if(isset($errors['departments'])) invalid_feedback($errors['departments']); ?>
            </div>

            <div class="form-group">
                <label for="email">Emailová adresa</label>
                <input type="email" name="EMAIL" id="email" class="form-control
                    <?php echo (isset($classes['email'])) ? $classes['email'] : ''; ?>
                    " placeholder="@" required
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
