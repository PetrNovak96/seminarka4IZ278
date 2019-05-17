<?php $this->header();?>
<?php
$departmentsModel = new \app\model\DepartmentsModel();
$department = $departmentsModel->findDepartment($this->ID);
$employeesModel = new \app\model\EmployeesModel();
$members = $employeesModel->getMembers($this->ID);
$subdepartments = $departmentsModel->getSubdepartments($this->ID);
$this->render('components/statusBar');
$this->link = 'departments';
$this->type = 'oddělení';
$this->render('components/detailBtnGroup');
?>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <h1>
                <?php echo htmlspecialchars($department['NAME']);?>
            </h1>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <table class="table table-striped table-hover informace">
                <tr>
                    <td>Vedoucí:</td>
                    <td><?php
                        echo '<a class="card-link" href="employees/detail/'.$department['HEAD_ID'].'">';
                        echo htmlspecialchars($department['HEAD_NAME'].' '.$department['SURNAME']);
                        echo '</a>';
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Místnost:</td>
                    <td><?php echo htmlspecialchars($department['ROOM']); ?></td>
                </tr>
                <tr>
                    <td>Budova:</td>
                    <td><?php echo htmlspecialchars($department['BUILDING']); ?></td>
                </tr>
                <tr>
                    <td>Počet členů oddělení:</td>
                    <td><?php echo $department['COUNT']; ?></td>
                </tr>
                <tr>
                    <td>Počet pododdělení:</td>
                    <td><?php echo $departmentsModel->countSubdepartments($this->ID); ?></td>
                </tr>
                <tr>
                    <td>Počet členů s pododděleními:</td>
                    <td><?php echo $departmentsModel->countSubMembers($this->ID); ?></td>
                </tr>
            </table>
        </div>
    </div>
    <?php
    $membersFill = $subdepartments? 'col-lg-6 col-md-6 col-sm-12 col-xs-12' : 'col-12';
    $subFill = $members? 'col-lg-6 col-md-6 col-sm-12 col-xs-12' : 'col-12';
    ?>
    <div class="row">
        <?php if ($members): ?>
        <div class="<?php echo $membersFill;?>">
            <h3>Členové</h3>
            <table class="table table-striped table-hover">
            <tr>
                <th></th>
                <th>Přiřazen</th>
            </tr>
            <?php
            foreach ($members as $member) {
                echo '<tr>';
                echo '<td><a class="card-link" href="employees/detail/'.$member['ID'].'">';
                echo htmlspecialchars($member['NAME'].' '.$member['SURNAME']);
                echo '</a></td>';
                echo '<td>'.format_date($member['BEGINNING']).'</td>';
                echo '</tr>';
            }
            ?>
            </table>
        </div>
        <?php endif; ?>
        <?php if ($subdepartments): ?>
            <div class="<?php echo $subFill;?>">
            <h3>Pododdělení</h3>
            <table class="table table-striped table-hover">
                <tr>
                    <th></th>
                    <th>Vedoucí</th>
                </tr>
                <?php
                foreach ($subdepartments as $subdepartment) {
                    echo '<tr>';
                    echo '<td><a class="card-link" href="departments/detail/'.$subdepartment['ID'].'">';
                    echo htmlspecialchars($subdepartment['NAME']);
                    echo '</a></td>';
                    echo '<td><a class="card-link" href="employees/detail/'.$subdepartment['HEAD_ID'].'">';
                    echo htmlspecialchars($subdepartment['HEAD_NAME'].' '.$subdepartment['HEAD_SURNAME']);
                    echo '</a></td>';
                    echo '</tr>';
                }
                ?>
            </table>
        </div>
        <?php endif; ?>
    </div>
<?php $this->footer();?>
