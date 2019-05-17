<?php
$departmentsModel = new \app\model\DepartmentsModel();
$departments = $departmentsModel->getDepartments();
$employeesModel = new \app\model\EmployeesModel();

$formData = [];

if (isset($this->ID)) {
    $employee = $employeesModel->findEmployee($this->ID);
    $emDepartments = $departmentsModel->findDepartmentsByEmployee($this->ID);
    $temp = [];
    foreach ($emDepartments as $emDepartment) {
        $temp[] = $emDepartment['ID'];
    }
    $emDepartments = $temp;
    $formData = [
            'NAME' => $employee['NAME'],
            'SURNAME' => $employee['SURNAME'],
            'EMAIL' => $employee['EMAIL'],
            'BIRTH' => $employee['BIRTH'],
            'ENTERED' => $employee['ENTERED'],
            'departments' => $emDepartments,
    ];
}

if (!empty($_POST)) {
    $formData = [
            'NAME' => $_POST['NAME'],
            'SURNAME' => $_POST['SURNAME'],
            'EMAIL' => $_POST['EMAIL'],
            'BIRTH' => $_POST['BIRTH'],
            'ENTERED' => $_POST['ENTERED'],
            'departments' => $_POST['departments'],
    ];
    require __DIR__.'/validate.php';
    if (empty($errors)) {
        if ((isset($this->ID))) {
            $employeesModel->updateEmloyee($this->ID, $_POST);
            header('Location: /~novp19/employees/edited/'.$this->ID.'/');
        } else {
            $employee_id = $employeesModel->saveEmployee($_POST);
            header('Location: /~novp19/employees/created/');
        }
    }
}
$this->header();
?>
<form method="post">
    <div class="row">
        <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <label for="name">Křestní jméno</label>
            <input type="text" name="NAME" id="name" class="
            <?php echo (isset($classes['NAME'])) ? $classes['NAME'] : ''; ?>
            form-control" placeholder="Petr" required maxlength="50"
            value="<?php echo isset($formData['NAME'])? htmlspecialchars($formData['NAME']) : ''; ?>"
            >
            <?php if(isset($errors['NAME'])) invalid_feedback($errors['NAME']); ?>
        </div>
        <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <label for="surname">Příjmení</label>
            <input type="text" name="SURNAME" id="surname"
            value="<?php echo isset($formData['SURNAME'])? htmlspecialchars($formData['SURNAME']) : ''; ?>"
            class="form-control
            <?php echo (isset($classes['SURNAME'])) ? $classes['SURNAME'] : ''; ?>
            " placeholder="Novák" required maxlength="50">
            <?php if(isset($errors['SURNAME'])) invalid_feedback($errors['SURNAME']); ?>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email"
                       name="EMAIL"
                       id="email"
                       class="form-control
                          <?php echo (isset($classes['EMAIL'])) ? $classes['EMAIL'] : ''; ?>
                       " required
                       value="<?php echo isset($formData['EMAIL'])? htmlspecialchars($formData['EMAIL']) : '@'; ?>"
                >
                <?php if(isset($errors['EMAIL'])) invalid_feedback($errors['EMAIL']); ?>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
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
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
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
        </div>
        <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
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
    </div>
    <button type="submit" class="btn btn-secondary"><?php echo isset($this->ID)? 'Upravit' : 'Vytvořit' ?> pracovníka</button>
</form>
<?php $this->footer();?>
