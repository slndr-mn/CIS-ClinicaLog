<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom Dropdown</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            padding: 20px;
        }

        .dropdown {
            position: relative;
            width: 250px;
            margin: 10px 0;
        }

        .dropdown input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        .dropdown-list {
            position: absolute;
            z-index: 1;
            background-color: #fff;
            border-radius: 4px;
            margin-top: 2px;
            width: 100%;
            max-height: 200px;
            overflow-y: auto;
            display: none; /* Hidden by default */
            border: 1px solid #ccc;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .dropdown-item {
            padding: 10px;
            cursor: pointer;
        }

        .dropdown-item:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <form name="f1" method="post" action="sample2">
        <label for="program">Select Program:</label>
        <div class="dropdown">
            <input type="text" id="program" placeholder="Select a program" oninput="filterItems()" onclick="toggleDropdown()">
            <div id="dropdown-list" class="dropdown-list"></div>
        </div>
        <input type="submit" value="Submit">
    </form>

    <script>
        const programs = ['PHP', 'Python', 'Java', 'Ruby', 'JavaScript', 'C#', 'Go'];
        const dropdownList = document.getElementById('dropdown-list');

        function filterItems() {
            const input = document.getElementById('program').value.toLowerCase();
            dropdownList.innerHTML = ''; // Clear the dropdown list

            programs.forEach(program => {
                if (program.toLowerCase().includes(input)) {
                    const item = document.createElement('div');
                    item.textContent = program;
                    item.classList.add('dropdown-item');
                    item.onclick = () => selectProgram(program);
                    dropdownList.appendChild(item);
                }
            });

            // Show the dropdown if there are items to display
            dropdownList.style.display = dropdownList.innerHTML ? 'block' : 'none';
        }

        function selectProgram(program) {
            document.getElementById('program').value = program; // Set the input value
            dropdownList.style.display = 'none'; // Hide dropdown after selection
        }

        function toggleDropdown() {
            dropdownList.style.display = dropdownList.style.display === 'block' ? 'none' : 'block';
            filterItems(); // Ensure the dropdown items are updated
        }

        document.addEventListener('click', (event) => {
            if (!event.target.closest('.dropdown')) {
                dropdownList.style.display = 'none';
            }
        });
    </script>
</body>
</html>
