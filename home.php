<?php

// CHECK IF USER IS LOGGED IN
if (!Engine::checkLoginState($conn)) {
    # redirect user to login page
    navigateToURL('?page=login');
    exit();
}


# show welcome if logged in...
echo "Welcome ".$_SESSION['email'].'!';

// echo Engine::checkLoginState($conn);

?>
<div class="btnlogout">
    <a href="?page=logout">Logout</a>
</div>