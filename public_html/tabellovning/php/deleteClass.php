<?php
declare (strict_types=1);

require_once 'funktioner.php';

if($_SERVER['REQUEST_METHOD']!=="POST") {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "Sidan ska anropas med POST"];
    skickaSvar($error, 405);
}

if(!isset($_POST['ID'])) {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "'ID' saknas"];
    skickaSvar($error, 400);
}

$ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
$unwanted = "+\-";
$ID = trim($ID, $unwanted);

if($ID==="") {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'ID' får endast bestå av ett heltal och inte vara tom"];
    skickaSvar($error, 400);
}

$db = kopplaDatabas();

$sql = "SELECT * FROM elever WHERE klassID=:klassID";
$stmt = $db -> prepare($sql);
$stmt -> execute(['klassID'=>$ID]);
if($stmt->fetch()) {
    $error = new stdClass();
    $error -> error = ["Fel vid radering", "Klassen innehåller elever"];
    skickaSvar($error, 400);
}

$sql = "DELETE FROM klasser WHERE ID=:ID";
$stmt = $db -> prepare($sql);
$stmt -> execute(['ID'=>$ID]);
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