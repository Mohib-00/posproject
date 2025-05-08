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
    .add-voucher-btn {
    background-color: #007bff;
    border: none;
    padding: 8px 16px;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 5px;
  }

  .add-voucher-btn:hover {
    background-color: #0056b3;
  }

  .add-voucher-btn i {
    font-size: 14px;
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

                        <form action="{{ route('sales.list') }}" method="GET" class="row g-2 p-3 d-flex align-items-end">
                          <div class="col-md-2">
                              <label for="from_date" class="form-label">From Date</label>
                              <input type="date" id="from_date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                          </div>
                      
                          <div class="col-md-2">
                              <label for="to_date" class="form-label">To Date</label>
                              <input type="date" id="to_date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                          </div>
                      
                      
                          <div class="col-md-2">
                              <button type="submit" class="btn btn-primary w-100">
                                  <i class="fas fa-search"></i> Search
                              </button>
                          </div>
                      </form>
                      
                      
  
                      <div class="card-header d-flex justify-content-between align-items-center">
  
                        <div class="d-flex">
                          <button class="btn btn-sm btn-outline-primary me-2 print-table">
                            <i class="fas fa-print"></i> Print
                          </button>
                      
                          <button class="btn btn-sm btn-outline-danger me-2 export-pdf">
                            <i class="fas fa-file-pdf"></i> PDF
                          </button>
                      
                          <button class="btn btn-sm btn-outline-primary me-2 export-excel">
                            <i class="fas fa-file-excel"></i> Excel
                          </button>
                        </div>
                      
                        <a href="/admin/add_voucher" onclick="loadvoucherPage(); return false;" class="btn btn-primary add-voucher-btn ms-auto">
                          <i class="fa fa-plus me-1"></i> Add Voucher
                        </a>
                      </div>
                    
  
                        <div class="card-body">
                          <div class="table-responsive">
                            <table id="add-row" class="display table table-striped table-hover">
                              <thead>
                                  <tr>
                                    <th>#</th>
                                    <th>Id</th>
                                    <th>Narration</th>
                                    <th>Payment Type</th> 
                                    <th>Accounts</th>
                                    <th>Total Amount</th>
                                    <th>Veoucher Status</th>
                                    <th>Created By</th>
                                    <th>Created Date</th>
                                    <th>Action</th>
                                  </tr>
                              </thead>
                              <tbody>
                               
                                  {{--@foreach($vouchers as $voucher)
                                      <tr class="user-row sale-row" id="sale-{{ $sale->id }}">
                                          <td>{{ $counter }}</td>
                                          <td>{{ $sale->id }}</td>
                                          <td>{{ $sale->ref }}</td>
                                          <td>{{ $sale->customer_name }}</td>
                                          <td>{{ $sale->payment_type ?? 'N/A' }}</td>
                                          <td>{{ $sale->subtotal }}</td>
                                          <td>{{ $sale->fixed_discount }}</td>
                                          <td>{{ $sale->discount }}</td>
                                          <td>{{ $sale->sale_return }}</td>
                                          <td>{{ $sale->sale_type == 'credit' ? $sale->subtotal : '' }}</td>
                                          <td>{{ $sale->sale_type == 'cash' ? $sale->subtotal : '' }}</td>
                                          <td>{{ $sale->user->name ?? 'Unknown' }}</td>
                                          <td>{{ $sale->status ?? 'Pending' }}</td>
                                          <td style="white-space: nowrap;">{{ $sale->created_at->format('d-m-Y ') }}</td>
                                          <td>
                                              <div class="form-button-action" style="display: flex; gap: 8px; align-items: center;">
                                                  <a data-sale-id="{{ $sale->id }}" onclick="loadeditSalePage(this)" class="btn btn-link btn-primary btn-lg edit-sale-btn">
                                                      <i class="fa fa-edit"></i>
                                                  </a>
                                                  <a data-sale-id="{{ $sale->id }}" onclick="loadsaleinvoicePage(this)" class="btn btn-link btn-primary btn-lg invoicepage">
                                                      <i style="color: purple" class="fa fa-eye"></i>
                                                  </a>
                                                  <a data-sale-id="{{ $sale->id }}" onclick="loadsaleprintPage(this)" class="btn btn-link btn-primary btn-lg saleprintpage">
                                                      <i style="color:darkred" class="fas fa-print"></i>
                                                  </a>
                                                  <a data-sale-id="{{ $sale->id }}" class="btn btn-link btn-danger delsale mt-2">
                                                      <i class="fa fa-times"></i>
                                                  </a>
                                                  @if ($sale->status == 'pending')
                                                  <a data-sale-id="{{ $sale->id }}" data-customer-name="{{ $sale->customer_name }}" data-subtotal="{{ $sale->subtotal }}" class="btn btn-sm completesale d-inline-flex align-items-center px-2 py-1 text-white" style="background-color: #dc3545;" data-bs-toggle="modal" data-bs-target="#completeSaleModal">
                                                      <i class="fas fa-check-circle me-1"></i> Complete
                                                  </a>
                                                  @endif
                                                  <a data-sale-id="{{ $sale->id }}" class="btn btn-sm salereturn d-inline-flex align-items-center px-2 py-1 text-white" style="background-color: #007bff; white-space: nowrap;">
                                                      <i class="fas fa-undo-alt me-1"></i> Sale Return
                                                  </a>
                                              </div>
                                          </td>
                                      </tr>
                                     
                                  @endforeach--}}
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
