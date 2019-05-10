<?php

function validateDate($date, bool $past = false): bool {
    $f = 'Y-m-d';
    $d = DateTime::createFromFormat($f, $date);
    if($past) {
        if(!($d && $d->format($f) == $date && $date <= date($f))) {
            return false;
        }
    } else {
        if(!($d && $d->format($f) == $date)) {
            return false;
        }
    }
    return true;
}

function invalid_feedback(array $msgs) {
    foreach ($msgs as $msg) {
        echo '<div class="invalid-feedback">';
        echo $msg;
        echo '</div>';
    }
}