<?php
session_start();

include('../database/config.php');
include('../php/user.php');

$db = new Database(); 
$conn = $db->getConnection();

$user = new User($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['register'])) {
        $user_id = $_POST['user_id'];
        $user_fname = $_POST['user_fname'];
        $user_lname = $_POST['user_lname'];
        $user_mname = $_POST['user_mname'];
        $user_email = filter_var($_POST['user_email'], FILTER_SANITIZE_EMAIL);
        $user_position = $_POST['user_position'];
        $user_status = $_POST['user_status'];
        $user_dateadded = date('Y-m-d H:i:s');
        $password = $_POST['password'];
        $code = $_POST['code'];

        $user_profile = '';
        if (isset($_FILES['user_profile']) && $_FILES['user_profile']['error'] == 0) {
            $profile = $_FILES['user_profile'];
            $profile_original_name = basename($profile['name']);
            $profile_tmp = $profile['tmp_name'];
        
            $profile_hash = md5(uniqid($profile_original_name, true));
            $profile_extension = pathinfo($profile_original_name, PATHINFO_EXTENSION);
            $profile_name = $profile_hash . '.' . $profile_extension;
        
            $uploadDir = "uploads/";
            $profile_destination = $uploadDir . $profile_name;
        
            if (move_uploaded_file($profile_tmp, $profile_destination)) {
                $user_profile = $profile_name;
            } else {
                $_SESSION['status'] = 'error';
                $_SESSION['message'] = 'Failed to upload profile picture.';
                header('Location: form.php');
                exit();
            }
        }
        

        if (filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
            if ($user->register($user_id, $user_fname, $user_lname, $user_mname, $user_email, $user_position, $user_status, $user_dateadded, $user_profile, $password, $code)) {
                $_SESSION['status'] = 'success';
                $_SESSION['message'] = 'User registered successfully!';
            } else {
                $_SESSION['status'] = 'error';
                $_SESSION['message'] = 'Registration failed. Please try again.';
            }
        } else {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Invalid email address.';
        }

        header('Location: form.php');
        exit();
    }
}
?>
