<?php
require_once 'db_connectie.php';

// maak verbinding met de database (zie db_connection.php)
$db = maakVerbinding();

// haal alle bestellingen op en hun details
$query = 'SELECT 
            PO.order_id AS id, 
            COALESCE(PO.client_name, \'Geen Klantnaam\') AS klant_naam, 
            U.first_name + \' \' + U.last_name AS personeel_naam, 
            CASE 
              WHEN PO.status = 1 THEN \'In afwachting\' 
              WHEN PO.status = 2 THEN \'Bezig\' 
              WHEN PO.status = 3 THEN \'Afgerond\' 
              ELSE \'Onbekend Status\'
            END AS status
          FROM Pizza_Order PO
          LEFT JOIN [User] U ON PO.personnel_username = U.username
          ORDER BY PO.datetime DESC';

$data = $db->query($query);

$html_table = '<table>';
$html_table = $html_table . '<tr><th>Bestelnummer</th><th>Klantnaam</th><th>Personeel</th><th>Status</th></tr>';

while ($rij = $data->fetch()) {
  $id = $rij['id'];
  $klant_naam = $rij['klant_naam'];
  $personeel_naam = $rij['personeel_naam'];
  $status = $rij['status'];
  
  $html_table = $html_table . "<tr><td>$id</td><td>$klant_naam</td><td>$personeel_naam</td><td>$status</td></tr>";
}

$html_table = $html_table . "</table>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    table,
    td,
    th {
      padding: 0px 2px 0px 5px;
      border: 1px solid black;
    }
    table { border-collapse: collapse; }
    td { text-align: left; }
    td:first-child { text-align: right; }
    td:last-child { text-align: center; }
  </style>
  <title>Pizza Bestellingen</title>
</head>
<body>
  <h1>Overzicht van Pizza Bestellingen</h1>
  <?php 
  echo ($html_table);
  ?>
</body>
</html>
