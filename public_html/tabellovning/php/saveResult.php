<?php
declare (strict_types=1);

require_once 'funktioner.php';

if($_SERVER['REQUEST_METHOD']!=="POST") {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "Sidan ska anropas med POST"];
    skickaSvar($error, 405);
}

if(!isset($_POST['elevID'])) {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "Parametern 'elevID' saknas"];
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

$unwanted = "+\-";

$elevID = filter_input(INPUT_POST , 'elevID' ,FILTER_SANITIZE_NUMBER_INT);
$elevID = trim($elevID, $unwanted);

$tabell = filter_input(INPUT_POST , 'tabell' ,FILTER_SANITIZE_NUMBER_INT);
$tabell = trim($tabell, $unwanted);

$poang = filter_input(INPUT_POST , 'poang' ,FILTER_SANITIZE_NUMBER_INT);
$poang = trim($poang, $unwanted);

if($elevID==="") {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'elevID' får endast bestå av siffror och inte vara tom"];
    skickaSvar($error, 400);
}

if($tabell==="") {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'tabell' får endast bestå av ett heltal från 1-13 och inte vara tom"];
    skickaSvar($error, 400);
}

if($poang==="") {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'poang' får endast bestå av siffror och inte vara tom"];
    skickaSvar($error, 400);
}

if(($tabell<1|$tabell>13)) {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'tabell' måste vara ett heltal från 1 till 13 eller 'blandat'"];
    skickaSvar($error, 400);
}

if($tabell==13) {
    $tabell = "blandade";
} else {
    $tabell = $tabell . "x";
}

if($poang<0|$poang>10) {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'poang' måste vara ett heltal från 0 till 10"];
    skickaSvar($error, 400);
}

$db = kopplaDatabas();

$sql = "UPDATE resultat 
        SET $tabell=:poang 
        WHERE elevID=:elevID";
$stmt = $db -> prepare($sql);
$stmt -> execute(['elevID'=>$elevID, 'poang'=>$poang]);
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