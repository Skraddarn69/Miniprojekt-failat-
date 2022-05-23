<?php
declare (strict_types=1);

require_once "funktioner.php";

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

$elevID = filter_input(INPUT_POST, 'elevID', FILTER_SANITIZE_NUMBER_INT);
$unwanted = "+\-";
$elevID = trim($elevID, $unwanted);

if($elevID==="") {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "Parametern 'elevID' måste vara ett tal"];
    skickaSvar($error, 400);
}

$db = kopplaDatabas();

$sql="SELECT ID, tabell, poang, datum FROM resultat 
WHERE elevID=:elevID";
$stmt = $db -> prepare($sql);
$stmt -> execute(['elevID'=>$elevID]);
$resultat = $stmt -> fetchAll();

$out = new stdClass();
$out -> rekord=[];
foreach($resultat as $row) {
        $out->rekord[]=['ID'=>$row['ID']];
        $out->rekord[]=['tabell'=>$row['tabell']];
        $out->rekord[]=['poang'=>$row['poang']];
        $out->rekord[]=['datum'=>$row['datum']];
}

skickaSvar($out, 200);