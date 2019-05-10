<?php $this->header();?>
    <?php
    $eventsModel = new \app\model\EventsModel();
    $event = $eventsModel->findEvent($this->ID);
    ?>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <h1>
                <?php echo $event['NAME'];?>
            </h1>
            <div class="text-secondary">
                <?php echo $event['DESCRIPTION'];?>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <table class="table table-striped table-hover informace">
                <tr>
                    <td>Kapacita:</td>
                    <td><?php echo $event['CAPACITY']; ?></td>
                </tr>
                <tr>
                    <td>Počet přihlášených:</td>
                    <td><?php echo $event['COUNT']; ?></td>
                </tr>
                <tr>
                    <td>Organizuje:</td>
                    <td><?php
                        echo '<a class="card-link" href="departments/detail/'.$event['ORGANIZATOR_ID'].'">';
                        echo $event['ORGANIZATOR'];
                        echo '</a>';
                        ?>
                    </td>
                </tr>
                <?php if ($event['PLACE']): ?>
                <tr>
                    <td>Místo konání:</td>
                    <td><?php echo $event['PLACE']; ?></td>
                </tr>
                <?php endif; ?>
                <tr>
                    <td>Začátek:</td>
                    <td><?php echo $eventsModel->dtToString($event['BEGINNING']); ?></td>
                </tr>
                <tr>
                    <td>Konec:</td>
                    <td><?php echo $eventsModel->dtToString($event['ENDING']);; ?></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row">
        <?php
        $employeesModel = new \app\model\EmployeesModel();
        $employees = $employeesModel->findParticipants($this->ID);
        $ended = time() - strtotime($event['ENDING']);
        if ($employees):
        ?>
        <div class="col-12">
            <h3 class="text-left">Přihlášení</h3>
            <table class="table table-striped table-hover">
                <?php if ($ended > 0): ?>
                <tr>
                    <th></th>
                    <th>Vykázáno</th>
                </tr>
                <?php endif; ?>
                <?php
                    foreach ($employees as $employee) {
                        echo '<tr>';
                        echo '<td><a class="card-link" href="employees/detail/'.$employee['ID'].'">';
                        echo $employee['NAME'].' '.$employee['SURNAME'].'</a></td>';
                        if ($ended > 0){
                            echo '<td>';
                            if ($employee['REPORTED']) echo '<i class="fas fa-check"></i>';
                            else echo '<i class="fas fa-times"></i>';
                            echo '</td>';
                        }
                        echo '</tr>';
                    }
                ?>
            </table>
         <?php endif;?>
        </div>
    </div>
<?php $this->footer();?>