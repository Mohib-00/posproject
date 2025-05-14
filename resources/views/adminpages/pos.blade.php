<!DOCTYPE html>
<html lang="en">
  <head>
   @include('adminpages.css')
   <style>
    .modal-content {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        text-align: center;
        width: 100%;
        max-width: 800px; 
        animation: slideDown 0.5s ease;
    }

    .modal-dialog {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        margin: 0;
        padding: 0;
    }

    @media (max-width: 767px) {
        .modal-dialog {
            max-width: 90%; 
        }

        .modal-content {
            padding: 15px;
        }
    }

    @media (max-width: 480px) {
        .modal-content {
            padding: 10px;
        }
    }
</style>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


  </head>
  <body>
    <div class="wrapper">
    @include('adminpages.sidebar')

      <div class="main-panel">
        @include('adminpages.header')

        <div class="container">
            <div class="page-inner">
             
              <form id="saleForm">
                <div class="row">
                    <div class="col-md-12">
                      <div class="card card-round">
                        <div class="card-header">
                          <div class="row">
                            <!-- Clients Form -->
                            <div class="col-12 col-md-2 mb-3">
                              <label for="customerSelect">Choose Employee</label>
                              <select class="form-select form-select-sm" id="customerSelect" name="employee">
                                <option value="1">All</option>
                                @foreach ($users as $user)
                                  <option value="{{ $user->name }}">{{ $user->name }}</option>
                                @endforeach
                              </select>
                            </div>
                  
                            <!-- Choose a Customer -->
                            <div class="col-12 col-md-6 mb-3">
                              <label for="smallSelect">Choose a Customer</label>
                              <select class="form-select form-select-sm" id="smallSelect" name="customer_name">
                                <option value="1">All</option>
                                @foreach ($customers as $customer)
                                  <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
                                @endforeach
                              </select>
                            </div>
                  
                            <!-- Date -->
                            <div class="col-12 col-md-2 mb-3">
                              <label for="dateInput">Date</label>
                              <input class="form-control form-control-sm" type="date" name="created_at" id="dateInput"/>
                            </div>
                  
                            <!-- Ref# -->
                            <div class="col-12 col-md-2 mb-3">
                              <label for="refInput">Ref#</label>
                              <input class="form-control form-control-sm" type="text" name="ref" id="refInput"/>
                            </div>

                            <div class="col-8 col-md-8 mb-3">
                              <label for="productSelect">Search Item</label>
                              <select class="form-select form-select-sm" id="productSelect">
                                <option value="">Select Item</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->item_name }}</option>
                                @endforeach
                            </select>
                            </div>

                            <div class="col-2 col-md-2 mb-3 d-flex align-items-center">
                              <label style="white-space: nowrap" class="me-2 mb-0">Product Name:</label>
                              <input class="form-control form-control-sm" type="text" id="productName" readonly/>
                            </div>
                            
                            <div class="col-2 col-md-2 mb-3 d-flex align-items-center">
                              <label style="white-space: nowrap" class="me-2 mb-0">Product Quantity:</label>
                              <input class="form-control form-control-sm" type="text" id="qty" readonly/> 
                            </div>
                            
                            
                          

                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                
                  <div class="row">

                    <div class="col-md-9">
                      <div class="card card-round">
                        <div class="card-header">
                          <div class="card-head-row card-tools-still-right">
                          </div>
                        </div>
                        <div class="card-body p-0">
                          <div class="table-responsive">
                            <table class="table align-items-center mb-0" id="productTable">
                              <thead class="thead-light">
                                <tr>
                                  <th scope="col">Name</th>
                                  <th scope="col">Quantity</th>
                                  <th scope="col">Purchase Rate</th>
                                  <th scope="col">Retail Rate</th>
                                  <th scope="col">Sub-Total</th>
                                  <th scope="col">Delete</th>
                                </tr>
                              </thead>
                              <tbody>
                              </tbody>
                              <tfoot>
                                <tr>
                                  <td colspan="3" class="text-end fw-bold">Total Items</td>
                                  <td class=" fw-bold" >
                                    <input type="number" id="totalItems"  name="total_items" class="form-control form-control-sm text-end fw-bold" style="width: fit-content" disabled>
                                  </td>
                                </tr>
                                <tr>
                                  <td colspan="3" class="text-end fw-bold">Total</td>
                                  <td class=" fw-bold" >
                                    <input type="number" id="totalAmount" name="total" class="form-control form-control-sm text-end fw-bold" style="width: fit-content;" disabled>

                                  </td>
                                </tr>
                              </tfoot>
                            </table>
                            
                          </div>
                        </div>
                      </div>
                    </div>
                  
                    <!-- Right Side Panel -->
                    <div class="col-md-3">
                      <div class="card card-round shadow-sm">
                        <div class="card-body">
                    
                          <!-- Sale Type Section -->
                          <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="fw-bold" style="font-size: 16px;">Sale Type</div>
                            <select class="form-select form-select-sm flex-grow-1 ms-2" name="sale_type" id="saleTypeSelect" style="min-width: 100px; max-width: 180px; border-radius: 8px;">
                              <option value="1">Cash</option>
                              <option value="2">Credit</option>
                            </select>
                          </div>
                    
                          <!-- Payment Type Section -->
                          <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="fw-bold" style="font-size: 16px;">Payment Type</div>
                            <select class="form-select form-select-sm flex-grow-1 ms-2" name="payment_type" id="paymentTypeSelect" style="min-width: 100px; max-width: 180px; border-radius: 8px;">
                              <option value="1">Cash</option>
                              <option value="2">Bank</option>
                            </select>
                          </div>
                    
                          <hr>
                    
                          <!-- Discount Section -->
                          <div class="d-flex justify-content-between align-items-center py-2">
                            <div class="fw-bold" style="font-size: 16px;">Discount</div>
                            <input class="form-control form-control-sm flex-grow-1 ms-2" type="text" name="discount" id="discount" value="0" style="min-width: 100px; max-width: 180px; border-radius: 8px;" />
                          </div>
                    
                          <!-- Amount After Discount Section -->
                          <div class="d-flex justify-content-between align-items-center py-2">
                            <div class="fw-bold" style="font-size: 16px;">Amount After Discount</div>
                            <input class="form-control form-control-sm flex-grow-1 ms-2" type="number" name="amount_after_discount" id="amountafterdiscount" value="0" style="min-width: 100px; max-width: 180px; border-radius: 8px;" disabled />
                          </div>
                    
                          <hr>
                    
                          <!-- Fixed Discount Section -->
                          <div class="d-flex justify-content-between align-items-center py-2">
                            <div class="fw-bold" style="font-size: 16px;">Fixed Discount</div>
                            <input class="form-control form-control-sm flex-grow-1 ms-2" type="number" name="fixed_discount" id="fixeddiscount" value="0" style="min-width: 100px; max-width: 180px; border-radius: 8px;" disabled />
                          </div>
                    
                          <!-- Amount After Fixed Discount Section -->
                          <div class="d-flex justify-content-between align-items-center py-2">
                            <div class="fw-bold" style="font-size: 16px;">Amount After Fix-Discount</div>
                            <input class="form-control form-control-sm flex-grow-1 ms-2" type="number" name="amount_after_fix_discount" id="amountafterfixdiscount" value="0" style="min-width: 100px; max-width: 180px; border-radius: 8px;" disabled />
                          </div>
                    
                          <hr>
                    
                          <!-- Total Rs Section -->
                          <div class="d-flex justify-content-between align-items-center py-2">
                            <div class="fw-bold" style="font-size: 16px;">Total Rs:</div>
                            <input class="form-control form-control-sm flex-grow-1 ms-2" type="number" name="subtotal" id="total" value="0" style="min-width: 100px; max-width: 180px; border-radius: 8px;" disabled />
                          </div>
                    
                          <hr>
                    
                          <!-- Submit Button -->
                          <div class="d-flex justify-content-center mt-3">
                            <button type="submit" class="btn btn-primary w-100" style="border-radius: 8px;">Submit</button>
                          </div>
                    
                        </div>
                      </div>
                    </div>
                  
                  </div>
              </form>
                  
              </div>
        </div>

        @include('adminpages.footer')
      </div>
    </div>

    



    @include('adminpages.js')
    @include('adminpages.ajax')


    <script>
      $('#customerSelect').on('change', function () {
  var userName = $(this).val();

  $.ajax({
    url: '/get-customers-by-username/' + userName,
    type: 'GET',
    success: function (response) {
      var customers = response.customers;

      var $select = $('#smallSelect');
      $select.empty();
      $select.append('<option value="1">All</option>');

      customers.forEach(function (customer) {
        $select.append(`<option value="${customer.id}">${customer.customer_name}</option>`);
      });

      $('#fixeddiscount').val('');
      $('#fixedDiscountSection').hide();
    },
    error: function (xhr) {
      console.error('Error fetching customers:', xhr);
    }
  });
});


