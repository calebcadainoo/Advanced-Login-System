<?php

class Engine{
    
    public $tokenLength = 164;
    
    public static function runSession(){
        if (!isset($_SESSION)) {
            session_start();
        }
    }

    public static function checkLoginState($conn){
        // run session if not running
        Engine::runSession();

        if (isset($_COOKIE['account_id']) && isset($_COOKIE['email']) && isset($_COOKIE['token']) && isset($_COOKIE['serial'])) {
            # search query
            $query = "SELECT * FROM sessions WHERE sessions_accountid = :account_id AND session_token = :token AND session_serial = :serial"; 
           
            // set values
            $account_id = $_COOKIE['account_id'];
            $email = $_COOKIE['email'];
            $token = $_COOKIE['token'];
            $serial = $_COOKIE['serial'];

            $stmt = $conn->prepare($query);
            $stmt->execute(array(':account_id' => $account_id, ':token' => $token, ':serial' => $serial));

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row['sessions_accountid'] > 0) {
                # cheeck cookies...
                if ($row['sessions_accountid'] == $_COOKIE['account_id'] && 
                    $row['session_token'] == $_COOKIE['token'] && 
                    $row['session_serial'] == $_COOKIE['serial'] ) {
                    # check sessions...
                    if ($row['sessions_accountid'] == $_SESSION['account_id'] && 
                        $row['session_token'] == $_SESSION['token'] && 
                        $row['session_serial'] == $_SESSION['serial']) {
                        # code...
                        return true;
                    }else{
                        Engine::createSession($_COOKIE['account_id'], $_COOKIE['email'], $_COOKIE['token'], $_COOKIE['serial']);
                        return true;
                    }
                }
            }
        }else{
            return false;
        }
        echo ' mini ';
        #end of isset condition
    }
    #end of check login

    public static function createAccount($conn, $account_id, $email){
        // delete existing sessions
        $delstmt = $conn->prepare("DELETE FROM sessions WHERE sessions_accountid = :session_accountid");
        $delstmt->execute(array(':session_accountid' => $account_id));

        $query = "INSERT INTO sessions (sessions_accountid, session_token, session_serial, session_date) VALUES (:account_id, :token, :serial, :date_today)";

        $token  = Engine::generateToken(164);
        $serial  = Engine::generateToken(164);

        Engine::createCookie($email, $account_id, $token, $serial);
        Engine::createSession($email, $account_id, $token, $serial);

        // insert session variables in db
        $stmt = $conn->prepare($query);
        $stmt->execute(array(':account_id'=>$account_id, ':token' => $token, ':serial' => $serial, ':date_today' => date('Y-m-d H:i:s')));      
    }
    #end of create account

    public static function createCookie($email, $account_id, $token, $serial){
        setcookie('email', $email, time() + (86400) * 30, '/');
        setcookie('account_id', $account_id, time() + (86400) * 30, '/');
        setcookie('token', $token, time() + (86400) * 30, '/');
        setcookie('serial', $serial, time() + (86400) * 30, '/');
    }
    #end of create cookie

    public static function deleteCookie(){
        Engine::runSession();
        setcookie('email', '', time() - 1, '/');
        setcookie('account_id', '', time() - 1, '/');
        setcookie('token', '', time() - 1, '/');
        setcookie('serial', '', time() - 1, '/');
        session_destroy();
    }
    #end of delete cookies

    public static function createSession($email, $account_id, $token, $serial){
        // run session if not running
        Engine::runSession();
        // set session variables
        $_SESSION['email'] = $email;
        $_SESSION['account_id'] = $account_id;
        $_SESSION['token'] = $token;
        $_SESSION['serial'] = $serial;

    }
    #end of create session

    public static function generateToken($len){
        // NB: keep all tokens 164 characters long
        $charLibrary = "1=qay2-ws!x3edc4rfv5tgb6zhn7ujm8ik9olp_AQWSXEDCVFRTGBNHYZUJMKILOP.";
        $token = '';
        $r_new = '';
        $r_old = '';

        for($i = 1; $i < $len; $i++){
            while($r_old == $r_new){
                $r_new = rand(0, 65);
            }
            $r_old = $r_new;
            $token = $token.$charLibrary[$r_new];
        }
        
        return $token;
        // return substr(str_shuffle($charLibrary), 0, $len);
    }
    # end of generate token
}

?>