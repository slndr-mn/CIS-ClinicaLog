<?php
session_start();

// Initialize JavaScript variable
$jsScript = '';

if (isset($_POST['changed_password'])) {
    $pass = $_POST['password'];
    $confirmpass = $_POST['confirm_password'];

    if ($pass !== $confirmpass) {
        $_SESSION['message'] = "Passwords do not match!";
        $_SESSION['message_type'] = "error";
        $jsScript = "
            Swal.fire({
                title: 'Error!',
                text: 'Passwords do not match!',
                icon: 'error',
                confirmButtonText: 'OK',
                allowOutsideClick: false
            });
        ";
    } else {
        $_SESSION['message'] = "Password updated successfully!";
        $_SESSION['message_type'] = "success";
        $jsScript = "
            Swal.fire({
                title: 'Success!',
                text: 'Your password has been updated successfully.',
                icon: 'success',
                confirmButtonText: 'OK',
                allowOutsideClick: false
            }); 
        ";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <form action="" method="post">
        <div>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div>
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password" id="confirm_password" required>
        </div>
        <button type="submit" name="changed_password">Submit</button>
    </form>

    <?php if (isset($jsScript)): ?>
        <script>
            <?php echo $jsScript; ?>
        </script>
    <?php endif; ?>
</body>
</html>
