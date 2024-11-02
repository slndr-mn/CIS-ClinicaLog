<?php
$hashedPassword = '$2y$10$os.yczuFLfvMps91e34K/eQYj4To144F.kv4e3TBg5XAwZB9.BLTO';
$inputPassword = '2210';



if (password_verify($inputPassword, $hashedPassword)) {
    echo 'Password is valid!';
} else {
    echo 'Invalid password.';
}

?>