<?php


$conn = mysqli_connect("localhost", "root", "", "inventory_obat");

$ambilsemuadatastock = mysqli_query($conn, "SELECT * FROM stock");

$rows = [];
while ($row = mysqli_fetch_assoc($ambilsemuadatastock)) {
  $rows[] = $row;
}
echo json_encode($rows);
