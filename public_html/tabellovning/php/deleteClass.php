<?php
declare (strict_types=1);

require_once 'funktioner.php';

if($_SERVER['REQUEST_METHOD']!=="POST") {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "Sidan ska anropas med POST"];
    skickaSvar($error, 405);
}

if(!isset($_POST['klass'])) {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "'klass' saknas"];
    skickaSvar($error, 400);
}

$klass = filter_input(INPUT_POST, 'klass', FILTER_UNSAFE_RAW);
$klass = strip_tags($klass);
if($klass==="") {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'klass' får inte vara tom"];
    skickaSvar($error, 400);
}

$klassFormat = "/[1-9][A-Ö]/";
if(!preg_match($klassFormat, $klass)) {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'klass' får endast bestå av en siffra följd av en stor bokstav, ex:1B"];
    skickaSvar($error, 400);
}

$db = kopplaDatabas();

$sql = "SELECT * FROM elever WHERE klass=:klass";
$stmt = $db -> prepare($sql);
$stmt -> execute(['klass'=>$klass]);
if($stmt->fetch()) {
    $error = new stdClass();
    $error -> error = ["Fel vid radering", "Angiven klass ($klass) innehåller elever"];
    skickaSvar($error, 400);
}

$sql = "DELETE FROM klasser WHERE klass=:klass";
$stmt = $db -> prepare($sql);
$stmt -> execute(['klass'=>$klass]);
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

