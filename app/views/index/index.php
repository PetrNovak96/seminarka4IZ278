<?php
$eventsModel = new \app\model\EventsModel();
$employeesModel = new \app\model\EmployeesModel();
$this->header();?>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" id="participationsPiechart">
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <h3>Poslední akce</h3>
        <table class="table table-striped table-hover">
            <tr>
                <th>Název</th>
                <th>Konec</th>
            </tr>
            <?php
            $events = $eventsModel->lastEvents();
            foreach ($events as $event) {
                echo '<tr>';
                echo '<td><a class="card-link" href="events/detail/'.$event['ID'].'/">';
                echo htmlspecialchars($event['NAME']);
                echo '</a></td>';
                echo '<td>'.format_datetime($event['ENDING']).'</td>';
                echo '</tr>';
            }
            ?>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <h3>TOP pracovníci</h3>
        <table class="table table-striped table-hover">
            <tr>
                <th>Pracovník</th>
                <th>Počet vykázaných školení</th>
            </tr>
            <?php
            $employees = $employeesModel->dashboardQuery();
            foreach ($employees as $employee) {
                echo '<tr>';
                echo '<td><a class="card-link" href="employees/detail/'.$employee['ID'].'/">';
                echo htmlspecialchars($employee['NAME'].' '.$employee['SURNAME']);
                echo '</a></td>';
                echo '<td>'.$employee['NUMBER_REPORTED'].'</td>';
                echo '</tr>';
            }
            ?>
        </table>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <h3>Nadcházející akce</h3>
        <table class="table table-striped table-hover">
            <tr>
                <th>Název</th>
                <th>Začátek</th>
            </tr>
            <?php
            $events = $eventsModel->nextEvents();
            foreach ($events as $event) {
                echo '<tr>';
                echo '<td><a class="card-link" href="events/detail/'.$event['ID'].'/">';
                echo htmlspecialchars($event['NAME']);
                echo '</a></td>';
                echo '<td>'.format_datetime($event['BEGINNING']).'</td>';
                echo '</tr>';
            }
            ?>
        </table>
    </div>
</div>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {

        $.ajax({
            url: '/~novp19/events/getParticipationsStatistic',
            type: 'GET',
            success: function(result) {
                let obj = JSON.parse(result);
                var data = google.visualization.arrayToDataTable([
                    ['Vykázal', 'Počet pracovníků'],
                    ['Účast',     parseInt(obj.REPORTED)],
                    ['Neúčast',    parseInt(obj.UNREPORTED)],
                ]);

                var options = {
                    title: 'Účast na školeních'
                };
                var chart = new google.visualization.PieChart(document.getElementById('participationsPiechart'));
                chart.draw(data, options);
            },
            error: function (error) {
                console.log(error);
            }
        });


    }
</script>
<?php $this->footer();?>