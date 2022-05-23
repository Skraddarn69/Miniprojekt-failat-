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
    $error -> error = ["Felaktig indata", "'ID' saknas"];
    skickaSvar($error, 400);
}

if(!isset($_POST['nyttNamn'])) {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'nyttNamn' saknas"];
    skickaSvar($error, 400);
}

if(!isset($_POST['nyttLosenord'])) {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'nyttNamn' saknas"];
    skickaSvar($error, 400);
}

$ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
$unwanted = "+\-";
$ID = trim($ID, $unwanted);

$nyttNamn = filter_input(INPUT_POST, 'nyttNamn', FILTER_UNSAFE_RAW);
$nyttNamn = strip_tags($nyttNamn);

$nyttLosenord = filter_input(INPUT_POST, 'nyttLosenord', FILTER_UNSAFE_RAW);
$nyttLosenord = strip_tags($nyttLosenord);

if($ID==="") {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'ID' får endast bestå av ett heltal och inte vara tom"];
    skickaSvar($error, 400);
}

if($nyttNamn==="") {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'nyttNamn' får inte vara tom"];
    skickaSvar($error, 400);
}

if($nyttLosenord==="") {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'nyttLosenord' får inte vara tom"];
    skickaSvar($error, 400);
}

$db = kopplaDatabas();

$sql = "UPDATE elever SET namn=:nyttNamn, losenord=:nyttLosenord WHERE ID=:ID";
$stmt = $db -> prepare($sql);
$stmt -> execute(['nyttNamn'=>$nyttNamn, 'nyttLosenord'=>$nyttLosenord, 'ID'=>$ID]);
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