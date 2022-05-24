<?php
declare (strict_types=1);

require_once "funktioner.php";

if($_SERVER['REQUEST_METHOD']!=="POST") {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "Sidan ska anropas med POST"];
    skickaSvar($error, 405);
}

if(!isset($_POST['lararID'])) {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "Parametern 'lararID' saknas"];
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

$db = kopplaDatabas();

$sql="SELECT ID, klass FROM klasser WHERE lararID=:lararID";
$stmt = $db -> prepare($sql);
$stmt -> execute(['lararID'=>$lararID]);
$resultat = $stmt->fetchAll();

$out = new stdClass();
$out -> classes=[];
foreach($resultat as $row) {
    $out->classes[]=['ID'=>$row['ID'], 'klass'=>$row['klass']];
}

skickaSvar($out, 200);