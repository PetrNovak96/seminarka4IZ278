<?php
$departmentsModel = new \app\model\DepartmentsModel();
$employeesModel = new \app\model\EmployeesModel();
$departments = $departmentsModel->getDepartments();
$employees = $employeesModel->getEmployees();
$this->header();?>
<form method="post">
    <div class="row">
        <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <label for="name">Název</label>
            <input type="text" name="NAME" id="name" class="form-control">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="departments">Nadoddělení</label>
                <select name="departments"
                        class="form-control"
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
    </div>
    <div class="row">
        <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <label for="room">Místnost</label>
            <input type="text" name="ROOM" id="room" class="form-control">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="head">Vedoucí</label>
                <select name="HEAD_ID"
                        class="form-control"
                        id="head">
                    <?php
                    foreach ($employees as $employee) {
                        echo '<option value="'.$employee['ID'].'">';
                        echo $employee['NAME'].' '.$employee['SURNAME'];
                        echo '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-secondary">Vytvořit oddělení</button>
</form>
<?php $this->footer();?>