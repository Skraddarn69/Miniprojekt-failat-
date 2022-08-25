<?php
declare (strict_types=1);
require_once "funktioner.php";

if(!isset($_GET['userType'])) {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'userType' saknas"];
    skickaSvar($error, 400);
}

$userType = filter_input(INPUT_GET, 'userType', FILTER_SANITIZE_NUMBER_INT);

if(!isset($_GET['ID'])) {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'ID' saknas"];
    skickaSvar($error, 400);
}

$ID = filter_input(INPUT_GET, 'ID', FILTER_SANITIZE_NUMBER_INT);

if($userType==0) {
    $tabell = "elever";
} elseif($userType==1) {
    $tabell = "larare";
} else {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'userType' måste vara 0 eller 1"];
    skickaSvar($error, 400);
}

$db = kopplaDatabas();

$sql="SELECT losenord FROM $tabell"
    . " WHERE ID=:ID";
$stmt = $db -> prepare($sql);
$stmt -> execute(['ID'=>$ID]);

if(!$stmt -> execute()) {
    $error = new stdClass();
    $error -> error = ["Fel vid databasanrop", $db->errorInfo()];
    skickaSvar($error, 400);
}

if($record = $stmt->fetchObject()) {
    skickaSvar($record, 200);
} else {
    $out = new stdClass();
    $out -> error = ["Post saknas", "ID=$ID finns inte"];
    skickaSvar($out, 400);
}