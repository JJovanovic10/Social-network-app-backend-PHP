<?php
    function text_validation($text) {
        if(empty($text)) {
            return "Enter value";
        }
        elseif(strlen($text)>50) {
            return "The field must be less than 50 characters";
        }
        elseif(ctype_alpha(str_replace(" ", "", $text)) == false) {
            return "The field can only contains letters and spaces";
        }
        else {
            return false;
        }
    }

    function date_validation($date) {
        $year = (int)substr($date, 0, 4);
        if($year < 1900) {
            return "Year of the birth can't be before 1900";
        }
        else {
            return false;
        }
    }

    function passwordValidation($pass) {
        if(empty($pass)) {
            return "Enter value";
        }
        elseif(preg_match("/\s/",$pass)) {
            return "Password can't contains spaces";
        }
        elseif(strlen($pass)<5 || strlen($pass)>25) {
            return "Password must be between 5 and 25 characters";
        }
        else {
            return false;
        }
    }


    // function validation_name_surname($text) {
    //     if( strlen($text)>=50 ) {
    //         return false;
    //     }
    //     $text = trim($text) // Odseca višak praznina
    //     if( preg_match('/\s\s+/',$text) == false ) {
    //         return false;
    //     } 
    //     return true;
    // }
?>