<?php
    // Otvaranje sesije
    session_start();

    require_once 'connection.php';
    $usernameErr = $passErr = "";
    $set = false;
    if(isset($_GET['username']) && isset($_GET['password'])) {
        $set = true;
    }

    if($_SERVER['REQUEST_METHOD'] == "GET" && $set) {
        // korisnik želi da pokuša logovanje slanjem svog username i pass
        $username = $_GET['username'];
        $pass = $_GET['password'];
        $validation = true;

        if(empty($username)) {
            $validation = false;
            $usernameErr = "Username cannot be left blank";
        }
        if(empty($pass)) {
            $validation = false;
            $passErr = "Password connot be left blank";
        }

        if($validation) {
            // Pokušavamo da ulogujemo korisnika
            $q = "SELECT *
                  FROM `users`
                  WHERE `username` = '$username';";
            $res = $conn->query($q);

            if($res->num_rows == 0) {
                // Ne postoji korisnik sa unetim korisničkim imenom
                $usernameErr = "This username doesn't exist";
            } 
            else {
                // Postoji korisnik sa unetim korisničkim imenom i treba proveriti da li se poklapa password
                $row = $res->fetch_assoc();
                $dbPass = $row['pass'];
                if($dbPass == md5($pass)) {
                    // Onda vršimo logovanje
                    $_SESSION['id'] = $row['id'];
                    // $_SESSION['username'] = $row['username'];
                    // echo $_SESSION['id'];
                    // echo $_SESSION['username'];
                    header('Location:followers.php');
                }
                else {
                    // Prijaviti grešku
                    $passErr = "Incorrect password";
                }
            }
        }
    }    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form action="" method="get">
        <p>
            <label for="">Username: </label>
            <input type="text" name="username" id="">
            <span id="usernameErr" class="err">* <?php echo $usernameErr; ?> </span>
        </p>

        <p>
            <label for="">Password:</label>
            <input type="password" name="password">
            <span id="passErr" class="err">* <?php echo $passErr; ?></span>
        </p>

        <p>
            <input type="submit" value="Log In">
        </p>
    </form>
</body>
</html>