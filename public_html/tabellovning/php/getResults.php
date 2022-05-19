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

$sql="SELECT * FROM resultat 
WHERE elevID=:elevID";
$stmt = $db -> prepare($sql);
$stmt -> execute(['elevID'=>$elevID]);
$resultat = $stmt -> fetchObject();

$out = new stdClass();
$out -> rekord=[];
$i = 0;
foreach($resultat as $row) {
    if($i==0) {
        $out->rekord[]=['elevID'=>$row];
        $i += 1;
    } elseif($i==13) {
        $out->rekord[]=['blandade'=>$row];
        $i += 1;
    } else {
        $out->rekord[]=[$i . 'x'=>$row];
        $i += 1;
    }
}

skickaSvar($out, 200);