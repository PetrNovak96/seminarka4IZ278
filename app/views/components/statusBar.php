<?php if (isset($this->info)):?>
<div class="alert alert-<?php echo isset($this->error)? 'danger' : 'success'; ?>"
     role="alert">
    <?php echo $this->info;?>
</div>
<?php endif;?>