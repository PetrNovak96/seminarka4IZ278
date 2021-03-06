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
                    <th>Počet účastníků</th>
                    <th>Stav</th>
                </tr>
                <?php

                $eventsModel = new \app\model\EventsModel();
                $events = $eventsModel->tableQuery($this->page);
                foreach ($events as $event) {
                    echo '<tr>';
                    echo '<td><a class="card-link" href="events/detail/'.$event['ID'].'/">';
                    echo htmlspecialchars($event['NAME']).'</a></td>';
                    echo '<td>'.$event['NUMBER'].'</td>';
                    echo '<td>'.$eventsModel->evTStr(time() -  $event['TIME']).'</td>';
                    echo '</tr>';
                }
                ?>
            </table>
        </div>
    </div>
<?php
$this->link = 'events';
$this->render('components/pagination');
$this->footer();?>