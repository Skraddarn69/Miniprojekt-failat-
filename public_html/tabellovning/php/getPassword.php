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

if($anvandarTyp==1) {
    $tabell = "larare";
} elseif($anvandarTyp===2) {
    $tabell = "elev";
} elseif($anvandarTyp===3) {
    $tabell = "admin";
} else {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'anvandarTyp' får endast vara 1, 2 eller 3"];
    skickaSvar($error, 400);
}

if($tabell !== "admin") {
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

    $sql="SELECT losenord FROM :tabell WHERE ID=:ID";
    $stmt = $db -> prepare($sql);
    $stmt -> execute(['tabell'=>$tabell, 'ID'=>$ID]);
    $resultat = $stmt -> fetchObject();
    $out = new stdClass();
    $out -> losenord = $resultat['losenord'];

    skickaSvar($out, 200);
} else {
    $sql="SELECT losenord FROM :tabell";
    $stmt = $db -> prepare($sql);
    $stmt -> execute(['tabell'=>$tabell]);
    $resultat = $stmt -> fetchObject();
    $out = new stdClass();
    $out -> losenord = $resultat['losenord'];

    skickaSvar($out, 200);
}