<?php
session_start();
if (isset($_SESSION['status']) && isset($_SESSION['message'])) {
    echo "<script>alert('{$_SESSION['message']}');</script>";
    unset($_SESSION['status']);
    unset($_SESSION['message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body> 
    <h2>Register</h2>
    <form action="process.php" method="POST" enctype="multipart/form-data">
        <label for="user_id">User ID:</label>
        <input type="text" id="user_id" name="user_id" required><br><br>
        
        <label for="user_fname">First Name:</label>
        <input type="text" id="user_fname" name="user_fname" required><br><br>
        
        <label for="user_lname">Last Name:</label>
        <input type="text" id="user_lname" name="user_lname" required><br><br>
        
        <label for="user_mname">Middle Name:</label>
        <input type="text" id="user_mname" name="user_mname"><br><br>
        
        <label for="user_email">Email:</label>
        <input type="email" id="user_email" name="user_email" required><br><br>
        
        <label for="user_position">Position:</label>
        <input type="text" id="user_position" name="user_position" required><br><br>
        
        <label for="user_status">Status:</label>
        <input type="text" id="user_status" name="user_status" required><br><br>
        
        <label for="user_profile">Profile Picture:</label>
        <input type="file" id="user_profile" name="user_profile"><br><br>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        
        <label for="code">Code:</label>
        <input type="number" id="code" name="code" required><br><br>
        
        <button type="submit" name="register">Register</button>
    </form>
</body>
</html>
