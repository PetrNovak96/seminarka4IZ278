<?php $this->header();?>
    <div class="row">
        <div class="col-12">
            <table class="table table-striped table-hover">
                <tr>
                    <th>Název</th>
                    <th>Počet přímých členů</th>
                    <th>Počet členů s pododděleními</th>
                </tr>
                <?php

                $departmentsModel = new \app\model\DepartmentsModel();
                $departments = $departmentsModel->tableQuery();
                foreach ($departments as $department) {
                    echo '<tr>';
                    echo '<td><a class="card-link" href="departments/detail/'.$department['ID'].'/">';
                    echo $department['NAME'].'</a></td>';
                    echo '<td>'.$department['DIRECT_NUMBER'].'</td>';
                    echo '<td>'.$department['ALL_NUMBER'].'</td>';
                    echo '</tr>';
                }
                ?>
            </table>
        </div>
    </div>
<?php $this->footer();?>