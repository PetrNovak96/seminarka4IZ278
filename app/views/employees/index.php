<?php $this->header();?>
    <div class="row">
        <div class="col-12">
            <table class="table table-striped table-hover">
                <tr>
                    <th>Jméno</th>
                    <th>Věk</th>
                    <th>Počet školení</th>
                </tr>
                <?php

                $employeesModel = new \app\model\EmployeesModel();
                $employees = $employeesModel->tableQuery();
                foreach ($employees as $employee) {
                    echo '<tr>';
                    echo '<td><a class="card-link" href="employees/detail/'.$employee['ID'].'/">';
                    echo $employee['NAME'].' '.$employee['SURNAME'];
                    echo '</a></td>';
                    echo '<td>'.$employee['AGE'].'</td>';
                    echo '<td>'.$employee['NUMBER_OF_EVENTS'].'</td>';
                    echo '</tr>';
                }
                ?>
            </table>
        </div>
    </div>
<?php $this->footer();?>