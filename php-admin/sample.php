<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Storage Example</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        input, button {
            margin-top: 10px;
            padding: 10px;
        }
    </style>
</head>
<body>

    <h1>Session Storage Example</h1>
    
    <label for="nameInput">Enter your name:</label>
    <input type="text" id="nameInput" class="storage-input" placeholder="Enter your name" />

    <label for="emailInput">Enter your email:</label>
    <input type="email" id="emailInput" class="storage-input" placeholder="Enter your email" />

    <h2>Stored Name: <span id="storedName"></span></h2>
    <h2>Stored Email: <span id="storedEmail"></span></h2>

    <button id="clearAll">Clear All Session Storage</button>
    <button id="removeItem">Remove Specific Item</button>

    <script>
        // Function to display stored name and email if they exist
        function displayStoredData() {
            const storedName = sessionStorage.getItem('username');
            const storedEmail = sessionStorage.getItem('useremail');
            
            document.getElementById('storedName').innerText = storedName ? storedName : 'No name stored';
            document.getElementById('storedEmail').innerText = storedEmail ? storedEmail : 'No email stored';

            if (storedName) {
                document.getElementById('nameInput').value = storedName; // Set the input field to the stored name
            } else {
                document.getElementById('nameInput').value = ''; // Clear the input field if no name is stored
            }
            if (storedEmail) {
                document.getElementById('emailInput').value = storedEmail; // Set the input field to the stored email
            } else {
                document.getElementById('emailInput').value = ''; // Clear the input field if no email is stored
            }
        }

        // Load the stored data on page load
        displayStoredData();

        // Save name and email to session storage on input change using a single event listener
        document.querySelectorAll('.storage-input').forEach(input => {
            input.addEventListener('input', function() {
                sessionStorage.setItem(this.id === 'nameInput' ? 'username' : 'useremail', this.value);
                displayStoredData();  // Update displayed data
            });
        });

        // Clear all session storage
        document.getElementById('clearAll').addEventListener('click', function() {
            sessionStorage.clear();
            displayStoredData();  // Update displayed data
            alert('All session storage cleared.');
        });

        // Remove specific items and clear input fields
        document.getElementById('removeItem').addEventListener('click', function() {
            // Remove username and email from session storage
            sessionStorage.removeItem('username');
            sessionStorage.removeItem('useremail');
            // Clear the input fields
            document.getElementById('nameInput').value = '';
            document.getElementById('emailInput').value = '';
            displayStoredData();  // Update displayed data
            alert('Username and email removed from session storage and input fields cleared.');
        });
    </script>

</body>
</html>
