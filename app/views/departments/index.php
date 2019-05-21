<?php
$this->header();
$this->render('components/statusBar');
$this->page = (isset($this->page)) ? $this->page : 0;
?>
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
                $departments = $departmentsModel->tableQuery($this->page);
                foreach ($departments as $department) {
                    echo '<tr>';
                    echo '<td><a class="card-link" href="departments/detail/'.$department['ID'].'/">';
                    echo htmlspecialchars($department['NAME']).'</a></td>';
                    echo '<td>'.$department['DIRECT_NUMBER'].'</td>';
                    echo '<td>'.$department['ALL_NUMBER'].'</td>';
                    echo '</tr>';
                }
                ?>
            </table>
        </div>
    </div>
<?php
$this->link = 'departments';
$this->render('components/pagination');
$this->footer();?>