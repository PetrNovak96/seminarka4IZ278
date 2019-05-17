<?php
$eventsModel = new \app\model\EventsModel();
$departmentsModel = new \app\model\DepartmentsModel();
$departments = $departmentsModel->getDepartments();

$formData = [];

if (isset($this->ID)) {
    $event = $eventsModel->findEvent($this->ID);
    $beginning = $event['BEGINNING'];
    $ending = $event['ENDING'];
    $be_date = preg_replace('/(.*)\s+(.*)/', '$1', $beginning);
    $be_time = preg_replace('/(.*)\s+(\d+:\d+):(.*)/', '$2', $beginning);
    $en_date = preg_replace('/(.*)\s+(.*)/', '$1', $ending);
    $en_time = preg_replace('/(.*)\s+(\d+:\d+):(.*)/', '$2', $ending);
    $formData = [
            'NAME' => $event['NAME'],
            'PLACE' => $event['PLACE'],
            'DESCRIPTION' => $event['DESCRIPTION'],
            'BE_DATE' => $be_date,
            'BE_TIME' => $be_time,
            'EN_DATE' => $en_date,
            'EN_TIME' => $en_time,
            'department' => $event['ORGANIZATOR_ID'],
            'CAPACITY' => $event['CAPACITY'],
    ];
}

