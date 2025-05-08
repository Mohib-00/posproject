<!DOCTYPE html>
<html lang="en">
<head>
    @include('adminpages.css')
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        .invoice-container {
            max-width: 800px;
            margin: 40px auto;
            background: #fff;
            padding: 40px 50px;
            border-radius: 10px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        }

        .invoice-header {
            text-align: center;
            border-bottom: 2px solid #007bff;
            margin-bottom: 30px;
            padding-bottom: 10px;
        }

        .invoice-header h1 {
            margin: 0;
            color: #007bff;
            font-size: 32px;
            font-weight: bold;
        }

        .invoice-info, .customer-info {
            margin-bottom: 20px;
            font-size: 15px;
        }

        .invoice-info p, .customer-info p {
            margin: 5px 0;
        }

        .invoice-info strong, .customer-info strong {
            color: #333;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .invoice-table th, .invoice-table td {
            text-align: left;
            padding: 12px;
            border: 1px solid #e0e0e0;
            font-size: 14px;
        }

        .invoice-table thead {
            background-color: #f1f3f5;
        }

        .total {
            text-align: right;
            font-size: 16px;
            font-weight: bold;
            margin-top: 20px;
        }

        .discount {
            margin-top: 10px;
            text-align: right;
            font-size: 14px;
            color: #555;
        }

        .thank-you-note {
            text-align: center;
            font-size: 16px;
            margin-top: 50px;
            font-style: italic;
            color: #555;
            border-top: 1px dashed #ccc;
            padding-top: 20px;
        }

        .signature {
            text-align: center;
            font-weight: bold;
            font-size: 15px;
            margin-top: 10px;
        }

        .print-btn {
            display: flex;
            justify-content: flex-end;
            margin-top: 30px;
        }

        .print-btn button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
        }

        @media print {
            body * {
                visibility: hidden;
            }

            #invoicePrintArea, #invoicePrintArea * {
                visibility: visible;
            }

            #invoicePrintArea {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                margin: auto;
                width: 800px;
                padding: 20px;
                box-shadow: none;
            }

            button {
                display: none !important;
            }

            .thank-you-note, .signature {
                text-align: center !important;
                color: #000 !important;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        @include('adminpages.sidebar')

        <div class="main-panel">
            @include('adminpages.header')

            <div class="container">
                <div id="invoicePrintArea" class="invoice-container">
                    <div class="invoice-header">
                        <h1>SALE INVOICE</h1>
                    </div>

                    <div class="invoice-info">
                        <p><strong>Invoice No:</strong> INV-{{ str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}</p>
                        <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($sale->created_at)->format('Y-m-d') }}</p>
                        <p><strong>Created By:</strong> {{ $userName }}</p>
                    </div>

                    <div class="customer-info">
                        <p><strong>Customer Name:</strong> {{ $sale->customer_name ?? 'N/A' }}</p>
                    </div>

                    <table class="invoice-table">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sale->saleItems as $item)
                                <tr>
                                    <td>{{ $item->product_name }}</td>
                                    <td>{{ $item->product_quantity }}</td>
                                    <td>Rs:{{ number_format($item->product_rate, 2) }}</td>
                                    <td>Rs:{{ number_format($item->product_subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="total">
                        Total: Rs:{{ number_format($sale->total, 2) }}
                    </div>

                    @if ($sale->discount && $sale->discount > 0)
                        <div class="discount">
                            Discount: Rs:{{ number_format($sale->discount, 2) }}
                        </div>
                    @elseif ($sale->fixed_discount && $sale->fixed_discount > 0)
                        <div class="discount">
                            Fix Discount: Rs:{{ number_format($sale->fixed_discount, 2) }}
                        </div>
                    @endif

                    <div class="total">
                        Subtotal: Rs:{{ number_format($sale->subtotal, 2) }}
                    </div>

                    <!-- Thank You Note -->
                    <div class="thank-you-note">
                        <p>Thank you for your purchase! We appreciate your business and look forward to serving you again.</p>
                    </div>
                    <div class="signature">
                        — The Sales Team
                    </div>

                    <div class="print-btn">
                        <button onclick="window.print()">Print Invoice</button>
                    </div>
                </div>
            </div>

            @include('adminpages.footer')
        </div>
    </div>

    @include('adminpages.js')
    @include('adminpages.ajax')
</body>
</html>