$('#smallSelect').on('change', function () {
  var customerId = $(this).val();

  if (customerId !== '1') {
    $.ajax({
      url: '/get-customer-discount/' + customerId,
      type: 'GET',
      success: function (response) {
        if (response.fixed_discount !== null) {
          $('#fixeddiscount').val(response.fixed_discount); 
          $('#fixedDiscountSection').show();
        } else {
          $('#fixeddiscount').val('');
          $('#fixedDiscountSection').hide();
        }

        updateTotals();
      },
      error: function (xhr) {
        console.error('Error fetching discount:', xhr);
      }
    });
  } else {
    $('#fixeddiscount').val('');
    $('#fixedDiscountSection').hide();
    updateTotals(); 
  }
});



function updateTotals() {
      let totalItems = 0;
      let totalAmount = 0;

      $('#productTable tbody tr').each(function () {
        const quantity = parseInt($(this).find('.quantity-input').val()) || 0;
        const subtotal = parseFloat($(this).find('.subtotal-input').val()) || 0;

        totalItems += quantity;
        totalAmount += subtotal;
      });

      $('#totalItems').val(totalItems);
      $('#totalAmount').val(totalAmount.toFixed(2));

      const discountPercentage = parseFloat($('#discount').val()) || 0;
      const fixedDiscount = parseFloat($('#fixeddiscount').val()) || 0;

      const amountAfterDiscount = totalAmount - discountPercentage;
      $('#amountafterdiscount').val(amountAfterDiscount.toFixed(2));

      const amountAfterFixDiscount = amountAfterDiscount - fixedDiscount;
      $('#amountafterfixdiscount').val(amountAfterFixDiscount.toFixed(2));

      $('#total').val(amountAfterFixDiscount.toFixed(2));
    }


    </script>
    


    <script>
      $(document).ready(function() {
          $('#productSelect').select2({
              placeholder: "Search and select an item",
              allowClear: true
          });
      });
  </script>

