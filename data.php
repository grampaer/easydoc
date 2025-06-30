<?php

session_start();

header('Content-Type: application/json');

$rows = $db->getInformationHisByMonth($_SESSION['user_id']);

// Prépare les données pour Chart.js
$labels = [
  "Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
  "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"
];
$counts = array_fill(0, 12, 0);

foreach ($rows as $row) {
  $monthIndex = (int)$row["month"] - 1;
  $counts[$monthIndex] = (int)$row["count"];
}

echo json_encode([
  "labels" => $labels,
  "counts" => $counts
]);
