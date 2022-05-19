<?php
require_once "funktioner.php";

$db = kopplaDatabas();

if($_SERVER['REQUEST_METHOD']!=="POST") {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "Sidan ska anropas med POST"];
    skickaSvar($error, 405);
}

if(!isset($_POST['klassID'])) {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'klassID' saknas"];
    skickaSvar($error, 400);
}

$klassID = filter_input(INPUT_POST, 'klassID', FILTER_SANITIZE_NUMBER_INT);
$unwanted = "+\-";
$klassID = trim($klassID, $unwanted);

if($klassID==="") {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'klassID' får endast bestå av ett heltal och inte vara tom"];
    skickaSvar($error, 400);
}

$sql="SELECT ID, namn FROM elever WHERE klassID=:klassID";
$stmt = $db -> prepare($sql);
$stmt -> execute(['klassID'=>$klassID]);
$resultat = $stmt -> fetchAll();

$out = new stdClass();
$out -> students=[];
foreach($resultat as $row) {
    $out->students[]=['ID'=>$row['ID'], 'namn'=>$row['namn']];
}

skickaSvar($out, 200);