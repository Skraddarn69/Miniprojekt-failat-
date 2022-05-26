<?php
declare (strict_types=1);

require_once "funktioner.php";

if($_SERVER['REQUEST_METHOD']!=="POST") {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "Sidan ska anropas med POST"];
    skickaSvar($error, 405);
}

if(!isset($_POST['anvandarTyp'])) {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "Parametern 'anvandarTyp' saknas"];
    skickaSvar($error, 400);
}

$anvandarTyp = filter_input(INPUT_POST, 'anvandarTyp', FILTER_SANITIZE_NUMBER_INT);
$unwanted = "+\-";
$anvandarTyp = trim($anvandarTyp, $unwanted);

if($anvandarTyp==="") {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "Parametern 'anvandarTyp' får endast vara 1 eller 2 och inte vara tom"];
    skickaSvar($error, 400);
}

if($anvandarTyp==2) {
    if(!isset($_POST['lararID'])) {
        $error = new stdClass();
        $error -> error = ["Felaktigt anrop", "Parametern 'lararID' saknas"];
        skickaSvar($error, 400);
    }

    $lararID = filter_input(INPUT_POST, 'lararID', FILTER_SANITIZE_NUMBER_INT);
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
} elseif($anvandarTyp==1) {
    $db = kopplaDatabas();

    $sql="SELECT ID, klass FROM klasser";
    $stmt = $db -> query($sql);
}
    $resultat = $stmt->fetchAll();

$out = new stdClass();
$out -> classes=[];
foreach($resultat as $row) {
    $out->classes[]=['ID'=>$row['ID'], 'klass'=>$row['klass']];
}

skickaSvar($out, 200);