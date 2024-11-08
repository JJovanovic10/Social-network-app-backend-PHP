<?php
    require_once "connection.php";
    require_once "validation.php";

    $validation = true;
    $nameErr = $surnameErr = $dobErr = $passwordErr = $usernameErr = $retypePasswordErr = "";

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $gender = $_POST['gender'];
        $dob = $_POST['dob'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $retypePassword = $_POST['retypePassword'];

        // Validacija za ime
        if(text_validation($name)) {
            $nameErr = text_validation($name);
            $validation = false;
        }

        // Validacija za prezime
        if(text_validation($surname)) {
            $surnameErr = text_validation($surname);
            $validation = false;
        }

        // Validacija za datum
        if(date_validation($dob)) {
            $dobErr = date_validation($dob);
            $validation = false;
        }

        // Validacija za username
        if(text_validation($username)) {
            $usernameErr = text_validation($username);
            $validation = false;
        }

        // Validacija za password
        if(passwordValidation($password)) {
            $passwordErr = passwordValidation($password);
            $validation = false;
        }

        // Validacija za re.password
        if(passwordValidation($retypePassword)) {
            $retypePasswordErr = passwordValidation($retypePassword);
            $validation = false;
            if($password != $retypePassword) {
                $retypePasswordErr = "Password and retype password must be the same";
                $validation = false;
            }
        }

        // Ako nigde nije prijavljena greška prilikom validacije, vršimo unos u bazu podataka
        if($validation) {
            // 1. unos u tabelicu users
            $password = md5($password);
            $q = "INSERT INTO `users`(`username`, `pass`) VALUES ('$username', '$password')";
            if($conn->query($q)) {
                $q = "SELECT `id` 
                      FROM `users` 
                      WHERE `username` LIKE '$username';";

                $res = $conn->query($q);
                $red = $res->fetch_assoc();
                $id = $red['id'];

                // 2. unos u tabelicu profiles
                $q = "INSERT INTO `profiles`(`name`, `surname`, `gender`, `dob`, `user_id`) 
                      VALUES ('$name', '$surname', '$gender', '$dob', '$id')";
                if($conn->query($q)) {
                    header('Location: login.php'); // Redirekcija na stranicu login.php
                }
            } else {
                echo $conn->error_log;
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
    <link rel="stylesheet" href="style.css">
    <title>Register</title>
</head>
<body>
    <form action="#" method="post">
        <p>
            Name:
            <input type="text" name="name" value="<?php if(isset($name)) echo $name; ?>">
            <span class="err" id="err_name">* <?php echo $nameErr; ?></span>
        </p>
        <p>
            Surname:
            <input type="text" name="surname" value="<?php if(isset($surname)) echo $surname; ?>">
            <span class="err" id="err_surname">* <?php echo $surnameErr; ?> </span>
        </p>
        <p>
            Gender:
            <input type="radio" name="gender" value="m" <?php if(isset($gender) && $gender == "m") echo "checked"; ?> >Male
            <input type="radio" name="gender" value="f" <?php if(isset($gender) && $gender == "f") echo "checked"; ?>>Female
            <input type="radio" name="gender" value="o" <?php if(isset($gender) && $gender != "m" && $gender != "f") echo "checked"; ?>>Other
        </p>
        <p>
            Date of birth:
            <input type="date" name="dob" value="<?php if(isset($dob)) echo $dob; ?>">
            <span class="err" id="err_surname">* <?php echo $dobErr; ?> </span>
        </p>
        <p>
            Username:
            <input type="text" name="username" value="<?php if(isset($username)) echo $username; ?>">
            <span class="err" id="err_username">* <?php echo $usernameErr; ?> </span>       
        </p>
        <p>
            Password:
            <input type="password" name="password" value="">
            <span class="err" id="err_surname">* <?php echo $passwordErr; ?> </span>
        </p>
        <p>
            Retype password:
            <input type="password" name="retypePassword" value="">
            <span class="err" id="err_surname">* <?php echo $retypePasswordErr; ?> </span>
        </p>
        <p>
            <input type="submit" value="Submit">
        </p>
    </form>
</body>
</html>