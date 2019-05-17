<?php

//if not empty post
$this->header(false);
?>
    <div class="row mt-3">
        <div class="offset-lg-3 offset-md-3 offset-sm-1 offset-xs-1"></div>
        <div class="col-lg-6 col-md-6 col-sm-11 col-xs-11 text-center">
            <h3 class="mb-3">Výběr typu registrace</h3>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <button class="btn btn-lg btn-light btn-block" onclick="signUpExisting();" role="button">Existující pracovník</button>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <button class="btn btn-lg btn-secondary btn-block" onclick="signUpNew();" role="button">Nový pracovník</button>
                </div>
            </div>
        </div>
        <div class="offset-lg-3 offset-md-3 offset-sm-1 offset-xs-1"></div>
    </div>
<?php $this->footer(false);?>