<?php
declare (strict_types=1);

require_once 'funktioner.php';

if($_SERVER['REQUEST_METHOD']!=="POST") {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "Sidan ska anropas med POST"];
    skickaSvar($error, 405);
}

if(!isset($_POST['namn'])) {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "'namn' saknas"];
    skickaSvar($error, 400);
}

$namn = filter_input(INPUT_POST, 'namn', FILTER_UNSAFE_RAW);
$namn = strip_tags($namn);

$db = kopplaDatabas();

$sql = "DELETE FROM elever WHERE namn=:namn";
$stmt = $db -> prepare($sql);
$stmt -> execute(['namn'=>$namn]);
$antaPoster = $stmt -> rowCount();
if($antaPoster===0) {
    $svar = new stdClass();
    $svar -> result = false;
    $svar -> message = ["Inga poster raderades"];
    skickaSvar($svar, 200);
} else {
    $svar = new stdClass();
    $svar -> result = true;
    $svar -> message = ["Radera lyckades", "$antaPoster poster raderades"];
    skickaSvar($svar, 200);
}

