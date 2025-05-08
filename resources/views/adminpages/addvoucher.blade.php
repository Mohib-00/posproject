<!DOCTYPE html>
<html lang="en">
  <head>
    @include('adminpages.css')
    <style>
      .user {
        padding: 8px 16px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
        margin-left: auto;
      }

      .user:hover {
        background-color: #45a049;
      }

      .custom-dropdown {
        position: relative;
        width: 100%;
      }

      .dropdown-selected {
        padding: 10px;
        border: 1px solid #ccc;
        cursor: pointer;
        background: #fff;
      }

      .dropdown-list {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        border: 1px solid #ccc;
        background: white;
        max-height: 200px;
        overflow-y: auto;
        display: none;
        z-index: 1000;
      }

      .dropdown-list.show {
        display: block;
      }

      .dropdown-search {
        width: 100%;
        box-sizing: border-box;
        padding: 5px 10px;
        border: none;
        border-bottom: 1px solid #ccc;
      }

      .dropdown-item {
        padding: 10px;
        cursor: pointer;
      }

      .dropdown-item:hover {
        background-color: #f0f0f0;
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
                  <div class="card-header">
                    <a class="user" href="/admin/voucher" onclick="loadvoucher(); return false;">Back</a>
                  </div>
                  <form id="productssssform">
                    <div class="card-body">
                      <div class="row">

                        <div class="col-md-6 col-lg-4">
                          <div class="form-group">
                            <label for="defaultSelect">Receiving Location*</label>
                            <select class="form-select form-control" id="receiving_location" name="receiving_location">
                              <option>Head Office</option>
                            </select>
                          </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                          <div class="form-group">
                            <label for="defaultSelect">Voucher Type</label>
                            <select class="form-select form-control" id="vendors" name="vendors">
                              <option>Cash Payment</option>
                              <option>Cash Receipt</option>
                            </select>
                          </div>
                        </div>

                       

                        <div class="col-md-6 col-lg-4">
                          <div class="form-group">
                              <label for="invoice_date">Voucher Date</label>
                              <input type="date" id="from_date" name="created_at" class="form-control" >
                              <span id="nameError" class="text-danger"></span>
                          </div>
                      </div>

                      <div class="col-md-6 col-lg-4">
                        <div class="form-group">
                          <label for="defaultSelect">Users Form</label>
                          <select class="form-select form-control" id="vendors" name="vendors">
                            <option>User 1</option>
                            <option>User 2</option>
                            <option>User 3</option>
                            <option>User 4</option>
                            <option>User 5</option>
                          </select>
                        </div>
                      </div>
                      

                        <div class="col-md-12 col-lg-8">
                          <div class="form-group">
                            <label for="remarks">Narration</label>
                            <input class="form-control" type="text" id="remarks" name="remarks" placeholder="Remarks">
                            <span id="nameError" class="text-danger"></span>
                          </div>
                        </div>

                        <div class="table-responsive mt-3">
                          <table class="table table-bordered" id="productTable">
                            <thead>
                              <tr>
                                <th style="background-color: #FFA500; color: white;">Account</th>
                                <th style="background-color: #FFA500; color: white;">Balance</th>
                                <th style="background-color: #FFA500; color: white;">Narration</th>
                                <th style="background-color: #FFA500; color: white;">Amount</th>
                                <th style="background-color: #FFA500; color: white;">
                                  <button type="button" class="btn btn-sm btn-light" onclick="addRow()">+</button>
                                </th>
                              </tr>
                            </thead>
                            <tbody id="tableBody">
                              <tr>
                                <td style="min-width: 270px; max-width: 300px;">
                                  <select class="form-select form-control" name="products[]" onchange="updateProductData(this)">
                                      <option>Choose One</option>
                                      {{--@foreach($products as $product)
                                          <option value="{{ $product->id }}" 
                                              data-carton-qty="{{ $product->quantity }}"
                                              data-retail-rate="{{ $product->retail_rate }}"
                                              data-purchase-rate="{{ $product->purchase_rate }}">
                                              {{ $product->item_name }}
                                          </option>
                                      @endforeach--}}
                                      <option>1</option>
                                      <option>2</option>
                                      <option>3</option>
                                  </select>
                              </td>
                              <td style="min-width: 120px; max-width: 120px;">
                                <input type="number"  name="balance[]" class="form-control" id="balance" disabled>
                              </td>
                              <td style="min-width: 120px; max-width: 120px;">
                                  <input type="number" name="narration[]" class="form-control" id="narration">
                              </td>
                              <td style="min-width: 120px; max-width: 120px;">
                                  <input type="number" name="amount[]" class="form-control" id="amount">
                              </td>
                            
                              <td>
                                  <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">X</button>
                              </td>
                              </tr>
                          </tbody>
                          
                          </table>
                        </div>

                        <div class="col-md-6 col-lg-4">
                          <div class="form-group">
                            <label for="remarks">Total </label>
                            <input class="form-control" type="number" id="total" name="total" disabled>
                          </div>
                        </div>

                       

                      </div>
                    </div>
                    <div class="card-action">
                      <a id="submitdata" class="btn btn-success">Submit</a>
                    </div>
                  </form>
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


   

    <script>
     
  
      function addRow() {
          const tableBody = document.getElementById('tableBody');
          const newRow = document.createElement('tr');
  
          newRow.innerHTML = `
              <td style="min-width: 270px; max-width: 300px;">
                  <select class="form-select form-control" name="products[]" onchange="updateProductData(this)">
                      <option>Choose Product</option>
                      <option>1</option>
                      <option>2</option>
                      <option>3</option>
                  </select>
              </td>
             <td style="min-width: 120px; max-width: 120px;">
                <input type="number"  name="balance[]" class="form-control" id="balance" disabled>
             </td>
             <td style="min-width: 120px; max-width: 120px;">
                <input type="number" name="narration[]" class="form-control" id="narration">
             </td>
             <td style="min-width: 120px; max-width: 120px;">
                <input type="number" name="amount[]" class="form-control" id="amount">
             </td>
              <td>
                  <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">X</button>
              </td>
          `;
  
          tableBody.appendChild(newRow);
      }
  
      function removeRow(button) {
          const row = button.closest('tr');
          row.remove();
          calculateTotals(); 
      }
  

  </script>
  
  
  
      
      
  </body>
</html>