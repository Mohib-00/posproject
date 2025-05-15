<!DOCTYPE html>
<html lang="en">

<head>
    @include('adminpages.css')
    <style>
        .styled-table {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 20px 0;
        }

        .styled-table th,
        .styled-table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .styled-table th {
            background-color: #f4f4f9;
            font-weight: bold;
            text-transform: uppercase;
        }

        .styled-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .styled-table tr:hover {
            background-color: #f1f1f1;
        }

        
    </style>
</head>

<body>
    <div class="wrapper">
        @include('adminpages.sidebar')

        <div class="main-panel">
            @include('adminpages.header')

            <div class="container">
                <div class="page-inner">
                    <div class="card">
                        <form method="GET" action="{{ route('stock.search') }}" class="row g-3 p-4">
                            <div class="col-md-2">
                                <label for="from_date">From Date</label>
                                <input type="date" id="from_date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                            </div>
                            <div class="col-md-2">
                                <label for="to_date">To Date</label>
                                <input type="date" id="to_date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                            </div>
                            <div class="col-md-2">
                                <label for="vendors">Company</label>
                                <select class="form-select" name="vendors">
                                    <option value="">All</option>
                                    @foreach ($purchasesvendors->unique('vendors') as $purchase)
                                        <option value="{{ $purchase->vendors }}" {{ request('vendors') == $purchase->vendors ? 'selected' : '' }}>
                                            {{ $purchase->vendors }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">Search</button>
                            </div>
                        </form>

                          <div class="card-header">
                                    <div>
                                        <button class="btn btn-sm btn-outline-primary me-2 print-saletable">Print</button>
                                        <button class="btn btn-sm btn-outline-danger export-salepdf">PDF</button>
                                    </div>
                                </div>

                        <h1 class="mx-3">Stock Report</h1>

                        <div class="card-body" style="margin-top:-40px">
                            <div class="table-responsive">
                                <table class="styled-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Code</th>
                                            <th>Name</th>
                                            <th style="white-space: nowrap">Opening Stock</th>
                                            <th>Amount</th>
                                            <th style="white-space: nowrap">Purchase Stock</th>
                                            <th>Amount</th>
                                            <th style="white-space: nowrap">Total Stock</th>
                                            <th>Amount</th>
                                            <th style="white-space: nowrap">Sale Stock</th>
                                            <th>Amount</th>
                                            <th style="white-space: nowrap">Sale Return Stock</th>
                                            <th>Amount</th>
                                            <th style="white-space: nowrap">Available Stock</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $counter = 1; @endphp
                                        @foreach($purchases as $purchase)
                                            @php
                                                $quantities = json_decode($purchase->quantity ?? '[]');
                                                $productIds = json_decode($purchase->products ?? '[]');
                                                $amounts = json_decode($purchase->retail_rate ?? '[]');
                                            @endphp

                                            @foreach($productIds as $index => $productId)
                                                @php
                                                    $selectedProduct = $products->firstWhere('id', $productId);
                                                    $purchaseQty = $quantities[$index] ?? 0;
                                                    $currentQuantity = $selectedProduct->quantity ?? 0;
                                                    $originalQuantity = $currentQuantity - $purchaseQty;
                                                    $purchaseAmount = $amounts[$index] ?? 0;
                                                    $saleItem = DB::table('sale_items')->where('product_name', $selectedProduct->item_name)->first();
                                                    $saleStock = $saleItem->product_quantity ?? 0;
                                                    $saleAmount = $saleItem->product_subtotal ?? 0;
                                                @endphp

                                                <tr>
                                                    <td>{{ $counter++ }}</td>
                                                    <td>{{ $selectedProduct->id ?? 'N/A' }}</td>
                                                    <td style="white-space: nowrap">{{ $selectedProduct->item_name ?? 'N/A' }}</td>
                                                    <td>{{ $originalQuantity }}</td>
                                                    <td>{{ $selectedProduct->retail_rate ?? 'N/A' }}</td>
                                                    <td>{{ $purchaseQty }}</td>
                                                    <td>{{ $purchaseAmount }}</td>
                                                    <td>{{ $selectedProduct->quantity ?? 'N/A' }}</td>
                                                    <td>{{ $selectedProduct->retail_rate + $purchaseAmount }}</td>
                                                    <td>{{ $saleStock }}</td>
                                                    <td>{{ $saleAmount }}</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>{{ $selectedProduct->quantity }}</td>
                                                    <td>{{ $selectedProduct->retail_rate }}</td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('adminpages.footer')
        </div>
    </div>

    @include('adminpages.ajax')
  @include('adminpages.js')

<script>
  document.addEventListener('DOMContentLoaded', function () {
    function removeActionColumn(clonedTable) {
      const headerCells = clonedTable.querySelectorAll('thead th');
      let actionIndex = -1;

      headerCells.forEach((th, index) => {
        if (th.innerText.trim().toLowerCase() === 'action') {
          actionIndex = index;
        }
      });

      if (actionIndex > -1) {
        clonedTable.querySelectorAll('thead tr').forEach(row => {
          row.deleteCell(actionIndex);
        });

        clonedTable.querySelectorAll('tbody tr').forEach(row => {
          if (row.cells.length > actionIndex) {
            row.deleteCell(actionIndex);
          }
        });
      }
    }

    const printBtn = document.querySelector('.print-saletable');
    const pdfBtn = document.querySelector('.export-salepdf');

    if (printBtn) {
      printBtn.addEventListener('click', function () {
        const tables = document.querySelectorAll('.styled-table');
        const listTitle = "Stock Report";

        if (tables.length === 0) {
          alert('Tables not found!');
          return;
        }

        let clonedTables = '';
        tables.forEach(table => {
          const clonedTable = table.cloneNode(true);
          removeActionColumn(clonedTable);
          clonedTables += clonedTable.outerHTML;
        });

        const newWin = window.open('', '_blank');
        newWin.document.write(`
          <html>
            <head>
              <title>${listTitle}</title>
              <style>
                table {
                  border-collapse: collapse;
                  width: 100%;
                  margin-bottom: 20px;
                }
                th, td {
                  border: 1px solid #000;
                  padding: 8px;
                  text-align: left;
                }
                h1 {
                  text-align: center;
                  margin-bottom: 20px;
                }
                @media print {
                  table {
                    table-layout: auto !important;
                    font-size: 10pt !important;
                    width: 100% !important;
                  }
                  th {
                    padding: 4px 6px !important;
                    white-space: nowrap !important;
                  }
                  td {
                    padding: 4px 6px !important;
                    white-space: normal !important;
                    word-break: break-word !important;
                  }
                }
                thead {
                  display: table-header-group;
                }
                tr {
                  page-break-inside: avoid;
                  page-break-after: auto;
                }
                th, td {
                  border: 1px solid #000 !important;
                  text-align: center;
                }
              </style>
            </head>
            <body>
              <h1>${listTitle}</h1>
              ${clonedTables}
            </body>
          </html>
        `);
        newWin.document.close();
        newWin.focus();
        newWin.print();
        newWin.close();
      });
    }

    if (pdfBtn) {
      pdfBtn.addEventListener('click', function () {
        const tables = document.querySelectorAll('.styled-table');
        const listTitle = "Stock Report";

        if (tables.length === 0) {
          alert("Tables not found!");
          return;
        }

        let wrapper = document.createElement('div');
        let heading = document.createElement('h1');
        heading.innerText = listTitle;
        heading.style.textAlign = 'center';
        wrapper.appendChild(heading);

        tables.forEach(table => {
          const clonedTable = table.cloneNode(true);
          removeActionColumn(clonedTable);
          wrapper.appendChild(clonedTable);
        });

        document.body.appendChild(wrapper);

        html2canvas(wrapper).then(canvas => {
          const imgData = canvas.toDataURL('image/png');
          const pdf = new jspdf.jsPDF('p', 'mm', 'a4');
          const imgProps = pdf.getImageProperties(imgData);
          const pdfWidth = pdf.internal.pageSize.getWidth();
          const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

          pdf.addImage(imgData, 'PNG', 0, 10, pdfWidth, pdfHeight);
          pdf.save(`${listTitle}.pdf`);
          document.body.removeChild(wrapper);
        });
      });
    }
  });
</script>


</body>

</html>
