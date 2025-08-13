<?php
error_reporting(E_ALL);
session_start();
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $pdo->beginTransaction();

        $customer_name = trim($_POST['c_name']);
        $customer_phone = trim($_POST['c_phone']);
        $customer_address = trim($_POST['c_addr']);

        if (empty($customer_name) || empty($customer_phone) || empty($customer_address)) {
            throw new Exception("Customer details are required.");
        }

        // Check if customer already exists
        $stmt = $pdo->prepare("SELECT id FROM customers WHERE phone = ?");
        $stmt->execute([$customer_phone]);
        $existing_customer = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing_customer) {
            $customer_id = $existing_customer['id'];
        } else {
            $stmt = $pdo->prepare("INSERT INTO customers (name, phone, address) VALUES (?, ?, ?)");
            $stmt->execute([$customer_name, $customer_phone, $customer_address]);
            $customer_id = $pdo->lastInsertId();
        }

        // Insert invoice
        $stmt = $pdo->prepare("INSERT INTO invoices (customer_id, created_at) VALUES (?, NOW())");
        $stmt->execute([$customer_id]);
        $invoice_id = $pdo->lastInsertId();

        // Invoice item insert statement
        $stmt = $pdo->prepare("
            INSERT INTO invoice_items (invoice_id, product_id, quantity, unit_price, discount_amount, total_price) 
            VALUES (?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE 
                quantity = quantity + VALUES(quantity),
                discount_amount = VALUES(discount_amount),
                total_price = VALUES(total_price)
        ");

        // Track totals
        $total_before_discount = 0;
        $total_discount = 0;
        $grand_total = 0;

        if (!empty($_POST['item_id']) && is_array($_POST['item_id'])) {
            // Deduplicate input to avoid insertion errors (in case of identical items posted twice)
            $items = [];

            foreach ($_POST['item_id'] as $index => $item_id) {
                $item_id = (int) $item_id;
                $quantity = (int) $_POST['quantity'][$index];

                if (isset($items[$item_id])) {
                    $items[$item_id] += $quantity;
                } else {
                    $items[$item_id] = $quantity;
                }
            }

            foreach ($items as $item_id => $quantity) {
                // Fetch product info
                $stmtProduct = $pdo->prepare("SELECT id, unit_price, discount_percentage FROM products WHERE id = ?");
                $stmtProduct->execute([$item_id]);
                $product = $stmtProduct->fetch(PDO::FETCH_ASSOC);

                if (!$product) {
                    throw new Exception("Invalid product ID: $item_id");
                }

                $unit_price = (float) $product['unit_price'];
                $discount_percentage = (float) $product['discount_percentage'];

                // Correct discount logic
                $discount_per_unit = ($unit_price * $discount_percentage) / 100;
                $total_discount = $discount_per_unit * $quantity;
                $price_after_discount = max(0, ($unit_price * $quantity) - $total_discount);

                // Accumulate invoice totals
                $total_before_discount += ($unit_price * $quantity);
                $grand_total += $price_after_discount;

                // Save invoice item
                $stmt->execute([$invoice_id, $item_id, $quantity, $unit_price, $total_discount, $price_after_discount]);
            }
        } else {
            throw new Exception("No items provided.");
        }

        $pdo->commit();

        header("Location: receipt.php?invoice_id=" . $invoice_id);
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        die("Error: " . $e->getMessage());
    }
} else {
    die("Invalid request.");
}
