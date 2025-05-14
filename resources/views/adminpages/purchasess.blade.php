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
                    <a class="user" href="/admin/purchase_list" onclick="loadpurchasePage(); return false;">Back</a>
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
                            <label for="defaultSelect">Vendors</label>
                            <select class="form-select form-control" id="vendors" name="vendors">
                              <option>Choose Vendor</option>
                              @foreach($vendors as $vendor)
                                <option>{{$vendor->name}}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                          <div class="form-group">
                            <label for="invoice_no">Invoice No</label>
                            <input class="form-control" type="text" id="invoice_no" name="invoice_no" value="Invoice-{{ rand(1000, 9999) }}">
                            <span id="nameError" class="text-danger"></span>
                          </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                          <div class="form-group">
                              <label for="invoice_date">Invoice Date</label>
                              <input type="date" id="from_date" name="created_at" class="form-control" value="{{ request('from_date') }}">
                              <span id="nameError" class="text-danger"></span>
                          </div>
                      </div>
                      

                        <div class="col-md-12 col-lg-8">
                          <div class="form-group">
                            <label for="remarks">Remarks</label>
                            <input class="form-control" type="text" id="remarks" name="remarks" placeholder="Remarks">
                            <span id="nameError" class="text-danger"></span>
                          </div>
                        </div>

                        <div class="table-responsive mt-3">
                          <table class="table table-bordered" id="productTable">
                            <thead>
                              <tr>
                                <th style="background-color: #1a2035; color: white;">Product</th>
                                <th style="background-color: #1a2035; color: white;">Qty</th>
                                <th style="background-color: #1a2035; color: white;">Purchase Rate</th>
                                <th style="background-color: #1a2035; color: white;">Retail Rate</th>
                                <th style="background-color: #1a2035; color: white;">UPR</th>
                                <th style="background-color: #1a2035; color: white;">URR</th>
                                <th style="background-color: #1a2035; color: white; text-align: center;">
                                  <button type="button" class="btn btn-sm btn-light rowmaker" onclick="addRow()">+</button>
                                </th>
                              </tr>
                            </thead>
                            <tbody id="tableBody">
                              <tr>
                                <td style="min-width: 270px; max-width: 300px;">
                                  <select class="form-select form-control" name="products[]" onchange="updateProductData(this)">
                                      <option>Choose Product</option>
                                      @foreach($products as $product)
                                          <option value="{{ $product->id }}" 
                                              data-carton-qty="{{ $product->quantity }}"
                                              data-retail-rate="{{ $product->retail_rate }}"
                                              data-purchase-rate="{{ $product->purchase_rate }}">
                                              {{ $product->item_name }}
                                          </option>
                                      @endforeach
                                  </select>
                              </td>
                              <td style="min-width: 120px; max-width: 120px;">
                                <input type="number"  name="quantity[]" class="form-control" oninput="updateSingleRates(this); calculateTotals();">
                              </td>
                              <td style="min-width: 120px; max-width: 120px;">
                                  <input type="number" name="purchase_rate[]" class="form-control" oninput="calculateTotals(); updateSingleRates(this)">
                              </td>
                              <td style="min-width: 120px; max-width: 120px;">
                                  <input type="number" name="retail_rate[]" class="form-control" oninput="calculateTotals(); updateSingleRates(this)">
                              </td>
                              <td style="min-width: 120px; max-width: 120px;">
                                  <input type="number" name="single_purchase_rate[]" class="form-control" readonly>
                              </td>
                              <td style="min-width: 120px; max-width: 120px;">
                                  <input type="number" name="single_retail_rate[]" class="form-control" readonly>
                              </td>
                              <td>
                                  <button type="button" class="btn btn-danger btn-sm rowremover" onclick="removeRow(this)">X</button>
                              </td>
                              </tr>
                          </tbody>
                          
                          </table>
                        </div>

                        <div class="col-md-6 col-lg-4">
                          <div class="form-group">
                            <label for="remarks">Total Quantity</label>
                            <input class="form-control" type="number" id="totalQuantity" name="totalquantity" disabled>
                          </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                          <div class="form-group">
                            <label for="remarks">Gross Amount</label>
                            <input type="number" name="gross_amount" class="form-control" id="grossAmount" disabled>
                          </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                          <div class="form-group">
                            <label for="remarks">Discount</label>
                            <input type="number" name="discount" class="form-control" id="discount">
                          </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                          <div class="form-group">
                            <label for="remarks">Net Amount</label>
                            <input type="number" name="net_amount" class="form-control" id="netAmount" disabled>
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
      document.addEventListener('keydown', function(event) {
    if (event.key === "Escape") {
        document.querySelector('.rowmaker').click();
    }
});

    </script>


    <script>
      $(document).ready(function() {
       $('#submitdata').click(function(e) {
           e.preventDefault();
   
           var formData = new FormData($('#productssssform')[0]);
   
           var invoiceDate = $('#from_date').val();
           if (invoiceDate) {
               invoiceDate += " 00:00:00"; 
           }
   
           formData.append('created_at', invoiceDate);
           formData.append('totalquantity', $('#totalQuantity').val());
           formData.append('gross_amount', $('#grossAmount').val());
           formData.append('discount', $('#discount').val());
           formData.append('net_amount', $('#netAmount').val());
   
           $.ajax({
               url: '{{ route("purchase.store") }}',  
               type: 'POST',
               data: formData,
               processData: false,  
               contentType: false,  
               success: function(response) {
                   Swal.fire({
                       title: 'Success!',
                       text: 'Purchase saved successfully!',
                       icon: 'success',
                       confirmButtonText: 'OK'
                      }).then(() => {
                          loadpurchasePage(); 
                      });
                   $('#productssssform')[0].reset();  
               },
               error: function(xhr, status, error) {
                   Swal.fire({
                       title: 'Error!',
                       text: 'Something went wrong: ' + error,
                       icon: 'error',
                       confirmButtonText: 'Try Again'
                   });
               }
           });
       });
   });
   </script>
   

    <script>
      function updateProductData(selectElement) {
          const selectedOption = selectElement.options[selectElement.selectedIndex];
  
          const cartonQty = selectedOption.getAttribute('data-carton-qty');
          const purchaseRate = selectedOption.getAttribute('data-purchase-rate');
          const retailRate = selectedOption.getAttribute('data-retail-rate');
          const singlePurchaseRate = selectedOption.getAttribute('data-single-purchase-rate');
          const singleRetailRate = selectedOption.getAttribute('data-single-retail-rate');
  
          const row = selectElement.closest('tr');
          row.querySelector('[name="purchase_rate[]"]').value = purchaseRate;
          row.querySelector('[name="retail_rate[]"]').value = retailRate;
          row.querySelector('[name="single_purchase_rate[]"]').value = singlePurchaseRate;
          row.querySelector('[name="single_retail_rate[]"]').value = singleRetailRate;
  
          row.querySelector('[name="quantity[]"]').value = cartonQty; 
  
          calculateTotals();
      }
  
      function calculateTotals() {
          let totalQuantity = 0;
          let grossAmount = 0;
          let discount = parseFloat(document.getElementById('discount').value) || 0;
          let netAmount = 0;
  
          const rows = document.querySelectorAll('#tableBody tr');
          rows.forEach((row) => {
              const quantity = parseFloat(row.querySelector('[name="quantity[]"]').value) || 0;
              const purchaseRate = parseFloat(row.querySelector('[name="purchase_rate[]"]').value) || 0;
              const retailRate = parseFloat(row.querySelector('[name="retail_rate[]"]').value) || 0;
  
              totalQuantity += quantity;
              grossAmount += retailRate;  
              const singlePurchaseRate = purchaseRate / (quantity || 1);
              const singleRetailRate = retailRate / (quantity || 1);
  
              row.querySelector('[name="single_purchase_rate[]"]').value = singlePurchaseRate;
              row.querySelector('[name="single_retail_rate[]"]').value = singleRetailRate;
          });
  
          netAmount = grossAmount - discount;
  
          document.getElementById('totalQuantity').value = totalQuantity;
          document.getElementById('grossAmount').value = grossAmount;
          document.getElementById('netAmount').value = netAmount;
      }
  
      function addRow() {
          const tableBody = document.getElementById('tableBody');
          const newRow = document.createElement('tr');
  
          newRow.innerHTML = `
              <td style="min-width: 270px; max-width: 300px;">
                  <select class="form-select form-control" name="products[]" onchange="updateProductData(this)">
                      <option>Choose Product</option>
                      @foreach($products as $product)
                          <option value="{{ $product->id }}" 
                              data-carton-qty="{{ $product->quantity }}"
                              data-retail-rate="{{ $product->retail_rate }}"
                              data-purchase-rate="{{ $product->purchase_rate }}">
                              {{ $product->item_name }}
                          </option>
                      @endforeach
                  </select>
              </td>
              <td style="min-width: 120px; max-width: 120px;">
<input type="number" name="quantity[]" min="1" class="form-control" oninput="updateSingleRates(this); calculateTotals();">
              </td>
              <td style="min-width: 120px; max-width: 120px;">
                  <input type="number" name="purchase_rate[]" class="form-control" oninput="calculateTotals(); updateSingleRates(this)">
              </td>
              <td style="min-width: 120px; max-width: 120px;">
                  <input type="number" name="retail_rate[]" class="form-control" oninput="calculateTotals(); updateSingleRates(this)">
              </td>
              <td style="min-width: 120px; max-width: 120px;">
                  <input type="number" name="single_purchase_rate[]" class="form-control" readonly>
              </td>
              <td style="min-width: 120px; max-width: 120px;">
                  <input type="number" name="single_retail_rate[]" class="form-control" readonly>
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
  
      function updateSingleRates(inputElement) {
    const row = inputElement.closest('tr');  
    const quantity = parseFloat(row.querySelector('[name="quantity[]"]').value) || 1;

    const singlePurchaseRate = parseFloat(row.querySelector('[name="single_purchase_rate[]"]').value) || 0;
    const singleRetailRate = parseFloat(row.querySelector('[name="single_retail_rate[]"]').value) || 0;

    console.log('singlePurchaseRate:', singlePurchaseRate);
    console.log('singleRetailRate:', singleRetailRate);
    console.log('quantity:', quantity);

    const updatedPurchaseRate = singlePurchaseRate * quantity;
    const updatedRetailRate = singleRetailRate * quantity;

    row.querySelector('[name="purchase_rate[]"]').value = updatedPurchaseRate.toFixed(2);  
    row.querySelector('[name="retail_rate[]"]').value = updatedRetailRate.toFixed(2); 

    console.log('Updated purchase_rate:', updatedPurchaseRate);
    console.log('Updated retail_rate:', updatedRetailRate);

    row.querySelector('[name="single_purchase_rate[]"]').value = (updatedPurchaseRate / quantity).toFixed(2);  
    row.querySelector('[name="single_retail_rate[]"]').value = (updatedRetailRate / quantity).toFixed(2); 

    calculateTotals();
}


  
      function calculateNetAmount() {
          const grossAmount = parseFloat(document.getElementById('grossAmount').value) || 0;
          const discount = parseFloat(document.getElementById('discount').value) || 0;
  
          const netAmount = grossAmount - discount;
          document.getElementById('netAmount').value = netAmount.toFixed(2);
      }
  
      document.addEventListener('DOMContentLoaded', function() {
          document.getElementById('discount').addEventListener('input', function() {
              calculateNetAmount();
          });
          calculateTotals(); 
      });
  </script>
  
  
  
      
      
  </body>
</html>