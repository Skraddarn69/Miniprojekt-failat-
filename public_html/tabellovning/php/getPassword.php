<?php
require_once "funktioner.php";

$db = kopplaDatabas();

if($_SERVER['REQUEST_METHOD']!=="POST") {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "Sidan ska anropas med POST"];
    skickaSvar($error, 405);
}

if(!isset($_POST['anvandarTyp'])) {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'anvandarTyp' saknas"];
    skickaSvar($error, 400);
}

$anvandarTyp = filter_input(INPUT_POST, 'anvandarTyp', FILTER_SANITIZE_NUMBER_INT);
$unwanted = "+\-";
$anvandarTyp = trim($anvandarTyp, $unwanted);

if($anvandarTyp==="") {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'anvandarTyp' får inte vara tom"];
    skickaSvar($error, 400);
}

if($anvandarTyp==1 || $anvandarTyp==2) {
    if(!isset($_POST['ID'])) {
        $error = new stdClass();
        $error -> error = ["Felaktig indata", "'ID' saknas"];
        skickaSvar($error, 400);
    }

    $ID = filter_input(INPUT_POST, 'ID', FILTER_SANITIZE_NUMBER_INT);
    $ID = trim($ID, $unwanted);

    if($ID==="") {
        $error = new stdClass();
        $error -> error = ["Felaktig indata", "'ID' får endast bestå av ett heltal och inte vara tom"];
        skickaSvar($error, 400);
    }
}
    
if($anvandarTyp==1) {
    $sql="SELECT losenord FROM larare WHERE ID=:ID";
    $stmt = $db -> prepare($sql);
    $stmt -> execute(['ID'=>$ID]);
} elseif($anvandarTyp==2) {
    $sql="SELECT losenord FROM elev WHERE ID=:ID";
    $stmt = $db -> prepare($sql);
    $stmt -> execute(['ID'=>$ID]);
} elseif($anvandarTyp==3) {
    $sql="SELECT losenord FROM admin";
    $stmt = $db -> query($sql);
} else {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'anvandarTyp' måste vara 1, 2 eller 3"];
    skickaSvar($error, 400);
}

$resultat = $stmt -> fetchObject();
$antaPoster = $stmt -> rowCount();
if($antaPoster===0) {
    $svar = new stdClass();
    $svar -> result = false;
    $svar -> message = ["Inget lösenord returnerades"];
    skickaSvar($svar, 200);
} else {
    $out = new stdClass();
    $out -> losenord = $resultat -> losenord;
    skickaSvar($out, 200);
}