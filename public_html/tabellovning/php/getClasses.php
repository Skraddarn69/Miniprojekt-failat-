<?php
declare (strict_types=1);

require_once "funktioner.php";

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

    $sql = "SELECT COUNT(*) FROM klasser";
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

if(isset($_GET['teacherID'])) {

    $teacherID = filter_input(INPUT_GET, 'teacherID', FILTER_SANITIZE_NUMBER_INT);

    if($teacherID==="") {
        $error = new stdClass();
        $error -> error = ["Felaktigt anrop", "'teacherID' får inte vara tom"];
        skickaSvar($error, 400);
    }

    $db = kopplaDatabas();

    $sql="SELECT ID, klass FROM klasser WHERE lararID=:teacherID"
        . " ORDER BY klass"
        . " LIMIT $firstRecord, $records";
    $stmt = $db -> prepare($sql);
    $stmt -> execute(['teacherID'=>$teacherID]);

    $resultat = $stmt->fetchAll();

} else {
    $db = kopplaDatabas();

    $sql="SELECT k.ID, k.klass, COUNT(e.ID) AS studCount"
        . " FROM klasser AS k LEFT JOIN elever AS e ON e.klassID = k.ID"
        . " GROUP BY k.klass"
        . " ORDER BY k.klass"
        . " LIMIT $firstRecord, $records";
    $stmt = $db -> prepare($sql);
    if(!$stmt -> execute()) {
        $error = new stdClass();
        $error -> error = ["Fel vid databasanrop", $db->errorInfo()];
        skickaSvar($error, 400);
    }

    if($dbRecords = $stmt->fetchAll()) {
        $out = new stdClass();
        $out -> pages = $antalSidor;
        foreach($dbRecords as $row) {
            $out -> classes[] = $row;
        }
    skickaSvar($out, 200);
    } else {
        $error = new stdClass();
        $error -> error = ["Fel vid hämtning",
             "Inga poster returnerades"];
        skickaSvar($error, 400);
    }
}

$out = new stdClass();
$out -> classes=[];
foreach($resultat as $row) {
    $out->classes[]=['ID'=>$row['ID'], 'klass'=>$row['klass']];
}

skickaSvar($out, 200);