<?php

require_once 'config.php';

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])){
    $name = trim($_POST['name']) ?? '';
    $phone = trim($_POST['phone']) ?? '';
    $email = trim($_POST['email']) ?? '';
    $password = trim($_POST['password']) ?? '';
    $confirm_password = trim($_POST['confirm_password']) ?? '';

    $name = $phone = $email = $password = $confirm_password = '';
    $error = [];

    // Validate required fields
    if(empty($name)){
        $error['name'] = "Name is required.";
    }

    if(empty($phone)){
        $error['phone'] = "Phone is required.";
    }

    if(empty($email)){
        $error['email'] = "Email is required.";
    }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error['email'] = "Invalid email format.";
    }

    if(empty($password)){
        $error['password'] = "Password is required.";
    }elseif(strlen($password) < 6){
        $error['password'] = "Password must be at least 6 characters.";
    }

    if(empty($confirm_password)){
        $error['confirm_password'] = "Confirm Password is required.";
    }elseif($password !== $confirm_password){
        $error['confirm_password'] = "Passwords do not match.";
    }

    if(empty($error)){
        //Hash the password
        $hash_password = password_hash($password, PASSWORD_BCRYPT);

        // Prepare SQL statement
        $sql = $connect->prepare("INSERT INTO users (name, phone, email, password) VALUES (?, ?, ?, ?)");
        $sql->bind_param("ssss", $name, $phone, $email, $hash_password);

        if ($sql->execute()) {
            echo "<p class='text-green-600'>✅ Registration successful!</p>";
            // Reset form values
            $name = $phone = $email = '';
        } else {
            $error['email'] = '❌ Email already exists or DB error.';
        }

        $sql->close();
    }
}