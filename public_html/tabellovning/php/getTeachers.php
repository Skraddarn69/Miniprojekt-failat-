<?php
declare (strict_types=1);

require_once "funktioner.php";

if($_SERVER['REQUEST_METHOD']!=="POST") {
    $error = new stdClass();
    $error -> error = ["Felaktigt anrop", "Sidan ska anropas med POST"];
    skickaSvar($error, 405);
}

$db = kopplaDatabas();

$sql="SELECT ID, namn FROM larare";
$stmt = $db -> query($sql);
$resultat = $stmt->fetchAll();

$out = new stdClass();
$out -> classes=[];
foreach($resultat as $row) {
    $out->classes[]=['ID'=>$row['ID'], 'namn'=>$row['namn']];
}

skickaSvar($out, 200);