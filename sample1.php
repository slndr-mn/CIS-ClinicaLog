<?php
// Initialize a variable to store the result
$result = '';

// Handle the AJAX request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if 'action' parameter is set
    if (isset($_POST['action']) && $_POST['action'] === 'check') {
        // Simulate some PHP logic
        $someVariable = 'This is a test';

        // Set the result based on whether the variable is set
        $result = isset($someVariable) ? 'Variable is set!' : 'Variable is not set.';
        
        // Return the result and exit to avoid sending HTML content
        echo $result;
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check PHP Variable</title>
    <script>
        function handleClick() {
            // Send a request to the server-side PHP script
            fetch(window.location.href, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=check'
            })
            .then(response => response.text())
            .then(data => {
                // Handle the response from PHP
                document.getElementById('result').innerHTML = data;
            });
        }
    </script>
</head>
<body>
    <a href="#" onclick="handleClick(); return false;">Click me</a>
    <div id="result"><?php echo $result; ?></div>
</body>
</html>





<script>
let countdownTimer;
        function startCountdown() {
            let countdown = 20; // 20 seconds
            const resendLink = document.getElementById('resend-link');
            resendLink.style.pointerEvents = 'none'; // Disable link

            const countdownDisplay = document.getElementById('countdown');
            countdownDisplay.innerText = countdown;

            countdownTimer = setInterval(function() {
                countdown--;
                countdownDisplay.innerText = countdown;
                if (countdown <= 0) {
                    clearInterval(countdownTimer);
                    resendLink.style.pointerEvents = 'auto'; // Enable link
                    countdownDisplay.innerText = '';
                }
            }, 1000);
        }

        function resendCode(event) {
            event.preventDefault();
            // Trigger resend OTP via AJAX
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'verify.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    Swal.fire({
                        title: 'OTP Sent!',
                        text: 'Code has been sent to your email.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'An error occurred while sending the OTP.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            };
            xhr.send('resend=1');
            startCountdown();
        }

        function startCountdown() {
            let countdown = 20; // 20 seconds
            const resendLink = document.getElementById('resend-link');
            resendLink.style.pointerEvents = 'none'; // Disable link

            const countdownDisplay = document.getElementById('countdown');
            countdownDisplay.innerText = `${countdown} seconds`;

            const countdownTimer = setInterval(function() {
                countdown--;
                countdownDisplay.innerText = `${countdown} seconds`;
                if (countdown <= 0) {
                    clearInterval(countdownTimer);
                    resendLink.style.pointerEvents = 'auto'; // Enable link
                    countdownDisplay.innerText = ''; // Clear display
                }
            }, 1000);
        }
    </script>

    