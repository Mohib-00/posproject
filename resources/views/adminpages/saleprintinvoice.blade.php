<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Purchase Invoice</title>
    <link rel="icon" href="https://admin.logix199.com/favicon.ico" type="image/x-icon">
    <!-- Vendor CSS -->
    <link rel="stylesheet" href="{{ asset('purchase_invoice_files/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('purchase_invoice_files/font-awesome.min.css') }}">
    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('purchase_invoice_files/main.css') }}">
    <link rel="stylesheet" href="{{ asset('purchase_invoice_files/color_skins.css') }}">

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        .invoice1 {
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
        }

        .card-header {
            border-bottom: 1px solid #dee2e6 !important;
        }

        .invoice-top h4 {
            font-size: 24px;
            margin-bottom: 0.5rem;
        }

        .invoice-top p {
            margin: 0;
            color: #6c757d;
        }

        .table thead th {
            background-color: #ffc107;
            color: #212529;
            border-bottom: 2px solid #dee2e6;
        }

        .table td,
        .table th {
            vertical-align: middle;
        }

        .table-info strong,
        .table-danger strong,
        .table-success strong {
            display: inline-block;
            width: 100%;
            padding: 6px;
            border-radius: 5px;
        }

        .table-info strong {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .table-danger strong {
            background-color: #f8d7da;
            color: #721c24;
        }

        .table-success strong {
            background-color: #d4edda;
            color: #155724;
        }

        .btn {
            min-width: 100px;
            margin: 5px;
        }

        .nodisplay {
            display: none;
        }

        @media print {
            .nodisplay {
                display: none !important;
            }

            body {
                background-color: #fff;
            }

            .card.invoice1 {
                box-shadow: none;
                border: none;
            }
        }
    </style>
</head>

<body>

    <div class="container mt-4">
        <div class="card invoice1">
            <div class="body">
                <div class="invoice-top d-flex justify-content-between align-items-center">
                    <div class="title">
                        <h4>Invoice No:</strong> INV-{{ str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}</h4>
                        <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($sale->created_at)->format('Y-m-d') }}</p>
                        <p><strong>Created By:</strong> {{ $userName }}</p>
                        <p>Branch: Head Office</p>
                        <p><strong>Customer Name:</strong> {{ $sale->customer_name }}</p>
                    </div>
                </div>
                <hr>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="thead-warning">
                            <tr>
                                <th>Sr #</th>
                                <th>Product</th>
                                <th>Item Barcode</th>
                                <th>Qty</th>
                                <th>Rate</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sale->saleItems as $item)
                                
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->product_name }}</td>
                                    <td></td>
                                    <td>{{ $item->product_quantity }}</td>
                                    <td>Rs:{{ number_format($item->product_rate, 2) }}</td>
                                    <td>Rs:{{ number_format($item->product_subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        
                            <tr>
                                <td colspan="4"></td>
                                <td class="table-info"><strong>Gross Amount</strong></td>
                                <td class="table-info"><strong>{{ $sale->total }}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="4"></td>
                                <td class="table-danger"><strong>Discount</strong></td>
                                <td class="table-danger"><strong>{{ $sale->discount }}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="4"></td>
                                <td class="table-danger"><strong>Fixed Discount</strong></td>
                                <td class="table-danger"><strong>{{ $sale->fixed_discount }}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="4"></td>
                                <td class="table-success"><strong>Amount Total</strong></td>
                                <td class="table-success"><strong>{{ $sale->subtotal }}</strong></td>
                            </tr>
                            
                            <tr>
                                <td colspan="4"></td>
                                <td class="table-success"><strong>Amount Received</strong></td>
                                <td class="table-success"><strong>
                                    @if ($sale->status == 'complete')
                                    <strong>{{ $sale->subtotal }}</strong>
                                @else
                                    <strong>0</strong>
                                @endif
                                
                                </td>
                            </tr>
                           
                            
                            <tr>
                                <td colspan="4"></td>
                                <td class="table-success"><strong>Amount Return</strong></td>
                                <td class="table-success"><strong>{{ $sale->sale_return }}</strong></td>
                            </tr>
                        </tbody>
                        
                    </table>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <h5>Note</h5>
                        <p>Thank you for your purchase! We appreciate your business and look forward to serving you again.</p>
                    </div>
                </div>

                <div class="row ">
                    <div class="col-md-12 text-center mt-4">
                        <a href="/admin/purchase_list" onclick="loadsalelistPage(); return false;">
                            <button type="button" class="btn btn-danger">Back</button>
                        </a>
                        <button class="btn btn-info" id="printPageButton" onclick="window.print();">Print</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('purchase_invoice_files/libscripts.bundle.js') }}"></script>
    <script src="{{ asset('purchase_invoice_files/vendorscripts.bundle.js') }}"></script>
    <script src="{{ asset('purchase_invoice_files/mainscripts.bundle.js') }}"></script>
    @include('adminpages.ajax')
</body>

</html>
