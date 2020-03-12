<?php

require('arcreactor.php');

// GET PAGE KEY
$page = "";
if (isset($_GET['page'])) {
    $page = $_GET['page'];
}

    
// MAKE PAGE ROUTINGS
switch ($page) {
    case 'login':
        require('login.php');
        break;
    case 'logout':
        require('logout.php');
        break;
    
    default:
        include_once('home.php');
        break;
}

?>
