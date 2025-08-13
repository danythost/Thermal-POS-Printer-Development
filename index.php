<?php
require_once 'db.php';
require_once 'session_check.php';

// Fetch products from the database
$stmt = $pdo->query("SELECT id, item_name, unit_price, discount FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$message = "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Form</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="icons_fonts/css/all.css" rel="stylesheet">

    <script>
        function addItem() {
            let itemRow = document.createElement("tr");
            itemRow.innerHTML = `
                <td>
                    <select class="form-select" name="item_id[]" required onchange="updatePrice(this)">
                        <option value="">Select Item</option>
                        <?php foreach ($products as $product) { ?>
                            <option value="<?= $product['id']; ?>" data-price="<?= $product['unit_price']; ?>" data-discount="<?= $product['discount']; ?>">
                                <?= $product['item_name']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </td>
                <td><input type="number" class="form-control" name="quantity[]" required oninput="calculateTotal(this)"></td>
                <td><input type="number" class="form-control" name="unit_price[]" readonly></td>
                <td><input type="number" class="form-control" name="discount[]" readonly></td>
                <td><input type="number" class="form-control" name="total_price[]" readonly></td>
                <td><button type="button" class="btn btn-danger remove-item">Remove</button></td>
            `;
            document.getElementById("invoiceTableBody").appendChild(itemRow);
        }

        function updatePrice(select) {
            let row = select.closest("tr");
            let unitPriceInput = row.querySelector('input[name="unit_price[]"]');
            let discountInput = row.querySelector('input[name="discount[]"]');
            let selectedOption = select.options[select.selectedIndex];

            unitPriceInput.value = selectedOption.getAttribute("data-price") || 0;
            discountInput.value = selectedOption.getAttribute("data-discount") || 0;
            calculateTotal(select);
        }

        function calculateTotal(input) {
            let row = input.closest("tr");
            let quantity = row.querySelector('input[name="quantity[]"]').value;
            let unitPrice = row.querySelector('input[name="unit_price[]"]').value;
            let discount = row.querySelector('input[name="discount[]"]').value;
            let totalInput = row.querySelector('input[name="total_price[]"]');

            let total = (quantity * unitPrice) - discount;
            totalInput.value = total > 0 ? total.toFixed(2) : 0;
        }

        document.addEventListener("click", function(event) {
            if (event.target.classList.contains("remove-item")) {
                event.target.closest("tr").remove();
            }
        });
    </script>

    <style>
        body {
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            padding: 20px;
        }

        .container {
            width: 90%;
            max-width: 900px;
        }

        .card {
            width: 100%;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .btn-danger {
            display: block;
            margin: auto;
        }
    </style>
</head>
<body>

    <div class="container-fluid">
        <nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark">
              <div class="container-fluid">
                <a class="navbar-brand" href="#">
                    <img src="utils/Kuyash_farm_logo.jpg" alt="" class="rounded-pill" width="3%" height="3%"> <strong>KUYASH Int. Farms</strong>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                  <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <!-- <li class="nav-item">
                      <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li> -->
                    <li class="nav-item">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                           <i class="fas fa-cogs" style="color: whitesmoke;"></i></button> <strong><span style="color: whitesmoke;">Products</span>
                        </button>
                            <style>
                                /* Custom dropdown styles */
                                .dropdown-menu {
                                    background-color: #f8f9fa; /* Light background */
                                    border-radius: 5px;
                                    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); /* Subtle shadow */
                                }

                                .dropdown-item {
                                    color: #007bff; /* Bootstrap's primary button color */
                                    transition: background-color 0.3s ease, color 0.3s ease;
                                }

                                /* Hover effect on the dropdown items */
                                .dropdown-item:hover {
                                    background-color: #007bff; /* Change background color on hover */
                                    color: #fff; /* Change text color to white */
                                }

                                /* Custom button style */
                                .dropdown-toggle {
                                    background-color: #28a745; /* Green background */
                                    color: white; /* White text */
                                    border: none; /* Remove border */
                                    border-radius: 5px; /* Rounded corners */
                                    padding: 10px 20px;
                                    transition: background-color 0.3s ease;
                                }

                                .dropdown-toggle:hover {
                                    background-color: #218838; /* Darker green on hover */
                                }
                            </style>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="products.php">Change Products</a>
                            <a class="dropdown-item" href="add_product.php">Add Product</a>
                            <a class="dropdown-item" href="registeruser.php">Add User</a>
                            <a class="dropdown-item" href="invoices.php">Invoices</a>
                            <a class="dropdown-item" href="#">Inventory Records</a>
                            <a class="dropdown-item" href="userprofile.php">Manage Profile</a>
                        </div>
                    </li>
                  </ul>
                  <li><button class="btn btn-sm btn-light"><a href="logout.php"><i class="fas fa-sign-out-alt"></i> </a></button> <strong><span style="color: whitesmoke;">Logout</span></strong></li>
                </div>
            </div>
        </nav>

        <div class="card shadow" style="margin-top: 50px;">
            <div class="userid mb-2 mt-2"><h6 style="padding: 5px 5px 5px 5px;"><span><strong>Welcome, <?php echo $_SESSION['email']; ?>!</strong></span></h6></div>
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0"><strong>Invoice Card</strong></h2>
            </div>
            <div class="card-body">
                <?= $message; ?>  <!-- Display messages if any -->
                <form action="process_invoice.php" method="POST">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Customer Name</label>
                            <input type="text" class="form-control" name="c_name" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Customer Phone</label>
                            <input type="text" class="form-control" name="c_phone" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Customer Address</label>
                            <input type="text" class="form-control" name="c_addr" required>
                        </div>
                    </div>

                    <h5 class="mt-4">Invoice Items</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Item Name</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Discount</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="invoiceTableBody">
                            <tr>
                                <td>
                                    <select class="form-select" name="item_id[]" required onchange="updatePrice(this)">
                                        <option value="">Select Item</option>
                                        <?php foreach ($products as $product) { ?>
                                            <option value="<?= $product['id']; ?>" data-price="<?= $product['unit_price']; ?>" data-discount="<?= $product['discount']; ?>">
                                                <?= $product['item_name']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td><input type="number" class="form-control" name="quantity[]" required oninput="calculateTotal(this)"></td>
                                <td><input type="number" class="form-control" name="unit_price[]" readonly></td>
                                <td><input type="number" class="form-control" name="discount[]" readonly></td>
                                <td><input type="number" class="form-control" name="total_price[]" readonly></td>
                                <td><button type="button" class="btn btn-danger remove-item">Remove</button></td>
                            </tr>
                        </tbody>
                    </table>

                    <button type="button" class="btn btn-secondary mt-3" onclick="addItem()">+ Add Item</button>
                    <button type="submit" class="btn btn-primary mt-3">Submit Invoice</button>
                </form>
            </div>
        </div>
    </div>
</body>
<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</html>
