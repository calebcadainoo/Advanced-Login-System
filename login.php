<?php

if (Engine::checkLoginState($conn)) {
    navigateToURL('./'); // go back home
}
    if (isset($_POST['email']) && isset($_POST['password'])) {
        # code...
        $query = "SELECT * FROM accounts WHERE email = :email AND password = :password";

        $email = htmlentities($_POST['email']);
        $password = htmlentities($_POST['password']);

        $stmt = $conn->prepare($query);
        $stmt->execute(array(':email' => $email, ':password' => $password));

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row['account_id'] > 0) {
            Engine::createAccount($conn, $row['account_id'], $row['email']);
            navigateToURL('./'); // go back home

            echo '<br>Token: '.Engine::generateToken(164).'<br>';
        }

    }else{
        echo '
            <br>
            <h1>Login FOrm</h1>
            <form method="post" action="">
            <div class="hi">
                <label>Email</label><br>
                <input type="text" name="email" >
            </div>
            <div class="hi">
                <label>Password</label><br>
                <input type="password" name="password" >
            </div>
            <input type="submit" value="Login">
            </form>
        ';
    }
// }


?>

Hello everyone login