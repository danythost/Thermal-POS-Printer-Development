# 🧾 Thermal POS Receipt Printing System

## 📌 Overview
This project is a **custom receipt printing system** built for **thermal POS printers**. It processes transactions in real-time, formats receipts, and prints them instantly while maintaining accurate records in a **MySQL database**.  
Designed for **small businesses, restaurants, hotels, and retail stores**, it eliminates dependency on heavy third-party software.

---

## 🚀 Features
- **Database-Driven Orders** – Securely stores all transaction details
- **Thermal Printer Formatting** – Optimized for 80mm paper size
- **Real-Time Printing** – Automatic print trigger after order confirmation
- **Logo & Branding** – Company logo and headers on receipts
- **Multi-Device Support** – Works on Linux, Windows, and Mac (printer drivers required)
- **Inventory Integration** – Optional module to update stock after sale
- **Customizable Layout** – Easily change fonts, sizes, and sections

---

## 🛠 Tech Stack
- **Backend:** PHP (OOP/MVC)
- **Database:** MySQL
- **Frontend:** HTML5, Bootstrap, JavaScript (AJAX for real-time updates)
- **Printing:** ESC/POS commands for thermal printers
- **Server:** Apache/Nginx on Linux (Ubuntu) or Windows
- **Optional Modules:** Inventory Management, User Authentication

---

## 🗄 Database Schema

### `orders` table
| Field        | Type         | Description |
|--------------|-------------|-------------|
| `id`         | INT (PK)    | Unique order ID |
| `customer`   | VARCHAR(100) | Customer name |
| `items`      | TEXT         | JSON list of ordered items |
| `total`      | DECIMAL(10,2)| Total price |
| `payment`    | VARCHAR(50)  | Payment method |
| `created_at` | TIMESTAMP    | Order time |

### `order_items` table (optional)
| Field       | Type         | Description |
|-------------|-------------|-------------|
| `id`        | INT (PK)    | Unique item ID |
| `order_id`  | INT (FK)    | Links to `orders` |
| `product`   | VARCHAR(255)| Item name |
| `qty`       | INT         | Quantity |
| `price`     | DECIMAL(10,2)| Unit price |

---
### Example Receipt
My Store Name
----------------------------
Item         Qty    Price
Burger       2     $5.00
Fries        1     $2.50
----------------------------
Discount:           00.00
TOTAL:              $12.50
Thank you for your purchase!

