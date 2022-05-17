<?php
require_once "funktioner.php";

$db = kopplaDatabas();

if($_SERVER['REQUEST_METHOD']!=="POST") {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "Sidan ska anropas med POST"];
    skickaSvar($error, 405);
}

if(!isset($_POST['klass'])) {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'klass' saknas"];
    skickaSvar($error, 400);
}

$klass = filter_input(INPUT_POST, 'klass', FILTER_UNSAFE_RAW);
$klass = strip_tags($klass);

$sql="SELECT namn FROM elever WHERE klass=:klass";
$stmt = $db -> prepare($sql);
$stmt -> execute(['klass'=>$klass]);
$resultat = $stmt -> fetchAll();

$out = new stdClass();
$out -> students=[];
foreach($resultat as $row) {
    $out->students[]=['namn'=>$row['namn']];
}

skickaSvar($out, 200);