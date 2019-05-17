<?php $this->header(\app\CurrentUser::getInstance()->isLoggedIn()); ?>
<div class="page-wrap d-flex flex-row align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 text-center">
                <span class="display-1 d-block">404</span>
                <div class="mb-4 lead">Takúto stránku se nám nepodařilo nalézt.</div>
                <a href="/~novp19/index" class="btn btn-link">Zpátky na dashboard</a>
            </div>
        </div>
    </div>
</div>
<?php $this->footer(\app\CurrentUser::getInstance()->isLoggedIn()); ?>
