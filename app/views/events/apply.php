<?php

use app\Token;

$eventsModel = new \app\model\EventsModel();
$events = $eventsModel->getEvents();
$employeesModel = new \app\model\EmployeesModel();
$employees = $employeesModel->getEmployees();



$formData = [];

if (isset($this->ID)) {
    $participants = $employeesModel->findParticipants($this->ID);

    $temp = [];
    foreach ($participants as $participant) {
        $temp[] = $participant['ID'];
    }
    $participants = $temp;
    $formData = [
        'EVENT_ID' => $this->ID,
        'participants' => $participants,
    ];
}

if(!empty($_POST)) {
    $formData = [
        'EVENT_ID' => $_POST['EVENT_ID'],
        'participants' => $_POST['participants'],
    ];
    require __DIR__.'/validateApply.php';
    if (empty($errors)) {
        $eventsModel->updateParticipations($formData);
        header('Location: /~novp19/events/updatedParticipations/'.$this->ID.'/');
    }
}
$this->header();
?>
<form method="post">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="event">Událost</label>
                <select name="EVENT_ID"
                        class="form-control
                        <?php echo (isset($classes['event'])) ? $classes['event'] : ''; ?>
                        "
                        id="event" required>
                    <?php
                    foreach ($events as $event) {
                        echo '<option value="'.$event['ID'].'"';
                        echo (isset($formData['EVENT_ID']) &&
                            $formData['EVENT_ID'] == $event['ID'])?
                            'selected="selected"' : '';
                        echo '>'.htmlspecialchars($event['NAME']);
                        echo '</option>';
                    }
                    ?>
                </select>
                <?php if(isset($errors['event'])) invalid_feedback($errors['event']); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="form-group">
                <label for="participants">Pracovníci</label>
                <select multiple name="participants[]"
                        class="form-control
                    <?php echo (isset($classes['participants'])) ? $classes['participants'] : ''; ?>
                    "
                        size="<?php echo (count($employees) < 30)? count($employees) : 30; ?>"
                        id="participants">
                    <?php
                    foreach ($employees as $employee) {
                        echo '<option value="'.$employee['ID'].'"';
                        echo @in_array($employee['ID'], $formData['participants'])?
                            'selected="selected"' : '';
                        echo '>'.htmlspecialchars($employee['NAME'].' '.$employee['SURNAME']);
                        echo '</option>';
                    }
                    ?>
                </select>
                <?php if(isset($errors['participants'])) invalid_feedback($errors['participants']); ?>
            </div>
        </div>
    </div>
    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    <button type="submit" class="btn btn-secondary">Uložit změny</button>
</form>
<?php
$this->footer();
?>

