<?php $this->header();?>
    <?php
    $employeesModel = new \app\model\EmployeesModel();
    $employee = $employeesModel->findEmployee($this->ID);
    ?>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <h1>
                <?php echo $employee['NAME'].' '.$employee['SURNAME']; ?>
            </h1>
            <h3 class="text-right">
                <a class="card-link" href="#depTable" data-toggle="collapse">Oddělení</a>
            </h3>
            <table id="depTable" class="table table-striped table-hover collapse">
                <?php

                $departmentsModel = new \app\model\DepartmentsModel();
                $departments = $departmentsModel->findDepartmentsByEmployee($this->ID);
                foreach ($departments as $department) {
                    echo '<tr>';
                    echo '<td><a class="card-link" href="departments/detail/'.$department['ID'].'/">';
                    echo $department['NAME'].'</a></td>';
                    echo '</tr>';
                }
                ?>
            </table>
            <h3 class="text-right">
                <a class="card-link" href="#evTable" data-toggle="collapse">Přihlášen na školení</a>

            </h3>
            <table id="evTable" class="collapse table table-striped table-hover">
                <?php

                $eventsModel = new \app\model\EventsModel();
                $events = $eventsModel->findEventsByEmployee($this->ID);
                foreach ($events as $event) {
                    echo '<tr>';
                    echo '<td><a class="card-link" href="events/detail/'.$event['ID'].'/">';
                    echo $event['NAME'].'</a></td>';
                    echo '</tr>';
                }
                ?>
            </table>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <table class="table table-striped table-hover informace">
                <tr>
                    <td>Email:</td>
                    <td><?php echo $employee['EMAIL']; ?></td>
                </tr>
                <tr>
                    <td>Věk:</td>
                    <td><?php echo $employee['AGE']; ?></td>
                </tr>
                <?php if ($employee['ENTERED']): ?>
                <tr>
                    <td>Nastoupil:</td>
                    <td><?php echo $employee['ENTERED']; ?></td>
                </tr>
                <?php endif; ?>
                <?php if ($employee['GONE']): ?>
                    <tr>
                        <td>Odešel:</td>
                        <td><?php echo $employee['GONE']; ?></td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td>Počet absolvovaných školení:</td>
                    <td><?php echo $employee['ABSOLVED']; ?></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <?php
            $absolvedEvents = $eventsModel->findAbsolvedEvents($this->ID);
            if ($absolvedEvents):
            ?>
            <h3 class="text-left">Zůčastnil se</h3>
                <table class="table table-striped table-hover">
                    <tr>
                        <th>Název</th>
                        <th>Začátek</th>
                        <th>Vyplaceno</th>
                    </tr>
                    <?php

                    foreach ($absolvedEvents as $event) {
                        echo '<tr>';
                        echo '<td><a class="card-link" href="events/detail/'.$event['ID'].'/">';
                        echo $event['NAME'].'</a></td>';
                        echo '<td>'.$eventsModel->dtToString($event['BEGINNING']).'</td>';
                        if ($event['POSTED']) {
                            echo '<td><i class="fas fa-check"></i></td>';
                        } else {
                            echo '<td><i class="fas fa-times"></i></td>';
                        }

                        echo '</tr>';
                    }

                    ?>
                </table>
            <?php endif;?>
        </div>
    </div>
<?php $this->footer();?>
