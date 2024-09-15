<?php
session_start();

include('../database/config.php');
include('../php/user.php');

$db = new Database();
$conn = $db->getConnection();
$user = new User($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['addstaff'])) {
        // Get form data
        $id = $_POST['addid'];
        $first_name = $_POST['addfname'];
        $last_name = $_POST['addlname'];
        $middle_name = $_POST['addmname'];
        $email = filter_var($_POST['addemail'], FILTER_SANITIZE_EMAIL);
        $position = $_POST['addposition'];
        $status = $_POST['addstatus'];
        $dateadded = date('Y-m-d H:i:s');
        $password = $id; // Consider hashing the password
        $code = 0;

        // Handle file upload
        $user_profile = '';
        if (isset($_FILES['addprofile']) && $_FILES['addprofile']['error'] === UPLOAD_ERR_OK) {
            $profile = $_FILES['addprofile'];
            $profile_original_name = basename($profile['name']);
            $profile_tmp = $profile['tmp_name'];

            // Validate file type 
            $profile_extension = strtolower(pathinfo($profile_original_name, PATHINFO_EXTENSION));
            $allowed_extensions = ['jpg', 'jpeg', 'png'];
            
            if (in_array($profile_extension, $allowed_extensions)) {
                $profile_hash = md5(uniqid($profile_original_name, true));
                $profile_name = $profile_hash . '.' . $profile_extension;
                $uploadDir = 'uploads/';
                $profile_destination = $uploadDir . $profile_name;

                if (move_uploaded_file($profile_tmp, $profile_destination)) {
                    $user_profile = $profile_name;
                } else {
                    $_SESSION['status'] = 'error';
                    $_SESSION['message'] = 'Failed to upload profile picture.';
                    header('Location: datatables.php');
                    exit(); 
                }
            } else {
                $_SESSION['status'] = 'error';
                $_SESSION['message'] = 'Invalid file extension.';
                header('Location: datatables.php');
                exit();
            }
        }

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if ($user->register($id, $first_name, $last_name, $middle_name, $email, $position, $status, $dateadded, $user_profile, $password, $code)) {
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

        header('Location: datatables.php');
        exit();
    }

    if (isset($_POST['updateuser'])) {
        // Get form data
        $user_oldid = $_POST['editoldid'];
        $user_id = $_POST['editid'];
        $new_fname = $_POST['editfname'];
        $new_lname = $_POST['editlname'];
        $new_mname = $_POST['editmname'];
        $new_email = filter_var($_POST['editemail'], FILTER_SANITIZE_EMAIL);
        $new_position = $_POST['editposition'];
        $new_status = $_POST['editstatus'];
    
        // Initialize new profile picture variable
        $new_profile = null;
    
        // Handle file upload
        if (isset($_FILES['editprofile']) && $_FILES['editprofile']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['editprofile']['tmp_name'];
            $fileName = $_FILES['editprofile']['name'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
            $allowedExtensions = ['jpg', 'jpeg', 'png'];
    
            if (in_array($fileExtension, $allowedExtensions)) {
                $fileHash = md5(uniqid($fileName, true));
                $new_profile = $fileHash . '.' . $fileExtension;
                $uploadFileDir = 'uploads/';
                $dest_path = $uploadFileDir . $new_profile;
    
                if (!move_uploaded_file($fileTmpPath, $dest_path)) {
                    $_SESSION['status'] = 'error';
                    $_SESSION['message'] = 'Error moving the uploaded file.';
                    header('Location: datatables.php');
                    exit();
                }
            } else {
                $_SESSION['status'] = 'error';
                $_SESSION['message'] = 'Invalid file extension.';
                header('Location: datatables.php');
                exit();
            }
        }
    
        // Update user details
        if ($user->updateUser($user_oldid, $user_id, $new_fname, $new_lname, $new_mname, $new_email, $new_position, $new_status)) {
            // Update profile picture if needed
            if ($new_profile) {
                if ($user->updateProfilePicture($user_id, $new_profile)) {
                    $_SESSION['status'] = 'success';
                    $_SESSION['message'] = 'User updated successfully!';
                } else {
                    $_SESSION['status'] = 'error';
                    $_SESSION['message'] = 'User updated, but failed to update profile picture.';
                }
            } else {
                $_SESSION['status'] = 'success';
                $_SESSION['message'] = 'User updated successfully!';
            }
        } else {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Failed to update user.';
        }
    
        header('Location: datatables.php');
        exit();
    }

    if (isset($_POST['user_id'])) {
        $user_id = $_POST['user_id'];
        
        if ($user->deleteUser($user_id)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => $_SESSION['message']]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No user ID provided']);
    }
    
    
}
?>