<div class="row">
    <div class="btn-group
        <?php if ($this->link != 'events'): ?>
        offset-lg-10 col-lg-2 mb-lg-3 offset-md-10 col-md-2 mb-md-3 offset-sm-6 col-sm-6 mb-sm-3 offset-xs-4 col-xs-8 mb-xs-3
        <?php else: ?>
        offset-lg-9 col-lg-3 mb-lg-3 offset-md-9 col-md-3 mb-md-3 offset-sm-4 col-sm-8 mb-sm-3 col-xs-12 mb-xs-3
        <?php endif;?>
        " role="group">
        <button onclick="window.location.href='<?php echo '/~novp19/'.$this->link.'/edit/'.$this->ID;?>';"
           class="btn btn-primary">Upravit</button>
        <button role="button"
           type="button" data-toggle="modal" data-target="#deleteModal" class="btn btn-danger">
            <?php echo ($this->link == 'employees')? 'Výpověď' : 'Odstranit'; ?></button>
        <?php if (isset($this->ended) && !$this->ended): ?>
            <button onclick="window.location.href='<?php echo '/~novp19/'.$this->link.'/apply/'.$this->ID;?>';"
                    class="btn btn-success">Přihlášky</button>
        <?php endif;?>

        <div class="modal fade" id="deleteModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <p>Přejete si  <?php
                            echo ($this->link == 'employees')? 'vypovědět ' : 'odstranit ';
                            echo $this->type;
                            ?>?</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" data-dismiss="modal">Ne</button>
                        <button onclick="on_delete('<?php echo $this->link."', ".$this->ID; ?>);"
                                class="btn btn-danger" data-dismiss="modal">Ano</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
/**/