if (!empty($_POST)) {
    $formData = [
        'NAME' => $_POST['NAME'],
        'PLACE' => $_POST['PLACE'],
        'DESCRIPTION' => $_POST['DESCRIPTION'],
        'BE_DATE' => $_POST['BE_DATE'],
        'BE_TIME' => $_POST['BE_TIME'],
        'EN_DATE' => $_POST['EN_DATE'],
        'EN_TIME' => $_POST['EN_TIME'],
        'department' => $_POST['department'],
        'CAPACITY' => $_POST['CAPACITY'],
    ];
    require __DIR__.'/validate.php';
    if (empty($errors)) {
        if ((isset($this->ID))) {
            $eventsModel->updateEvent($this->ID, $_POST);
            header('Location: /~novp19/events/edited/'.$this->ID.'/');
        } else {
            $eventsModel->saveEvent($_POST);
            header('Location: /~novp19/events/created/');
        }

    }
}
$this->header();
?>
<form method="post">
    <div class="row">
        <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <label for="name">Název</label>
            <input type="text" name="NAME" id="name" class="form-control
            <?php echo (isset($classes['NAME'])) ? $classes['NAME'] : ''; ?>
            " required maxlength="50"
            value="<?php echo isset($formData['NAME'])? htmlspecialchars($formData['NAME']) : ''; ?>"
            >
            <?php if(isset($errors['NAME'])) invalid_feedback($errors['NAME']); ?>
        </div>
        <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <label for="place">Místo konání</label>
            <input type="text" name="PLACE" id="place" class="form-control
            <?php echo (isset($classes['PLACE'])) ? $classes['PLACE'] : ''; ?>
            " maxlength="100"
            value="<?php echo isset($formData['PLACE'])? htmlspecialchars($formData['PLACE']) : ''; ?>">
            <?php if(isset($errors['PLACE'])) invalid_feedback($errors['PLACE']); ?>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-12">
            <label for="description">Popis</label>
            <textarea name="DESCRIPTION" rows="5" id="description" class="form-control
            <?php echo (isset($classes['DESCRIPTION'])) ? $classes['DESCRIPTION'] : ''; ?>
            " required maxlength="300"><?php echo isset($formData['DESCRIPTION'])?
                    htmlspecialchars($formData['DESCRIPTION']) : ''; ?></textarea>
            <?php if(isset($errors['DESCRIPTION'])) invalid_feedback($errors['DESCRIPTION']); ?>
        </div>
    </div>
    <div class="row">
        <label class="col-lg-1 col-md-1 col-sm-2 col-xs-2 text-right" for="beginning_date">
            Od
        </label>
        <div class="form-group col-lg-5 col-md-5 col-sm-10 col-xs-10">
            <div class="input-group">
                <input type="date"
                       name="BE_DATE"
                       value="<?php echo isset($formData['BE_DATE'])? htmlspecialchars($formData['BE_DATE']) : date('Y-m-d'); ?>"
                       id="beginning_date"
                       class="form-control
                       <?php echo (isset($classes['BE_DATE'])) ? $classes['BE_DATE'] : ''; ?>
                       "
                       aria-describedby="bd_prepend">
                <div class="input-group-append">
                    <span class="input-group-text" id="bd_prepend">
                        <i class="fa fa-calendar"></i>
                    </span>
                </div>
                <input type="time"
                       name="BE_TIME"
                       id="beginning_time"
                       <?php echo isset($formData['BE_TIME'])? 'value="'.htmlspecialchars($formData['BE_TIME']).'"' : ''; ?>"
                       class="form-control
                       <?php echo (isset($classes['BE_TIME'])) ? $classes['BE_TIME'] : ''; ?>
                       " aria-describedby="bt_prepend">
                <div class="input-group-append">
                    <span class="input-group-text" id="bt_prepend">
                        <i class="fas fa-clock"></i>
                    </span>
                </div>
                <?php if(isset($errors['BE'])) invalid_feedback($errors['BE']); ?>
            </div>
        </div>

        <label class="col-lg-1 col-md-1 col-sm-2 col-xs-2 text-right" for="ending_date">
            Do
        </label>
        <div class="form-group col-lg-5 col-md-5 col-sm-10 col-xs-10">
            <div class="input-group">
                <input type="date"
                       name="EN_DATE"
                       value="<?php echo isset($formData['EN_DATE'])? htmlspecialchars($formData['EN_DATE']) : date('Y-m-d'); ?>"
                       id="ending_date"
                       class="form-control
                       <?php echo (isset($classes['EN_DATE'])) ? $classes['EN_DATE'] : ''; ?>
                       "
                       aria-describedby="ed_prepend">
                <div class="input-group-append">
                    <span class="input-group-text" id="ed_prepend">
                        <i class="fa fa-calendar"></i>
                    </span>
                </div>
                <input type="time"
                       name="EN_TIME"
                       <?php echo isset($formData['EN_TIME'])? 'value="'.htmlspecialchars($formData['EN_TIME']).'"' : ''; ?>"
                       id="ending_time"
                       class="form-control
                       <?php echo (isset($classes['EN_TIME'])) ? $classes['EN_TIME'] : ''; ?>
                       " aria-describedby="et_prepend">
                <div class="input-group-append">
                    <span class="input-group-text" id="et_prepend">
                        <i class="fas fa-clock"></i>
                    </span>
                </div>
                <?php if(isset($errors['EN'])) invalid_feedback($errors['EN']); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="ol-lg-4 col-md-4 col-sm-8 col-xs-8">
            <div class="form-group">
                <label for="department">Organizuje</label>
                <select name="department"
                        class="form-control
                        <?php echo (isset($classes['department'])) ? $classes['department'] : ''; ?>
                        "
                        id="department" required>
                    <?php
                    foreach ($departments as $department) {
                        echo '<option value="'.$department['ID'].'"';
                        echo (isset($formData['department']) &&
                            $formData['department'] == $department['ID'])?
                            'selected="selected"' : '';
                        echo '>'.htmlspecialchars($department['NAME']);
                        echo '</option>';
                    }
                    ?>
                </select>
                <?php if(isset($errors['department'])) invalid_feedback($errors['department']); ?>
            </div>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-4">
            <div class="form-group">
                <label for="capacity">Kapacita</label>
                <input type="number"
                       name="CAPACITY"
                       id="capacity"
                       value="<?php echo isset($formData['CAPACITY'])? htmlspecialchars($formData['CAPACITY']) : ''; ?>"
                       class="form-control
                       <?php echo (isset($classes['CAPACITY'])) ? $classes['CAPACITY'] : ''; ?>
                       " required>
                <?php if(isset($errors['CAPACITY'])) invalid_feedback($errors['CAPACITY']); ?>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-secondary"><?php echo isset($this->ID)? 'Upravit' : 'Vytvořit' ?> akci</button>
</form>
<?php $this->footer();?>
