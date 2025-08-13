<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'db.php';

// Fetch all invoices with additional calculations
$stmt = $pdo->prepare("
    SELECT invoices.id, invoices.created_at, 
           customers.name, customers.phone, customers.address,
           SUM(invoice_items.unit_price * invoice_items.quantity) AS before_discount, 
           SUM(products.discount) AS discount,  
           SUM((invoice_items.unit_price * invoice_items.quantity) - products.discount) AS payable_total
    FROM invoices 
    JOIN customers ON invoices.customer_id = customers.id
    JOIN invoice_items ON invoices.id = invoice_items.invoice_id
    JOIN products ON invoice_items.product_id = products.id
    GROUP BY invoices.id
    ORDER BY invoices.created_at DESC
");

$stmt->execute();
$invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoices - Kuyash</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="icons_fonts/css/all.css" rel="stylesheet">
    <link href="css/style.css">
</head>
<body>
<div class="container-fluid">
    <div class="row">
<style>
    body {
        background-color: #4d4d4d;
        font-family: 'Courier New', monospace;
    }
    .container {
        margin-top: 20px;
    }

    .text-center, h2{
        color: whitesmoke;
    }

    .table {
        background: white;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    th, td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    th {
        background-color: #f2f2f2;
        color: #333;
        text-transform: uppercase;
        font-weight: bold;
    }
    tr:hover {
        background-color: #f5f5f5;
    }
    .id-column {
        width: 5%;
        text-align: center;
    }
    .unit-price-column {
        width: 20%;
        text-align: right;
    }
</style>

<div class="col-md-12">
<div class="main-content" style="margin-top: 50px;">
    <h2 class="text-center"><strong>All Receipts</strong></h2>
    <div class="container">
         <div class="nav-items mb-3">
            <a href="index.php"><button class="btn btn-primary btn-sm"><i class="fas fa-home"></i></button></a>

            <button id="exportPDF" class="btn btn-danger ms-2 btn-sm" style="float: right;">
                <i class="fas fa-file-pdf"></i> Export to PDF
            </button>

            <button id="exportCSV" class="btn btn-success btn-sm" style="float: right;">
                <i class="fas fa-file-export"></i> Export to CSV
            </button>
        </div>
        <div class="form-group mb-3">
            <input type="text" id="invoiceSearch" class="form-control" placeholder="Search invoice...">
        </div>
        <div id="invoiceContent">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Invoice ID</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Sub-Total ₦</th>
                    <th>Disc. ₦</th>
                    <th>Net-Total ₦</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="invoiceTableBody">
                <?php foreach ($invoices as $invoice): ?>
                <tr>
                    <td><?php echo $invoice['id']; ?></td>
                    <td><?php echo date('d M Y, h:i A', strtotime($invoice['created_at'])); ?></td>
                    <td><?php echo htmlspecialchars($invoice['name']); ?></td>
                    <td><?php echo htmlspecialchars($invoice['phone']); ?></td>
                    <td><?php echo htmlspecialchars($invoice['address']); ?></td>
                    <td><?php echo number_format($invoice['before_discount'], 2); ?></td>
                    <td><?php echo number_format($invoice['discount'], 2); ?></td>
                    <td><?php echo number_format($invoice['payable_total'], 2); ?></td>
                    <td>
                        <a href="receipt.php?invoice_id=<?php echo $invoice['id']; ?>" class="btn btn-sm btn-primary">
                            <!-- <i class="fas fa-receipt"></i>-->Receipt
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    </div>
</div>
</div>
</div>
</div>

<script>
document.getElementById('invoiceSearch').addEventListener('input', function() {
  const searchValue = this.value.toLowerCase();
  const rows = document.querySelectorAll('#invoiceTableBody tr');
  
  rows.forEach(row => {
    const rowText = row.textContent.toLowerCase();
    row.style.display = rowText.includes(searchValue) ? '' : 'none';
  });
});
</script>








<script>
document.getElementById('exportCSV').addEventListener('click', function() {
    // Get table data
    const rows = document.querySelectorAll('#invoiceTableBody tr');
    let csvContent = "data:text/csv;charset=utf-8,";
    
    // Add headers
    const headers = [];
    document.querySelectorAll('thead th').forEach(header => {
        // Skip the "Action" column
        if (!header.textContent.includes('Action')) {
            headers.push(`"${header.textContent.replace(/₦/g, '').trim()}"`);
        }
    });
    csvContent += headers.join(',') + "\r\n";
    
    // Add rows
    rows.forEach(row => {
        const rowData = [];
        const cols = row.querySelectorAll('td');
        
        cols.forEach((col, index) => {
            // Skip the last column (Action buttons)
            if (index < cols.length - 1) {
                let text = col.textContent.trim();
                // Remove currency formatting for numbers
                if (index >= 5) { // For numeric columns
                    text = text.replace(/,/g, '');
                }
                rowData.push(`"${text}"`);
            }
        });
        
        csvContent += rowData.join(',') + "\r\n";
    });
    
    // Create download link
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement('a');
    link.setAttribute('href', encodedUri);
    link.setAttribute('download', `invoices_${new Date().toISOString().slice(0,10)}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
});
</script>

<script>
    // Initialize jsPDF
const { jsPDF } = window.jspdf;

document.getElementById('exportPDF').addEventListener('click', function() {
    // Create new PDF document
    const doc = new jsPDF({
        orientation: 'portrait',
        unit: 'mm'
    });

    // Add metadata
    doc.setProperties({
        title: `Invoice Report ${new Date().toLocaleDateString()}`,
        subject: 'Invoice records export',
        author: 'Your Company Name'
    });

    // Add title and metadata
    doc.setFontSize(16);
    doc.text('KUYASH INTERGRATED FARMS -INVOICE REPORT', 14, 15);
    doc.setFontSize(10);
    doc.text(`Generated on: ${new Date().toLocaleString()}`, 14, 20);
    doc.text(`Total Records: ${document.querySelectorAll('#invoiceTableBody tr').length}`, 14, 25);

    // Prepare table data
    const headers = Array.from(document.querySelectorAll('thead th'))
        .slice(0, -1) // Exclude Action column
        .map(header => header.textContent.replace(/₦/g, '').trim());

    const rows = Array.from(document.querySelectorAll('#invoiceTableBody tr')).map(row => {
        return Array.from(row.querySelectorAll('td'))
            .slice(0, -1) // Exclude Action column
            .map(cell => {
                let text = cell.textContent.trim();
                // Remove formatting from numbers
                if (cell.cellIndex >= 5) {
                    text = text.replace(/,/g, '');
                }
                return text;
            });
    });

    // Add table to PDF
    doc.autoTable({
        head: [headers],
        body: rows,
        startY: 30,
        styles: {
            cellPadding: 3,
            fontSize: 8,
            valign: 'middle'
        },
        columnStyles: {
            5: { cellWidth: 20 }, // Sub-Total
            6: { cellWidth: 15 }, // Discount
            7: { cellWidth: 20 }  // Net-Total
        },
        didDrawPage: function(data) {
            // Footer
            doc.setFontSize(8);
            doc.text(`Page ${data.pageCount}`, data.settings.margin.left, doc.internal.pageSize.height - 10);
        }
    });

    // Add summary
    const finalY = doc.lastAutoTable.finalY + 10;
    doc.setFontSize(10);
    doc.text('SUMMARY', 14, finalY);
    
    const summary = [
        `Total Sub-Total: ₦${calculateColumnTotal(5).toLocaleString()}`,
        `Total Discount: ₦${calculateColumnTotal(6).toLocaleString()}`,
        `Total Net-Total: ₦${calculateColumnTotal(7).toLocaleString()}`
    ];
    
    summary.forEach((text, i) => {
        doc.text(text, 14, finalY + 5 + (i * 5));
    });

    // Save PDF
    doc.save(`invoices_${new Date().toISOString().slice(0,10)}.pdf`);
});

// Reuse the same column total calculator from CSV export
function calculateColumnTotal(columnIndex) {
    return Array.from(document.querySelectorAll('#invoiceTableBody tr'))
        .reduce((sum, row) => {
            const cell = row.querySelector(`td:nth-child(${columnIndex + 1})`);
            return sum + parseFloat(cell.textContent.replace(/,/g, '') || 0);
        }, 0);
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" defer></script>

</body>
</html>
