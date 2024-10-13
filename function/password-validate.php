<?php

function passwordValidate($password, $confirmPassword) {
    $isValid = true;

    // Check if the password is empty
    if (empty($password)) {
        $isValid = false;
        return $isValid;
    } else {
        // Check if the password length is at least 8 characters
        if (strlen($password) < 8) {
            $isValid = false;
            return $isValid;
        }

        // Check for at least one lowercase letter in the password
        if (!preg_match("/[a-z]/", $password)) {
            $isValid = false;
            return $isValid;
        }

        // Check for at least one uppercase letter in the password
        if (!preg_match("/[A-Z]/", $password)) {
            $isValid = false;
            return $isValid;
        }

        // Check for at least one special character in the password
        if (!preg_match("/[\W_]/", $password)) {
            $isValid = false;
            return $isValid;
        }
    }

    // Check if the confirmation password is empty
    if (empty($confirmPassword)) {
        $isValid = false;
        return $isValid;
    }

    // Check if the password and confirmation password do not match
    if ($password !== $confirmPassword) {
        $isValid = false;
        return $isValid;
    }

    return $isValid;
}
