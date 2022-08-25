<?php
declare (strict_types=1);
require_once "funktioner.php";

if(!isset($_GET['classID'])) {
    $error = new stdClass();
    $error -> error = ["Felaktig indata", "'classID' saknas"];
    skickaSvar($error, 400);
}

$classID = filter_input(INPUT_GET, 'classID', FILTER_SANITIZE_NUMBER_INT);

if(isset($_GET['page'])) {
    $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
    if($page<1) {
        $error = new stdClass();
        $error -> error = ["Felaktig indata", "Ogiltig 'page'"];
        skickaSvar($error, 400);
    }

    $records = filter_input(INPUT_GET, 'records', FILTER_VALIDATE_INT);
    if($records<1) {
        $records = 9;
    }

    // Räkna ut första posten att returnera
    $firstRecord = ($page-1) * $records;

    $db = kopplaDatabas();

    $sql = "SELECT COUNT(*) FROM elever";
    $stmt = $db -> query($sql);
    $row = $stmt -> fetch(PDO::FETCH_NUM);
    $antalPoster = (int) $row[0];
    $antalSidor = ceil($antalPoster/$records);

    if($page>$antalSidor) {
        $error = new stdClass();
        $error -> error = ['Felaktigt anrop', 
            "Otillräckligt antal poster för att visa sidan $page"];
        skickaSvar($error, 400);
    }
}

$sql="SELECT ID, namn FROM elever"
    . " WHERE klassID=:klassID"
    . " ORDER BY namn"
    . " LIMIT $firstRecord, $records";
$stmt = $db -> prepare($sql);
$stmt -> execute(['klassID'=>$classID]);

if(!$stmt -> execute()) {
    $error = new stdClass();
    $error -> error = ["Fel vid databasanrop", $db->errorInfo()];
    skickaSvar($error, 400);
}

if($dbRecords = $stmt->fetchAll()) {
    $out = new stdClass();
    $out -> pages = $antalSidor;
    foreach($dbRecords as $row) {
        $out -> students[] = $row;
    }
skickaSvar($out, 200);
} else {
    $error = new stdClass();
    $error -> error = ["Fel vid hämtning",
            "Inga poster returnerades"];
    skickaSvar($error, 400);
}

skickaSvar($out, 200);