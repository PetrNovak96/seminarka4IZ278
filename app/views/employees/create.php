<?php
$departmentsModel = new \app\model\DepartmentsModel();
$departments = $departmentsModel->getDepartments();
$employeesModel = new \app\model\EmployeesModel();
$this->header();
?>
<form method="post">
    <div class="row">
        <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <label for="name">Jméno</label>
            <input type="text" name="NAME" id="name" class="form-control" placeholder="Petr">
        </div>
        <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <label for="surname">Příjmení</label>
            <input type="text" name="SURNAME" id="surname" class="form-control" placeholder="Novák">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="email">Email</label>
                <div class="input-group">
                    <input type="email"
                           name="EMAIL"
                           id="email"
                           class="form-control"
                           value="@">
                </div>
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
                           class="form-control"
                           aria-describedby="bd_prepend">
                    <div class="input-group-append">
                        <span class="input-group-text" id="bd_prepend"><i class="fa fa-calendar"></i></span>
                    </div>
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
                           class="form-control"
                           aria-describedby="entered_prepend">
                    <div class="input-group-append">
                        <span class="input-group-text" id="entered_prepend"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <label for="departments">Oddělení</label>
            <select multiple name="departments"
                    class="form-control"
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
        </div>
    </div>
    <button type="submit" class="btn btn-secondary">Vytvořit pracovníka</button>
</form>
<?php $this->footer();?>
