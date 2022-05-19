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
if(!isset($_POST['klassID'])) {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "Parametern 'klassID' saknas"];
    skickaSvar($error, 400);
}

if(!isset($_POST['namn'])) {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "Parametern 'namn' saknas"];
    skickaSvar($error, 400);
}

if(!isset($_POST['losenord'])) {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "Parametern 'losenord' saknas"];
    skickaSvar($error, 400);
}

$namn = filter_input(INPUT_POST , 'namn' ,FILTER_UNSAFE_RAW);
$namn = strip_tags($namn);

$klassID = filter_input(INPUT_POST, 'klassID', FILTER_UNSAFE_RAW);
$klassID = strip_tags($klassID);

$losenord = filter_input(INPUT_POST, 'losenord', FILTER_UNSAFE_RAW);
$losenord = strip_tags($losenord);

if($namn==="") {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "'namn' får inte vara tomt"];
    skickaSvar($error, 400);
}

if($klassID==="") {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "'klassID' får inte vara tomt"];
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

$sql = "INSERT INTO elever (klassID, namn, losenord) VALUES (:klassID, :namn, :losenord)";
$stmt = $db -> prepare($sql);
$stmt -> execute(['klassID'=>$klassID, 'namn'=>$namn, 'losenord'=>$losenord]);

$sql = "SELECT ID FROM elever WHERE namn=:namn";
$stmt = $db -> prepare($sql);
$stmt -> execute(['namn'=>$namn]);
$resultat = $stmt -> fetchObject();;
$elevID = $resultat -> ID;

$sql = "INSERT INTO resultat (elevID) VALUES (:elevID)";
$stmt = $db -> prepare($sql);
$stmt -> execute(['elevID'=>$elevID]);

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