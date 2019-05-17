<?php
$departmentsModel = new \app\model\DepartmentsModel();
$employeesModel = new \app\model\EmployeesModel();
$departments = $departmentsModel->getDepartments();
$employees = $employeesModel->getEmployees();

$formData = [];

if (isset($this->ID)) {
    $department = $departmentsModel->findDepartment($this->ID);
    $formData = [
        'NAME' => $department['NAME'],
        'department' => $department['DEPARTMENT_ID'],
        'ROOM' => $department['ROOM'],
        'BUILDING' => $department['BUILDING'],
        'HEAD_ID' => $department['HEAD_ID'],
    ];
}

if (!empty($_POST)) {
    $formData = [
            'NAME' => $_POST['NAME'],
            'department' => $_POST['department'],
            'ROOM' => $_POST['ROOM'],
            'BUILDING' => $_POST['BUILDING'],
            'HEAD_ID' => $_POST['HEAD_ID'],
    ];
    require __DIR__.'/validate.php';
    if (empty($errors)) {
        if ((isset($this->ID))) {
            $departmentsModel->updateDepartment($this->ID, $_POST);
            //header('Location: /~novp19/departments/edited/'.$this->ID.'/');
        } else {
            $departmentsModel->saveDepartment($_POST);
            //header('Location: /~novp19/departments/created/');
        }
    }
}
$this->header();?>
<form method="post">
    <div class="row">
        <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <label for="name">Název</label>
            <input type="text" name="NAME" id="name" class="form-control
            <?php echo (isset($classes['NAME'])) ? $classes['NAME'] : ''; ?>
            " required maxlength="50"
            value="<?php echo isset($formData['NAME'])? htmlspecialchars($formData['NAME']) : ''; ?>"
            >
            <?php if(isset($errors['NAME'])) invalid_feedback($errors['NAME']); ?>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="department">Nadoddělení</label>
                <select name="department"
                        class="form-control
                        <?php echo (isset($classes['department'])) ? $classes['department'] : ''; ?>
                        "
                        id="department">
                    <?php

                    echo '<option value="0"';
                    if (isset($formData['department'])) echo ' selected="selected"';
                    echo '>Žádné</option>';
                    foreach ($departments as $department) {
                        if (isset($this->ID) && $department['ID'] == $this->ID) continue;
                        echo '<option value="'.$department['ID'].'"';
                        echo (isset($formData['department']) && $formData['department'] == $department['ID'])?
                        'selected="selected"' : '';
                        echo '>'.htmlspecialchars($department['NAME']);
                        echo '</option>';
                    }
                    ?>
                </select>
                <?php if(isset($errors['department'])) invalid_feedback($errors['department']); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <label for="room">Místnost</label>
            <input type="text" name="ROOM" id="room" class="form-control
            <?php echo (isset($classes['ROOM'])) ? $classes['ROOM'] : ''; ?>
            " required maxlength="50"
            value="<?php echo isset($formData['ROOM'])? htmlspecialchars($formData['ROOM']) : ''; ?>"
            >
            <?php if(isset($errors['ROOM'])) invalid_feedback($errors['ROOM']); ?>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <label for="building">Budova</label>
            <input type="text" name="BUILDING" id="building" class="form-control
            <?php echo (isset($classes['BUILDING'])) ? $classes['BUILDING'] : ''; ?>
            " required maxlength="50"
            value="<?php echo isset($formData['BUILDING'])? htmlspecialchars($formData['BUILDING']) : ''; ?>"
            >
            <?php if(isset($errors['BUILDING'])) invalid_feedback($errors['BUILDING']); ?>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="head">Vedoucí</label>
                <select name="HEAD_ID"
                        class="form-control
                        <?php echo (isset($classes['HEAD_ID'])) ? $classes['HEAD_ID'] : ''; ?>
                        "
                        id="head">
                    <?php
                    foreach ($employees as $employee) {
                        echo '<option value="'.$employee['ID'].'"';
                        echo (isset($formData['HEAD_ID']) &&
                            $formData['HEAD_ID'] == $employee['ID'])?
                        'selected="selected"' : '';
                        echo '>'.htmlspecialchars($employee['NAME'].' '.$employee['SURNAME']);
                        echo '</option>';
                    }
                    ?>
                </select>
                <?php if(isset($errors['HEAD_ID'])) invalid_feedback($errors['HEAD_ID']); ?>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-secondary"><?php echo isset($this->ID)? 'Upravit' : 'Vytvořit' ?> oddělení</button>
</form>
<?php $this->footer();?>