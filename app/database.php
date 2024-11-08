<?php
    require_once "connection.php";

    $sql = "CREATE TABLE IF NOT EXISTS users(
                id INT UNSIGNED AUTO_INCREMENT,
                username VARCHAR(50) UNIQUE NOT NULL,
                pass VARCHAR(255) NOT NULL,
                PRIMARY KEY(id)
            ) ENGINE = InnoDB;";

    $sql .= "CREATE TABLE IF NOT EXISTS profiles(
                id INT UNSIGNED AUTO_INCREMENT,
                name VARCHAR(50) NOT NULL,
                surname VARCHAR(50) NOT NULL,
                gender CHAR(1),
                dob DATE,
                user_id INT UNSIGNED NOT NULL UNIQUE,
                PRIMARY KEY(id),
                FOREIGN KEY(user_id) REFERENCES users(id)
            ) ENGINE = InnoDB;";

    $sql .= "CREATE TABLE IF NOT EXISTS followers(
                id INT UNSIGNED AUTO_INCREMENT,
                sender_id INT UNSIGNED NOT NULL,
                recever_id INT UNSIGNED NOT NULL,
                PRIMARY KEY(id),
                FOREIGN KEY(sender_id) REFERENCES users(id),
                FOREIGN KEY(recever_id) REFERENCES users(id)
            ) ENGINE = InnoDB;";

    if($conn->multi_query($sql)) {
        echo "<p>Uspešno izvršeni upiti</p>";
    }
    else {
        echo "<p>Greška: $conn->error </p>";
    }