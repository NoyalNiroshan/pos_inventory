<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
        }
        .container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
        }
        .header {
            text-align: right;
        }
        .header h2 {
            margin: 0;
            font-size: 28px;
            color: #333;
        }
        .header small {
            font-size: 12px;
        }
        .logo {
            float: left;
            width: 200px;
        }
        .company-details {
            text-align: right;
        }
        .invoice-details {
            margin-top: 40px;
        }
        .invoice-details h3 {
            margin-bottom: 20px;
            font-size: 18px;
            font-weight: bold;
        }
        .invoice-details table {
            width: 100%;
        }
        .invoice-details table td {
            padding: 5px 0;
        }

        /* Table Style with Light Green Color */
        .table {
            width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
        }
        .table th, .table td {
            padding: 12px;
            vertical-align: middle;
            border-top: 1px solid #dee2e6;
        }
        .table-bordered {
            border: 2px solid #90EE90; /* Light Green Border */
        }
        .table-bordered th,
        .table-bordered td {
            border: 1px solid #90EE90; /* Light Green Border */
        }
        .table thead {
            background-color: #ccffcc; /* Light Green Header */
            color: #333;
        }
        .table th {
            font-size: 16px;
            text-align: center;
        }
        .table td {
            text-align: center;
        }

        .total-section {
            text-align: right;
            margin-top: 30px;
            font-size: 16px;
        }

        .terms {
            margin-top: 30px;
            font-size: 12px;
            color: #555;
        }

        .footer {
            text-align: center;
            margin-top: 50px;
            font-size: 12px;
            color: #555;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row mb-4">
        <div class="col-sm-6">
            <img src="/path-to-your-logo/logo.png" alt="Company Logo" class="logo">
        </div>
        <div class="col-sm-6 company-details">
            <h2>Happy Market</h2>
            <p>No. 218 Berown Road, Jaffna</p>
            <p>Phone: 0776401799</p>
            <p>Email: admin@innovay.com</p>
        </div>
    </div>

    <div class="invoice-details">
        <h3>Invoice</h3>
        <table>
            <tr>
                <td><strong>Invoice #:</strong> {{ $stockIn->id }}</td>
                <td><strong>Invoice Date:</strong> {{ $stockIn->stock_in_date }}</td>
            </tr>
            <tr>
                <td><strong>Supplier:</strong> {{ $stockIn->supplier->company_name }}</td>
                <td><strong>Batch No:</strong> {{ $stockIn->batch_no }}</td>
            </tr>
            <tr>
                <td colspan="2">
                    <strong>Address:</strong> {{ $stockIn->supplier->address }}
                </td>
            </tr>
        </table>
    </div>

    <!-- Full-width Light Green Table -->
    <table class="table table-bordered mt-4">
        <thead>
        <tr>
            <th>#</th>
            <th>Description</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
        @foreach($stockIn->items as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>LKR {{ number_format($item->price_per_unit, 2) }}</td>
                <td>LKR {{ number_format($item->total_price, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <p><strong>Sub Total:</strong> LKR {{ number_format($stockIn->total_amount, 2) }}</p>
        <p><strong>Total Payments:</strong> LKR {{ number_format($totalPaid, 2) }}</p>
        <p><strong>Balance Due:</strong> LKR {{ number_format($balanceDue, 2) }}</p>
    </div>

    <div class="terms">
        <h6>Terms & Conditions</h6>
        <p>All services provided are subject to the terms and conditions outlined in the contract agreement or engagement letter.</p>
    </div>

    <div class="footer">
        <p>Thank you for your business!</p>
    </div>
</div>
</body>
</html>
