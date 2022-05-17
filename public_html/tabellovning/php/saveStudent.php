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
if(!isset($_POST['klass'])&!isset($_POST['namn'])) {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "Parametrarna 'klass' och 'namn' saknas"];
    skickaSvar($error, 400);
}

if(!isset($_POST['klass'])) {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "Parametern 'klass' saknas"];
    skickaSvar($error, 400);
}

if(!isset($_POST['namn'])) {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "Parametern 'namn' saknas"];
    skickaSvar($error, 400);
}

$namn = filter_input(INPUT_POST , 'namn' ,FILTER_UNSAFE_RAW);
$namn = strip_tags($namn);

$klass = filter_input(INPUT_POST, 'klass', FILTER_UNSAFE_RAW);
$klass = strip_tags($klass);

if($namn==="") {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "'namn' får inte vara tomt"];
    skickaSvar($error, 400);
}

if($klass==="") {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "'klass' får inte vara tomt"];
    skickaSvar($error, 400);
}

$klassFormat = "/[1-9][A-Ö]/";
if(!preg_match($klassFormat, $klass)) {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'klass' får endast bestå av en siffra följd av en stor bokstav, ex:1B"];
    skickaSvar($error, 400);
}

if(!ctype_alpha($namn)) {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'namn' får endast bestå av bokstäver"];
    skickaSvar($error, 400);
}

$sql = "SELECT * from elever WHERE namn=:namn";
$stmt = $db -> prepare($sql);
$stmt -> execute(['namn'=>$namn]);
if($stmt->fetch()) {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "eleven '$namn' finns redan"];
    skickaSvar($error, 400);
}

$sql = "INSERT INTO elever (klass, namn) VALUES (:klass, :namn)";
$stmt = $db -> prepare($sql);
$stmt -> execute(['klass'=>$klass, 'namn'=>$namn]);
$antaPoster = $stmt -> rowCount();
if($antaPoster===0) {
    $error = new stdClass();
    $error -> error = ["Fel vid spara", "Inga poster sparades", $stmt->errorInfo()];
    skickaSvar($error, 400);
} else {
    $svar = new stdClass();
    $svar -> message = ["Spara lyckades"];
    skickaSvar($svar, 200);
}