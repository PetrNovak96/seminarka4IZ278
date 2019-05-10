<?php
$eventsModel = new \app\model\EventsModel();
$departmentsModel = new \app\model\DepartmentsModel();
$departments = $departmentsModel->getDepartments();
$this->header();?>
<form method="post">
    <div class="row">
        <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <label for="name">Název</label>
            <input type="text" name="NAME" id="name" class="form-control">
        </div>
        <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <label for="place">Místo konání</label>
            <input type="text" name="PLACE" id="place" class="form-control">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-12">
            <label for="description">Popis</label>
            <textarea name="DESCRIPTION" rows="5" id="description" class="form-control"></textarea>
        </div>
    </div>
    <div class="row">
        <label class="col-lg-1 col-md-1 col-sm-2 col-xs-2 text-right" for="beginning_date">
            Od
        </label>
        <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-6">
            <div class="input-group">
                <input type="date"
                       name="BE_DATE"
                       value="<?php echo date('Y-m-d'); ?>"
                       id="beginning_date"
                       class="form-control"
                       aria-describedby="bd_prepend">
                <div class="input-group-append">
                    <span class="input-group-text" id="bd_prepend">
                        <i class="fa fa-calendar"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group col-lg-2 col-md-2 col-sm-4 col-xs-4">
            <div class="input-group">
                <input type="time"
                       name="BE_TIME"
                       id="beginning_time"
                       class="form-control"
                       aria-describedby="bt_prepend">
                <div class="input-group-append">
                    <span class="input-group-text" id="bt_prepend">
                        <i class="fas fa-clock"></i>
                    </span>
                </div>
            </div>
        </div>

        <label class="col-lg-1 col-md-1 col-sm-2 col-xs-2 text-right" for="ending_date">
            Do
        </label>
        <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-6">
            <div class="input-group">
                <input type="date"
                       name="EN_DATE"
                       value="<?php echo date('Y-m-d'); ?>"
                       id="ending_date"
                       class="form-control"
                       aria-describedby="ed_prepend">
                <div class="input-group-append">
                    <span class="input-group-text" id="ed_prepend">
                        <i class="fa fa-calendar"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group col-lg-2 col-md-2 col-sm-4 col-xs-4">
            <div class="input-group">
                <input type="time"
                       name="EN_TIME"
                       id="ending_time"
                       class="form-control"
                       aria-describedby="et_prepend">
                <div class="input-group-append">
                    <span class="input-group-text" id="et_prepend">
                        <i class="fas fa-clock"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="ol-lg-4 col-md-4 col-sm-8 col-xs-8">
            <div class="form-group">
                <label for="departments">Organizuje</label>
                <select name="departments"
                        class="form-control"

                        id="departments">
                    <?php
                    foreach ($departments as $department) {
                        echo '<option value="'.$department['ID'].'">';
                        echo $department['NAME'];
                        echo '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-4">
            <div class="form-group">
                <label for="capacity">Kapacita</label>
                <input type="number" name="CAPACITY" id="capacity" class="form-control">
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-secondary">Vytvořit akci</button>
</form>
<?php $this->footer();?>
