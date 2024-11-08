<?php
    require_once "connection.php";
    require_once "validation.php";
    require_once "header.php";

    if(empty($_SESSION['id'])) {
        header('Location: login.php');
    }

    $id = $_SESSION['id'];

    $validation = true;
    $nameErr = $surnameErr = $dobErr = "";

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $gender = $_POST['gender'];
        $dob = $_POST['dob'];

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

        // Ako nigde nije prijavljena greška prilikom validacije, vršimo update podataka za logovanog korisnika
        if($validation) {
            $q = "UPDATE `profiles`
                  SET `name` = '$name', `surname` = '$surname', `gender` = '$gender', `dob` = '$dob'
                  WHERE `user_id` = '$id';";
            $res = $conn->query($q);
        }
    }

    $q = "SELECT *
          FROM `profiles`
          WHERE `user_id` = '$id'";

    $res = $conn->query($q);
    $row = $res->fetch_assoc();
    $name = $row['name'];
    $surname = $row['surname'];
    $gender = $row['gender'];
    $dob = $row['dob'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="post">
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
            <input type="submit" value="Submit">
        </p>
    </form>
</body>
</html>