<script>
  $(document).ready(function () {
    $('#productSelect').on('change', function () {
      var productId = $(this).val();
      if (!productId) return;

      $.ajax({
        url: '/get-product-details/' + productId,
        type: 'GET',
        success: function (product) {
          if (product.quantity <= 0) {
            Swal.fire({
              icon: 'warning',
              title: 'Out of Stock',
              text: `The item "${product.item_name}" is currently out of stock.`,
              confirmButtonText: 'OK'
            });
            return;
          }

          $('#qty').val(product.quantity); 
          $('#productName').val(product.item_name);

          var quantity = 1;
          var retailRate = parseFloat(product.retail_rate);
          var purchaseRate = parseFloat(product.purchase_rate);
          var subtotal = quantity * retailRate;

          var existingRow = $('#productTable tbody tr').filter(function () {
            return $(this).find('.item-name-input').val() === product.item_name;
          });

          if (existingRow.length > 0) {
            var currentQuantity = parseInt(existingRow.find('.quantity-input').val());
            var newQuantity = currentQuantity + 1;

            if (newQuantity > product.quantity) {
              Swal.fire({
                icon: 'warning',
                title: 'Stock Limit Reached',
                text: `Only ${product.quantity} units available for "${product.item_name}".`,
              });
              return;
            }

            existingRow.find('.quantity-input').val(newQuantity);
            existingRow.find('.quantity-input').attr('data-stock', product.quantity);

            var rate = parseFloat(existingRow.find('.rate-input').val());
            var newSubtotal = newQuantity * rate;
            existingRow.find('.subtotal-input').val(newSubtotal.toFixed(2));

            var basePurchaseRate = parseFloat(existingRow.find('.purchase-rate-input').attr('data-base-rate'));
            var newPurchaseRate = basePurchaseRate * newQuantity;
            existingRow.find('.purchase-rate-input').val(newPurchaseRate.toFixed(2));

            updateTotals();
          } else {
            $('#productTable tbody').append(`
              <tr>
                <td>
                  <input type="text" name="product_name[]" class="form-control form-control-sm item-name-input" value="${product.item_name}" readonly style="width:150px">
                </td>
                <td>
                  <input type="number" name="product_quantity[]" class="form-control form-control-sm quantity-input" value="${quantity}" min="1" style="text-align:right;width:60px" data-stock="${product.quantity}">
                </td>
                <td>
                  <input type="number" name="purchase_rate[]" class="form-control form-control-sm purchase-rate-input" value="${purchaseRate.toFixed(2)}" min="0" step="0.01" style="text-align:right;width:80px" data-base-rate="${purchaseRate.toFixed(2)}">
                </td>
                <td>
                  <input type="number" name="product_rate[]" class="form-control form-control-sm rate-input" value="${retailRate.toFixed(2)}" min="0" step="0.01" style="text-align:right;width:80px">
                </td>
                <td>
                  <input type="text" name="product_subtotal[]" class="form-control form-control-sm subtotal-input" value="${subtotal.toFixed(2)}" readonly style="text-align:right;width:100px">
                </td>
                <td>
                  <button class="btn btn-icon btn-round btn-danger btn-sm delete-row">
                    <i class="fa fa-trash"></i>
                  </button>
                </td>
              </tr>
            `);
            updateTotals();
          }
        },
        error: function (xhr, status, error) {
          console.error('AJAX error:', error);
        }
      });
    });

      $(document).on('keydown', function(e) {
    if (e.ctrlKey && e.key === 's') {
        e.preventDefault();
        handleSaleFormSubmit(); 
    }
});

document.getElementById('saleForm').addEventListener('submit', function(e) {
    e.preventDefault();
    handleSaleFormSubmit();
});

function handleSaleFormSubmit() {
    let productNames = document.querySelectorAll('[name="product_name[]"]');
    let productQuantities = document.querySelectorAll('[name="product_quantity[]"]');
    let purchaseRates = document.querySelectorAll('[name="purchase_rate[]"]');
    let productRates = document.querySelectorAll('[name="product_rate[]"]');
    let productSubtotals = document.querySelectorAll('[name="product_subtotal[]"]');

    let items = [];

    for (let i = 0; i < productNames.length; i++) {
        let name = productNames[i].value.trim();
        if (name !== '') {
            items.push({
                product_name: name,
                product_quantity: parseInt(productQuantities[i].value),
                purchase_rate: parseFloat(purchaseRates[i].value),
                product_rate: parseFloat(productRates[i].value),
                product_subtotal: parseFloat(productSubtotals[i].value),
            });
        }
    }

    let customerSelect = document.getElementById('smallSelect');
    let customerId = customerSelect.value;
    let customerName = customerSelect.options[customerSelect.selectedIndex].text;

    let formData = {
        employee: document.querySelector('[name="employee"]').value,
        customer_id: customerId,
        customer_name: customerName,
        created_at: document.querySelector('[name="created_at"]').value,
        ref: document.querySelector('[name="ref"]').value,
        sale_type: document.querySelector('[name="sale_type"]').value,
        payment_type: document.querySelector('[name="payment_type"]').value,
        discount: document.querySelector('[name="discount"]').value,
        total_items: document.querySelector('[name="total_items"]').value,
        total: document.querySelector('[name="total"]').value,
        amount_after_discount: document.querySelector('[name="amount_after_discount"]').value,
        fixed_discount: document.querySelector('[name="fixed_discount"]').value,
        amount_after_fix_discount: document.querySelector('[name="amount_after_fix_discount"]').value,
        subtotal: document.querySelector('[name="subtotal"]').value,
        items: items
    };

    fetch('/sales', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(formData)
    })
    .then(response => {
        if (!response.ok) throw new Error("Network response was not ok");
        return response.json();
    })
    .then(data => {
        Swal.fire({
            title: 'Success!',
            text: 'Sale submitted successfully!',
            icon: 'success',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('saleForm').reset(); 
                $('#productTable tbody').empty();
                updateTotals();
            }
        });
    })
    .catch((error) => {
        console.error('Error:', error);
        Swal.fire({
            title: 'Error!',
            text: 'There was an error submitting the sale.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    });
}

    $(document).on('click', '.delete-row', function () {
      $(this).closest('tr').remove();
      updateTotals();
    });

    $(document).on('input', '.quantity-input, .rate-input', function () {
      const $row = $(this).closest('tr');
      let quantity = parseInt($row.find('.quantity-input').val()) || 1;
      let rate = parseFloat($row.find('.rate-input').val()) || 0;
      let stock = parseInt($row.find('.quantity-input').attr('data-stock')) || 0;

      if (quantity > stock) {
        Swal.fire({
          icon: 'warning',
          title: 'Stock Limit Reached',
          text: `Available stock is ${stock}. You cannot exceed this.`,
        });
        quantity = stock;
        $row.find('.quantity-input').val(stock);
      }

      const subtotal = quantity * rate;
      $row.find('.subtotal-input').val(subtotal.toFixed(2));

      const basePurchaseRate = parseFloat($row.find('.purchase-rate-input').attr('data-base-rate')) || 0;
      const newPurchaseRate = basePurchaseRate * quantity;
      $row.find('.purchase-rate-input').val(newPurchaseRate.toFixed(2));

      updateTotals();
    });

    function updateTotals() {
      let totalItems = 0;
      let totalAmount = 0;

      $('#productTable tbody tr').each(function () {
        const quantity = parseInt($(this).find('.quantity-input').val()) || 0;
        const subtotal = parseFloat($(this).find('.subtotal-input').val()) || 0;

        totalItems += quantity;
        totalAmount += subtotal;
      });

      $('#totalItems').val(totalItems);
      $('#totalAmount').val(totalAmount.toFixed(2));

      const discountPercentage = parseFloat($('#discount').val()) || 0;
      const fixedDiscount = parseFloat($('#fixeddiscount').val()) || 0;

      const amountAfterDiscount = totalAmount - discountPercentage;
      $('#amountafterdiscount').val(amountAfterDiscount.toFixed(2));

      const amountAfterFixDiscount = amountAfterDiscount - fixedDiscount;
      $('#amountafterfixdiscount').val(amountAfterFixDiscount.toFixed(2));

      $('#total').val(amountAfterFixDiscount.toFixed(2));
    }

    $('#discount, #fixeddiscount').on('input', updateTotals);
  });
</script>



  </body>
</html>
