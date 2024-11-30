<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Izpis podatkov o delavcu</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Izpis podatkov o delavcu</h1>
<?php
$uporabnik = 'root';
$geslo = '';
$db = 'praksa';

$pma = new mysqli('localhost', $uporabnik, $geslo, $db);

if ($pma->connect_errno) {
    echo "Napaka pri vzpostavljanju povezave z bazo: " . $pma->connect_error;
    exit();
}
$sql = "SELECT id, ime, priimek, naslov, kraj, e_naslov, tel_stevilka FROM delavec";
$result = $pma->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="employee-card">';
        echo '<div class="employee-info">';
        echo '<p><span class="label">Id:</span> <span class="value">' . $row["id"] . '</span></p>';
        echo '<p><span class="label">Ime:</span> <span class="value">' . $row["ime"] . '</span></p>';
        echo '<p><span class="label">Priimek:</span> <span class="value">' . $row["priimek"] . '</span></p>';
        echo '<p><span class="label">Naslov:</span> <span class="value">' . $row["naslov"] . '</span></p>';
        echo '<p><span class="label">Kraj:</span> <span class="value">' . $row["kraj"] . '</span></p>';
        echo '<p><span class="label">E-naslov:</span> <span class="value">' . $row["e_naslov"] . '</span></p>';
        echo '<p><span class="label">Tel. številka:</span> <span class="value">' . $row["tel_stevilka"] . '</span></p>';
        echo '</div>';
        echo '</div>';
    }
}
$result->free_result();
$pma->close();
?>

</body>
</html>