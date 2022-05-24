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
if(!isset($_POST['klass'])) {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "Parametern 'klass' saknas"];
    skickaSvar($error, 400);
}

if(!isset($_POST['lararID'])) {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "Parametern 'lararID' saknas"];
    skickaSvar($error, 400);
}

$klass = filter_input(INPUT_POST, 'klass' ,FILTER_UNSAFE_RAW);
$klass = strip_tags($klass);
if($klass==="") {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "'klass' får inte vara tom"];
    skickaSvar($error, 400);
}

$lararID = filter_input(INPUT_POST, 'lararID', FILTER_SANITIZE_NUMBER_INT);
$unwanted = "+\-";
$lararID = trim($lararID, $unwanted);
if($lararID==="") {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "'lararID' får inte vara tom"];
    skickaSvar($error, 400);
}

$klassFormat = "/[1-9][A-Ö]/";
if(!preg_match($klassFormat, $klass)) {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'klass' får endast bestå av en siffra följd av en stor bokstav, ex:1B"];
    skickaSvar($error, 400);
}

$sql = "SELECT * from klasser WHERE klass=:klass";
$stmt = $db -> prepare($sql);
$stmt -> execute(['klass'=>$klass]);
if($stmt->fetch()) {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "Klassen '$klass' finns redan"];
    skickaSvar($error, 400);
}

$sql = "INSERT INTO klasser (klass, lararID) VALUES (:klass, :lararID)";
$stmt = $db -> prepare($sql);
$stmt -> execute(['klass'=>$klass, 'lararID'=>$lararID]);
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