<?php
    require_once "connection.php";
    require_once "validation.php";
    require_once "header.php";

    if(empty($_SESSION['id'])) {
        header('Location: login.php');
    }

    $id = $_SESSION['id'];

    $validation = true;
    $oldPasswordErr = $newPasswordErr = $retypeNewPasswordErr = "";

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $oldPassword = $_POST['old_password'];
        $newPassword = $_POST['new_password'];
        $retypeNewPassword = $_POST['retype_new_password'];

        // Validacija za password
        if(passwordValidation($newPassword)) {
            $newPasswordErr = passwordValidation($newPassword);
            $validation = false;
        }

        // Validacija za re.password
        if(passwordValidation($retypeNewPassword)) {
            $retypeNewPasswordErr = passwordValidation($retypeNewPassword);
            $validation = false;
            if($newPassword != $retypeNewPassword) {
                $retypeNewPasswordErr = "Password and retype password must be the same";
                $validation = false;
            }
        }

        // Proveravamo da li se stari password poklapa sa pass. koji je u bazi
        $q = "SELECT `pass` FROM `users` WHERE `id` = '$id';";
        $res = $conn->query($q);
        $row = $res->fetch_assoc();

        // Proverim da li mi se poklapaju iz baze pass i pass koji sam unela kao old
        if(md5($oldPassword) !=  $row['pass']) {
            $oldPasswordErr = "Old password doesn't match";
            $validation = false;
        }

        // Ako nigde nije prijavljena greška prilikom validacije, vršimo izmenu password polja u DB
        if($validation) {
           $new = md5($newPassword);
           $q = "UPDATE `users`
                 SET `pass` = '$new'
                 WHERE `id` = '$id';";
            $conn->query($q);
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
</head>
<body>
    <form action="#" method="post">
        <p>
            Old password:
            <input type="password" name="old_password" value="">
            <span class="err" id="err_surname">* <?php echo $oldPasswordErr; ?> </span>
        </p>
        <p>
            New password:
            <input type="password" name="new_password" value="">
            <span class="err" id="err_surname">* <?php echo $newPasswordErr; ?> </span>
        </p>
        <p>
            Retype new password:
            <input type="password" name="retype_new_password" value="">
            <span class="err" id="err_surname">* <?php echo $retypeNewPasswordErr; ?> </span>
        </p>
        <p>
            <input type="submit" value="Submit">
        </p>
    </form>
</html>