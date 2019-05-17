</div>
</div>
<?php if ($this->jumbotron): ?>
<div class="jumbotron">
    <div class="container">
        <small>Přihlášen <?php echo \app\CurrentUser::getInstance()->email; ?></small>
    </div>
</div>
<?php endif;?>
</body>
</html>