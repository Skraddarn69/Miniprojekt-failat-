<?php
require_once "funktioner.php";

$db = kopplaDatabas();

$sql="SELECT klass FROM klasser";
$stmt = $db->query($sql);
$resultat = $stmt->fetchAll();

$out = new stdClass();
$out->activities=[];
foreach($resultat as $row) {
    $out->classes[]=['klass'=>$row['klass']];
}

skickaSvar($out, 200);