<?php
// Database connection
$host = 'localhost'; // Change if necessary
$dbname = 'warehouse'; // Change to your database name
$username = 'root'; // Change to your database username
$password = ''; // Change to your database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $code = $_POST['code'];
    $sku = $_POST['sku'];
    $symbology = $_POST['symbology'];
    $track_weight = isset($_POST['track_weight']) ? 1 : 0;
    $track_quantity = isset($_POST['track_quantity']) ? 1 : 0;
    $has_variants = isset($_POST['has_variants']) ? 1 : 0;
    $has_serials = isset($_POST['has_serials']) ? 1 : 0;
    $alert_quantity = $_POST['alert_quantity'];
    $variants = $_POST['variants'] ? json_encode(explode(',', $_POST['variants'])) : null;
    $details = $_POST['details'];
    $rack_location = $_POST['rack_location'];
    $unit_id = !empty($_POST['unit_id']) ? $_POST['unit_id'] : null;
    $account_id = $_POST['account_id'];
    $extra_attributes = $_POST['extra_attributes'] ? json_encode(explode(',', $_POST['extra_attributes'])) : null;
    $photo = $_POST['photo'];
    $customer_first_name = $_POST['customer_first_name'];
    $customer_last_name = $_POST['customer_last_name'];
    $shipper_name = $_POST['shipper_name'];
    $receiver_name = $_POST['receiver_name'];

    // Ensure unit_id exists in the units table
    if ($unit_id) {
        $stmt = $pdo->prepare("SELECT id FROM units WHERE id = ?");
        $stmt->execute([$unit_id]);
        if ($stmt->rowCount() == 0) {
            die("<p style='color: red;'>Error: Selected unit does not exist.</p>");
        }
    }

    $sql = "INSERT INTO items (name, code, sku, symbology, track_weight, track_quantity, has_variants, has_serials, alert_quantity, variants, details, rack_location, unit_id, account_id, extra_attributes, created_at, customer_first_name, customer_last_name, shipper_name, receiver_name) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $code, $sku, $symbology, $track_weight, $track_quantity, $has_variants, $has_serials, $alert_quantity, $variants, $details, $rack_location, $unit_id, $account_id, $extra_attributes, $customer_first_name, $customer_last_name, $shipper_name, $receiver_name]);
    
    echo "<p style='color: green;'>Item added successfully!</p>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Item</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>


<body>
    <div class="container mt-5">
        <div class="card shadow p-4">
            <h2 class="text-center">Add New Item</h2>



            <form method="POST">
                <div class="row">
                    <div class="col-md-6">

                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required><br>


                    </div>

                    <div class="col-md-6">

                        <label class="form-label">Code</label>
                        <input type="text" name="code" class="form-control" required><br>
                    </div>

                    </div>

                    SKU: <input type="text" name="sku"><br>
                    Symbology: <input type="text" name="symbology" value="code128"><br>
                    
                    Track Weight: <input type="checkbox" name="track_weight"><br>
                    Track Quantity: <input type="checkbox" name="track_quantity"><br>
                    Has Variants: <input type="checkbox" name="has_variants"><br>
                    Has Serials: <input type="checkbox" name="has_serials"><br>
                    Alert Quantity: <input type="number" name="alert_quantity" step="0.0001"><br>
                    Variants (comma-separated): <input type="text" name="variants"><br>
                    Details: <textarea name="details"></textarea><br>
                    Rack Location: <input type="text" name="rack_location"><br>
                    Unit ID:
                    <select name="unit_id">
                        <option value="">Select Unit</option>
                        <?php
            $units = $pdo->query("SELECT id, name FROM units")->fetchAll(PDO::FETCH_ASSOC);
            foreach ($units as $unit) {
                echo "<option value='{$unit['id']}'>{$unit['name']}</option>";
            }
            ?>
                    </select><br>
                    Account ID: <input type="number" name="account_id" required><br>
                    Extra Attributes (comma-separated): <input type="text" name="extra_attributes"><br>
                    Photo URL: <input type="text" name="photo"><br>
                    Customer First Name: <input type="text" name="customer_first_name" required><br>
                    Customer Last Name: <input type="text" name="customer_last_name" required><br>
                    Shipper Name: <input type="text" name="shipper_name" required><br>
                    Receiver Name: <input type="text" name="receiver_name" required><br>
                    <button type="submit">Add Item</button>
            </form>

        </div>
    </div>
</body>

</html>