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
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Code</label>
                    <input type="text" name="code" class="form-control" required>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-6">
                    <label class="form-label">SKU</label>
                    <input type="text" name="sku" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Symbology</label>
                    <input type="text" name="symbology" class="form-control" value="code128">
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="track_weight" id="track_weight">
                        <label class="form-check-label" for="track_weight">Track Weight</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="track_quantity" id="track_quantity">
                        <label class="form-check-label" for="track_quantity">Track Quantity</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="has_variants" id="has_variants">
                        <label class="form-check-label" for="has_variants">Has Variants</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="has_serials" id="has_serials">
                        <label class="form-check-label" for="has_serials">Has Serials</label>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-6">
                    <label class="form-label">Customer First Name</label>
                    <input type="text" name="customer_first_name" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Customer Last Name</label>
                    <input type="text" name="customer_last_name" class="form-control" required>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-6">
                    <label class="form-label">Shipper Name</label>
                    <input type="text" name="shipper_name" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Receiver Name</label>
                    <input type="text" name="receiver_name" class="form-control" required>
                </div>
            </div>

            <div class="mt-4 text-center">
                <button type="submit" class="btn btn-primary">Add Item</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
