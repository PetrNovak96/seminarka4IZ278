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

function validateTime($time): bool {
    $f = 'H:i';
    $t = DateTime::createFromFormat($f, $time);
    if(!($t && $t->format($f) == $time)) {
        return false;
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

function format_date($date) {
    $date = date_create($date);
    return date_format($date, 'd.m.Y');
}

function format_datetime($datetime) {
    $date = date_create($datetime);
    return date_format($date, 'd.m.Y \v\e H:i');
}