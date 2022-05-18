<?php
declare (strict_types=1);

require_once 'funktioner.php';

if($_SERVER['REQUEST_METHOD']!=="POST") {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "Sidan ska anropas med POST"];
    skickaSvar($error, 405);
}

if(!isset($_POST['elev'])) {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "Parametern 'elev' saknas"];
    skickaSvar($error, 400);
}

if(!isset($_POST['tabell'])) {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "Parametern 'tabell' saknas"];
    skickaSvar($error, 400);
}

if(!isset($_POST['poang'])) {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "Parametern 'poang' saknas"];
    skickaSvar($error, 400);
}

$elev = filter_input(INPUT_POST , 'elev' ,FILTER_UNSAFE_RAW);
$elev = strip_tags($elev);

$tabell = filter_input(INPUT_POST , 'tabell' ,FILTER_SANITIZE_NUMBER_INT);

$poang = filter_input(INPUT_POST , 'poang' ,FILTER_SANITIZE_NUMBER_INT);

if($elev==="") {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'elev' får inte vara tom"];
    skickaSvar($error, 400);
}

if($tabell==="") {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'tabell' får inte vara tom"];
    skickaSvar($error, 400);
}

if($poang==="") {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'poang' får inte vara tom"];
    skickaSvar($error, 400);
}

if(($tabell<1|$tabell>12)&$tabell!=="blandat") {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'tabell' måste vara ett heltal från 1 till 12 eller 'blandat'"];
    skickaSvar($error, 400);
}

if($tabell!=="blandat") {
    $tabell = $tabell . "ansTabell";
}

if($poang<0|$poang>10) {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'poang' måste vara ett heltal från 0 till 10"];
    skickaSvar($error, 400);
}

$db = kopplaDatabas();

$sql = "UPDATE resultat 
        SET $tabell=:poang 
        WHERE elev=:elev";
$stmt = $db -> prepare($sql);
$stmt -> execute(['elev'=>$elev, 'poang'=>$poang]);
$antaPoster = $stmt -> rowCount();
if($antaPoster===0) {
    $error = new stdClass();
    $error -> error = ["Fel vid uppdatera", "Inga poster uppdaterades", $stmt->errorInfo()];
    skickaSvar($error, 400);
} else {
    $svar = new stdClass();
    $svar -> message = ["Uppdatera lyckades"];
    skickaSvar($svar, 200);
}