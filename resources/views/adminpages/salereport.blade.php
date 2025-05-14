<!DOCTYPE html>
<html lang="en">
  <head>
   @include('adminpages.css')
   <style>
    .card-header {
        display: flex;
        align-items: center;
    }

    .addemployee {
        padding: 8px 16px;
        background-color: #4CAF50;
        color: white;            
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
        margin-left: auto;
    }

    .addemployee:hover {
        background-color: #45a049;  
    }


.custom-modal.employee, 
.custom-modal.employeeedit {
    position: fixed;
    z-index: 1050;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;              
    justify-content: center;   
    align-items: center; 
}


    .modal-dialog {
        max-width: 800px;
        animation: slideDown 0.5s ease;
    }

  
    @keyframes fadeIn {
        0% { opacity: 0; }
        100% { opacity: 1; }
    }

    @keyframes slideDown {
        0% { transform: translateY(-50px); opacity: 0; }
        100% { transform: translateY(0); opacity: 1; }
    }

    .modal-content {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        width: 100%;
        height: auto;
        text-align: center;
    }

    /* Add a border and styling to the table */
.styled-table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.styled-table th,
.styled-table td {
    padding: 12px 20px;
    text-align: center;
    font-size: 1rem;
    border: 1px solid #ddd;
}

.styled-table th {
    background-color: #f4f4f9;
    color: #333;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.styled-table td {
    background-color: #fff;
    color: #333;
}

.styled-table tr:nth-child(even) {
    background-color: #f9f9f9;
}

.styled-table tr:hover {
    background-color: #f1f1f1;
}

.styled-table .editable {
    font-weight: bolder;
    color:black;
    cursor: pointer;
}

.styled-table tfoot td {
    font-weight: bold;
    background-color: #eaeaea;
}

.styled-table tfoot {
    font-size: 1.1rem;
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
       
              <div class="row">
                  <div class="col-md-12">
                      <div class="card">

                        <form method="GET" action="{{ route('sales.search') }}" class="row g-3 p-4">
                            <div class="col-md-3">
                                <label for="from_date" class="form-label">From Date</label>
                                <input type="date" id="from_date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="to_date" class="form-label">To Date</label>
                                <input type="date" id="to_date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                            </div>
                           
                            <div class="col-md-3 align-self-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search"></i> Search
                                </button>
                            </div>
                        </form>
  
                        <div class="card-header d-flex justify-content-between align-items-center">
                          
                          <div>
                              <button class="btn btn-sm btn-outline-primary me-2 print-table" >
                                  <i class="fas fa-print"></i> Print
                              </button>
                      
                              <button class="btn btn-sm btn-outline-danger export-pdf">
                                  <i class="fas fa-file-pdf"></i> PDF
                              </button>
                              <button class="btn btn-sm btn-outline-primary export-excel">
                                <i class="fas fa-file-pdf"></i> Excel
                            </button>
                          </div>
                      
                         
                      </div>
                      
                      <h1 class="mx-3 list">Sale Report</h1>
  
                        <div class="card-body">
                          <div class="table-responsive">
                              {{--<table id="add-row" class="display table table-striped table-hover">
                                  <thead>
                                      <tr>
                                          <th>#</th>
                                          <th>Invoice #</th>
                                          <th>User</th>
                                          <th>Item</th> 
                                          <th>Customer</th>
                                          <th>Sale Type</th>
                                          <th>Date</th>
                                          <th>Quantity</th>
                                                                              
                                          <th>Unit Cost</th>
                                          <th>Total</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                       @php $counter = 1; @endphp
                                       @foreach($sales as $sale)
                                       @foreach($sale->saleItems as $item)
                                        <tr class="user-row">
                                        <td>{{ $counter }}</td>
                                        <td>{{ $sale->ref }}</td>
                                        <td style="white-space: nowrap">{{ $sale->user->name ?? 'No User' }}</td>
                                        <td style="white-space: nowrap">{{ $item->product_name }}</td>
                                        <td style="white-space: nowrap">{{ $sale->customer_name }}</td>
                                        <td>{{ $sale->sale_type }}</td>
                                        <td style="white-space: nowrap">{{ $sale->created_at->format('Y-m-d') }}</td>
                                        <td>{{ $item->product_quantity }}</td>
                                      
                                        <td>{{$item->product_rate}}</td>
                                        <td>{{$item->product_subtotal}}</td>
                                        </tr>
                                       @php $counter++; @endphp
                                       @endforeach
                                       @endforeach

                                  </tbody>
                                  
                              </table>--}}

                              <table id="add-row" class="display table table-striped table-hover">
    <thead>
        <tr>
            <th>#</th>
            <th>Customer</th>
            <th>Qty</th>
            <th>Gross Amount</th>
            <th>Discount</th>
            <th>Fixed Discount</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @php 
            $counter = 1; 
              $totalNetAmount = 0;
                                      $totalFixedDiscount = 0;
                                      $totalDiscount = 0;
                                      $totalSaleReturn = 0;
                                      $totalCredit = 0;
                                      $totalCash = 0;
        @endphp

        @foreach($sales as $sale)
            <tr class="user-row">
                <td>{{ $counter }}</td>
                <td style="white-space: nowrap">{{ $sale->customer_name }}</td>
                <td>{{ $sale->total_items }}</td>
                <td>{{ $sale->total }}</td>
                <td>{{ $sale->discount }}</td>
                <td>{{ $sale->fixed_discount }}</td>
                <td>{{ $sale->subtotal }}</td>
            </tr>

           @php
                                          $counter++;
                                          $totalNetAmount += $sale->total;
                                          $totalFixedDiscount += $sale->fixed_discount;
                                          $totalDiscount += $sale->discount;
                                          $totalSaleReturn += $sale->sale_return;
                                          $totalCash += $sale->subtotal;
                                      @endphp
        @endforeach
    </tbody>

    <tfoot>
        <tr>
            <th colspan="3">Total</th>
            <th>{{ number_format($totalNetAmount, 2) }}</th>
            <th>{{ number_format($totalDiscount, 2) }}</th>
            <th>{{ number_format($totalFixedDiscount, 2) }}</th>
            <th>{{ number_format($totalCash, 2) }}</th>
        </tr>
    </tfoot>
</table>
<table class="styled-table">
    <thead>
        <tr>
            <th>Report</th>
            <th>Amount</th>
            <th>Net Total</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="font-weight: bolder">Net Sale</td>
            <td class="editable">Rs: {{ number_format($totalCash, 2) }}</td>
            <td class="editable">Rs: {{ number_format($totalCash, 2) }}</td>
        </tr>
        <tr>
            <td style="font-weight: bolder">Sale Return</td>
            <td class="editable">Rs: {{ number_format($totalSaleReturn, 2) }}</td>
            <td class="editable">Rs: {{ number_format($totalCash - $totalSaleReturn, 2) }}</td>
        </tr>
        <tr>
            <td style="font-weight: bolder">Credit</td>
            <td class="editable">
                Rs: 
                @php
                    $totalCreditSubtotal = $sales->where('sale_type', 'credit')->sum('subtotal');
                @endphp
                {{ number_format($totalCreditSubtotal, 2) }}
            </td>
            <td class="editable">
        Rs: {{ number_format(abs(($totalCash - $totalSaleReturn) - $totalCreditSubtotal), 2) }}
    </td>
        </tr>
    </tbody>
</table>

                              
                              
                          </div>
                        </div>
                      </div>
                    </div>
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
