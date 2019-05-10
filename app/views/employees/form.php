<?php
$departmentsModel = new \app\model\DepartmentsModel();
$departments = $departmentsModel->getDepartments();
$employeesModel = new \app\model\EmployeesModel();

if (!empty($_POST)) {
    require __DIR__.'/validate.php';
}
$this->header();
?>
<form method="post">
    <div class="row">
        <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <label for="name">Jméno</label>
            <input type="text" name="NAME" id="name" class="
            <?php echo (isset($classes['NAME'])) ? $classes['NAME'] : ''; ?>
            form-control" placeholder="Petr" required maxlength="50">
            <?php if(isset($errors['NAME'])) invalid_feedback($errors['NAME']); ?>
        </div>
        <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <label for="surname">Příjmení</label>
            <input type="text" name="SURNAME" id="surname" class="form-control
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
                       value="@">
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
                           value="1985-01-01"
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
                           value="<?php echo date('Y-m-d'); ?>"
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
                    echo '<option value="'.$department['ID'].'">';
                    echo $department['NAME'];
                    echo '</option>';
                }
                ?>
            </select>
            <?php if(isset($errors['departments'])) invalid_feedback($errors['departments']); ?>
        </div>
    </div>
    <button type="submit" class="btn btn-secondary">Vytvořit pracovníka</button>
</form>
<?php $this->footer();?>
