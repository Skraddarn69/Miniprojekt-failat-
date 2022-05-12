<?php
declare (strict_types=1);

require_once 'funktioner.php';
$db = kopplaDatabas();

if($_SERVER['REQUEST_METHOD']!=="POST") {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "Sidan ska anropas med POST"];
    skickaSvar($error, 405);
}

// här börjar spara klass
if(isset($_GET['klass'])) {
    $klass = filter_input(INPUT_POST ,$klass ,FILTER_UNSAFE_RAW);
    $klass = strip_tags($klass);
    echo ($klass);
    if(!isset($klass)) {
        $error = new stdClass();
        $error -> error = ["Felaktig indata", "'activity' saknas"];
        skickaSvar($error, 400);
    }
}