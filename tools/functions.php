<?php

    require_once "database_conn.php";


    function validate_field($field) {
        $field = htmlentities($field);
        if (strlen(trim($field)) < 1) {
            return false;
        }
        return true;
    }
    
    function validate_password($password) {
        $password = htmlentities($password);
        if (strlen(trim($password)) < 1) {
            return "Password cannot be empty";
        } elseif (strlen(trim($password)) < 8) {
            return "Password must be at least 8 characters long";
        }
        return true;
    }
    
    function validate_email($email) {
        $email = trim($email);
        if (empty($email)) {
            return false;
        }
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    function email_exists($email) {
        $conn = get_db_connection();
        if ($conn === null) {
            return false;
        }
    
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
    
        return $stmt->num_rows > 0;
    }
    
    function username_exists($username) {
        $conn = get_db_connection();
        if ($conn === null) {
            return false;
        }
    
        $sql = "SELECT user_id FROM user_accounts WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
    
        return $stmt->num_rows > 0;
    }
    
    function validate_conPass($cpassword, $password) {
        $cpw = htmlentities($cpassword);
        $pw = htmlentities($password);
        return strcmp($pw, $cpw) === 0;
    }
    
    function validate_phoneNum($phone) {
        $phone = htmlentities($phone);
        return preg_match('/^\d{10}$/', $phone);
    }
    
    
    
    
