<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'clinicalogsample'); // Replace with your actual DB details
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['medstock_id'], $_POST['requested_qty'])) {
    $medstock_id = $_POST['medstock_id'];
    $requested_qty = (int) $_POST['requested_qty'];

    $stmt = $conn->prepare("SELECT m.medstock_qty - (IFNULL(SUM(pm.pm_medqty), 0) + IFNULL(SUM(mi.mi_medqty), 0)) AS available_stock
                            FROM medstock m 
                            LEFT JOIN prescribemed pm ON pm.pm_medstockid = m.medstock_id 
                            LEFT JOIN medissued mi ON mi.mi_medstockid = m.medstock_id
                            WHERE m.medstock_id = ? 
                            GROUP BY m.medstock_id");
    $stmt->bind_param("s", $medstock_id);
    $stmt->execute();
    $stmt->bind_result($current_qty);
    $stmt->fetch();
    $stmt->close();

    if ($current_qty === null) {
        echo json_encode(["status" => "error", "message" => "Medicine not found"]);
    } elseif ($requested_qty > $current_qty) {
        echo json_encode(["status" => "error", "message" => "Only $current_qty available in stock"]);
    } else {
        echo json_encode(["status" => "success"]);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicine Stock Check</title>
    <script>
        // Real-time validation for medicine quantity
        function checkQuantity() {
            const medstockId = document.getElementById('medstock_id').value;
            const requestedQty = document.getElementById('requested_qty').value;
            const messageElement = document.getElementById('qty-message');

            if (medstockId && requestedQty > 0) {
                const formData = new FormData();
                formData.append('medstock_id', medstockId);
                formData.append('requested_qty', requestedQty);

                // Send AJAX request to self
                fetch('', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'error') {
                        messageElement.textContent = data.message;
                        messageElement.style.display = 'block';
                    } else {
                        messageElement.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    messageElement.textContent = 'An error occurred. Please try again.';
                    messageElement.style.display = 'block';
                });
            } else {
                messageElement.style.display = 'none'; // Hide the message if inputs are invalid
            }
        }
    </script>
</head>
<body>
    <div class="form-group">
        <label for="medstock_id">Medicine ID:</label>
        <input type="text" id="medstock_id" name="medstock_id" class="form-control" placeholder="Enter Medicine ID" >
    </div>

    <div class="form-group">
        <label for="requested_qty">Quantity:</label>
        <input type="number" id="requested_qty" name="requested_qty" class="form-control" placeholder="Enter quantity" min="1" oninput="checkQuantity()">
        <small id="qty-message" class="text-danger" style="display: none;"></small>
    </div>
</body>
</html>

<?php $conn->close(); ?>
