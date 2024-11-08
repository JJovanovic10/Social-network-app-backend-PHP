<?php
    session_start();
    if(isset($_SESSION['id'])) {
        // Brišemo sesiju
        session_unset(); // $_SESSION = array();
        session_destroy();
    }

    header('Location: login.php');
?>