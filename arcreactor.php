<?php

require('config.php');
require('Engine.php');

$stmt = $conn->prepare("SELECT * FROM accounts");
$stmt->execute();

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// foreach ($rows as $row) {
//     echo '<br>'.$row['username'];
// }

function navigateToURL($navURL){
    echo "<script>window.location.href='".$navURL."'</script>";
}