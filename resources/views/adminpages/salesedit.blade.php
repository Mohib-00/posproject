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
             
                <form id="saleeditForm">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-round">
                                <div class="card-header">
                                    <div class="row">
                                        <!-- Clients Form -->
                                        <div class="col-12 col-md-2 mb-3">
                                            <label for="customerSelect">Choose Employee</label>
                                            <select class="form-select form-select-sm" id="customerSelect" name="employee">
                                                <option value="1" {{ $sale->employee == 'All' ? 'selected' : '' }}>All</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->name }}" {{ $sale->employee == $user->name ? 'selected' : '' }}>
                                                        {{ $user->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                
                                        <!-- Choose a Customer -->
                                        <div class="col-12 col-md-6 mb-3">
                                            <label for="smallSelect">Choose a Customer</label>
                                            <select class="form-select form-select-sm" id="smallSelect" name="customer_name">
                                                <option value="1" {{ $sale->customer_name == 'All' ? 'selected' : '' }}>All</option>
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->id }}" {{ $sale->customer_name == $customer->customer_name ? 'selected' : '' }}>
                                                        {{ $customer->customer_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                
                                        <!-- Date -->
                                        <div class="col-12 col-md-2 mb-3">
                                            <label for="dateInput">Date</label>
                                            <input class="form-control form-control-sm" value="{{ $sale->created_at->format('Y-m-d') }}" type="date" name="created_at" id="dateInput"/>
                                        </div>
                
                                        <!-- Ref# -->
                                        <div class="col-12 col-md-2 mb-3">
                                            <label for="refInput">Invoice#</label>
                                            <input class="form-control form-control-sm" value="{{$sale->ref}}" type="text" name="ref" id="refInput"/>
                                        </div>
                
                                        <div class="col-12 col-md-12 mb-3">
                                            <label for="productSelect">Search Item</label>
                                            <select class="form-select form-select-sm" id="productSelect">
                                                <option value="">Select Item</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}">{{ $product->item_name }}</option>
                                                @endforeach
                                            </select>
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
                                    <div class="card-head-row card-tools-still-right"></div>
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
                                              @foreach($sale->saleItems as $item)
                                              @php
                                              $unitPurchaseRate = $item->purchase_rate / max($item->product_quantity, 1);
                                              $productStock = \DB::table('products')->where('item_name', $item->product_name)->value('quantity') ?? 0;
                                          @endphp
                                          <tr>
                                              <td>
                                                  <input type="text" name="product_name[]" class="form-control form-control-sm item-name-input" 
                                                         value="{{ $item->product_name }}" readonly style="width:150px">
                                              </td>
                                              <td class="text-end">
                                                  <input type="number" name="product_quantity[]" class="form-control form-control-sm quantity-input" 
                                                         value="{{ $item->product_quantity }}" min="1" data-stock="{{ $productStock }}" 
                                                         style="text-align:right;width:60px">
                                              </td>
                                              <td>
                                                  <input type="number" name="purchase_rate[]" class="form-control form-control-sm purchase-rate-input"
                                                         value="{{ $item->purchase_rate }}"
                                                         data-unit-purchase="{{ number_format($unitPurchaseRate, 2, '.', '') }}"
                                                         min="0" step="0.01" style="text-align:right;width:80px">
                                              </td>
                                              <td>
                                                  <input type="number" name="product_rate[]" class="form-control form-control-sm rate-input" 
                                                         value="{{ $item->product_rate }}" style="text-align:right;width:80px">
                                              </td>
                                              <td>
                                                  <input type="text" name="product_subtotal[]" class="form-control form-control-sm subtotal-input" 
                                                         value="{{ number_format($item->product_subtotal, 2) }}" readonly style="text-align:right;width:100px">
                                              </td>
                                              <td>
                                                  <button class="btn btn-icon btn-round btn-danger btn-sm delete-row">
                                                      <i class="fa fa-trash"></i>
                                                  </button>
                                              </td>
                                          </tr>
                                      @endforeach
                                      
                                                                            
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="3" class="text-end fw-bold">Total Items</td>
                                                    <td class="fw-bold">
                                                        <input type="number" id="totalItems" value="{{ $sale->total_items }}" name="total_items" class="form-control form-control-sm text-end fw-bold" style="width: fit-content" disabled>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" class="text-end fw-bold">Total</td>
                                                    <td class="fw-bold">
                                                        <input type="number" id="totalAmount" value="{{ $sale->total }}" name="total" class="form-control form-control-sm text-end fw-bold" style="width: fit-content;" disabled>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                
                        <div class="col-md-3">
                            <div class="card card-round shadow-sm">
                                <div class="card-body">
                                    <div class="card-head-row card-tools-still-right mb-3">
                                        <div class="fw-bold" style="font-size: 16px;">Sale Type</div>
                                        <div class="dropdown ms-auto">
                                            <select class="form-select form-select-sm" value={{$sale->sale_type}} name="sale_type" id="saleTypeSelect" style="width: 150px; border-radius: 8px;">
                                                <option value="1">{{$sale->sale_type}}</option>
                                                <option value="1">Cash</option>
                                                <option value="2">Credit</option>
                                            </select>
                                        </div>
                                    </div>
                
                                    <div class="card-head-row card-tools-still-right mb-3">
                                        <div class="fw-bold" style="font-size: 16px;">Payment Type</div>
                                        <div class="dropdown ms-auto">
                                            <select class="form-select form-select-sm" value={{$sale->payment_type}} name="payment_type" id="paymentTypeSelect" style="width: 150px; border-radius: 8px;">
                                                <option value="1">{{$sale->payment_type}}</option>
                                                <option value="1">Cash</option>
                                                <option value="2">Bank</option>
                                            </select>
                                        </div>
                                    </div>
                
                                    <hr>
                
                                    <div class="card-list py-4">
                                        <div class="item-list d-flex align-items-center">
                                            <div class="info-user">
                                                <div class="fw-bold" style="font-size: 16px;">Discount</div>
                                            </div>
                                            <input class="form-control form-control-sm ms-auto" value="{{ $sale->discount }}" type="text" name="discount" id="discount" style="width: 150px; border-radius: 8px;" value="0" />
                                        </div>
                                    </div>
                
                                    <div class="card-list py-1">
                                        <div class="item-list d-flex align-items-center">
                                            <div class="info-user">
                                                <div class="fw-bold" style="font-size: 16px;">Amount After Discount</div>
                                            </div>
                                            <input class="form-control form-control-sm ms-auto" type="number" value="{{ $sale->amount_after_discount }}" name="amount_after_discount" id="amountafterdiscount" style="width: 120px; border-radius: 8px;" disabled />
                                        </div>
                                    </div>
                
                                    <hr>
                
                                    <div class="card-list py-4" id="fixedDiscountSection">
                                        <div class="item-list d-flex align-items-center">
                                            <div class="info-user">
                                                <div class="fw-bold" style="font-size: 16px;">Fixed Discount</div>
                                            </div>
                                            <input class="form-control form-control-sm ms-auto" type="number" value="{{ $sale->fixed_discount }}" name="fixed_discount" id="fixeddiscount" style="width: 150px; border-radius: 8px;" value="0" disabled/>
                                        </div>
                                    </div>
                
                                    <div class="card-list py-1">
                                        <div class="item-list d-flex align-items-center">
                                            <div class="info-user">
                                                <div class="fw-bold" style="font-size: 16px;">Amount After Fix-Discount</div>
                                            </div>
                                            <input class="form-control form-control-sm ms-auto" type="number" value="{{ $sale->amount_after_fix_discount }}" name="amount_after_fix_discount" id="amountafterfixdiscount" style="width: 120px; border-radius: 8px;" value="0" disabled/>
                                        </div>
                                    </div>
                
                                    <hr>
                
                                    <div class="card-list py-1">
                                        <div class="item-list d-flex align-items-center">
                                            <div class="info-user">
                                                <div class="fw-bold" style="font-size: 16px;">Total Rs:</div>
                                            </div>
                                            <input class="form-control form-control-sm ms-auto" value="{{ $sale->subtotal }}" type="number" name="subtotal" id="total" style="width: 150px; border-radius: 8px;" disabled/>
                                        </div>
                                    </div>
                
                                    <hr>
                
                                    <div class="d-flex justify-content-center mt-4">
                                        <a id="submiteditsale" class="btn btn-primary" style="width: 100px; border-radius: 8px;">Submit</a>
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
        $(document).ready(function () {
          let saleId = window.location.pathname.split('/').pop();  
      
          $('#submiteditsale').on('click', function (e) {
              e.preventDefault();
      
              let productNames = $('[name="product_name[]"]');
              let productQuantities = $('[name="product_quantity[]"]');
              let productRates = $('[name="product_rate[]"]');
              let productSubtotals = $('[name="product_subtotal[]"]');
              let purchaseRates = document.querySelectorAll('[name="purchase_rate[]"]');
      
              let items = [];
              for (let i = 0; i < productNames.length; i++) {
                  let name = $(productNames[i]).val().trim();
                  if (name !== '') {
                      items.push({
                          product_name: name,
                          product_quantity: parseInt($(productQuantities[i]).val()),
                          purchase_rate: parseFloat(purchaseRates[i].value),
                          product_rate: parseFloat($(productRates[i]).val()),
                          product_subtotal: parseFloat($(productSubtotals[i]).val())
                      });
                  }
              }
      
              let formData = {
                  sale_id: saleId, 
                  employee: $('[name="employee"]').val(),
                  customer_id: $('#smallSelect').val(),
                  customer_name: $('#smallSelect option:selected').text(),
                  created_at: $('[name="created_at"]').val(),
                  ref: $('[name="ref"]').val(),
                  sale_type: $('[name="sale_type"]').val(),
                  payment_type: $('[name="payment_type"]').val(),
                  discount: $('[name="discount"]').val(),
                  total_items: $('[name="total_items"]').val(),
                  total: $('[name="total"]').val(),
                  amount_after_discount: $('[name="amount_after_discount"]').val(),
                  fixed_discount: $('[name="fixed_discount"]').val(),
                  amount_after_fix_discount: $('[name="amount_after_fix_discount"]').val(),
                  subtotal: $('[name="subtotal"]').val(),
                  items: items
              };
      
              $.ajax({
                  url: `/submit-sale-form/${saleId}`,  
                  method: 'PUT',
                  data: JSON.stringify(formData),
                  contentType: 'application/json',
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  },
                  success: function (data) {
                      console.log('Sale updated successfully!');
                      Swal.fire({
                          title: 'Success!',
                          text: 'Sale updated successfully!',
                          icon: 'success',
                          confirmButtonText: 'OK'
                      }).then((result) => {
                          if (result.isConfirmed) {
                              loadsalelistPage(); 
                          }
                      });
                  },
                  error: function (xhr, status, error) {
                      console.error('Error:', error);
                      Swal.fire({
                          title: 'Error!',
                          text: 'Something went wrong. Please try again.',
                          icon: 'error',
                          confirmButtonText: 'OK'
                      });
                  }
              });
          });
        });
      </script>
      
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
           var stockLimit = parseInt(existingRow.find('.quantity-input').attr('data-stock'));
 
           if (newQuantity > stockLimit) {
             Swal.fire({
               icon: 'warning',
               title: 'Stock Limit Exceeded',
               text: `Only ${stockLimit} items in stock.`,
             });
             return;
           }
 
           existingRow.find('.quantity-input').val(newQuantity);
           existingRow.find('.subtotal-input').val((newQuantity * retailRate).toFixed(2));
 
           var unitPurchaseRate = parseFloat(existingRow.find('.purchase-rate-input').attr('data-unit-purchase')) || 0;
           var updatedPurchaseRate = unitPurchaseRate * newQuantity;
           existingRow.find('.purchase-rate-input').val(updatedPurchaseRate.toFixed(2));
 
           updateTotals();
         } else {
           $('#productTable tbody').append(`
             <tr>
               <td>
                 <input type="text" class="form-control form-control-sm item-name-input" name="product_name[]" value="${product.item_name}" readonly/>
               </td>
               <td>
                 <input style="text-align:right;width:60px" type="number" class="form-control form-control-sm quantity-input" name="product_quantity[]" value="1" min="1" data-stock="${product.quantity}"/>
               </td>
               <td>
                 <input type="number" class="form-control form-control-sm purchase-rate-input" name="purchase_rate[]" value="${purchaseRate.toFixed(2)}" min="0" step="0.01" data-unit-purchase="${purchaseRate.toFixed(2)}"/>
               </td>
               <td>
                 <input type="number" class="form-control form-control-sm rate-input" style="text-align:right;width:80px" name="product_rate[]" value="${retailRate.toFixed(2)}" min="0" step="0.01"/>
               </td>
               <td>
                 <input type="number" class="form-control form-control-sm subtotal-input text-end" name="product_subtotal[]" value="${subtotal.toFixed(2)}" readonly/>
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
       }
     });
   });
 
   $(document).on('click', '.delete-row', function () {
     $(this).closest('tr').remove();
     updateTotals();
   });
 
   $(document).on('input', '.quantity-input', function () {
    var $row = $(this).closest('tr');
    var quantity = parseInt($(this).val()) || 1;
    var stockLimit = parseInt($row.find('.quantity-input').attr('data-stock')) || 0;

    if (quantity > stockLimit) {
      Swal.fire({
        icon: 'warning',
        title: 'Stock Limit Exceeded',
        text: `Only ${stockLimit} items available in stock.`,
        confirmButtonText: 'OK'
      });

      $(this).val(stockLimit); 
      quantity = stockLimit;
    }

    var retailRate = parseFloat($row.find('.rate-input').val()) || 0;
    var unitPurchaseRate = parseFloat($row.find('.purchase-rate-input').attr('data-unit-purchase')) || 0;

    var subtotal = quantity * retailRate;
    var updatedPurchaseRate = unitPurchaseRate * quantity;

    $row.find('.subtotal-input').val(subtotal.toFixed(2));
    $row.find('.purchase-rate-input').val(updatedPurchaseRate.toFixed(2));

    updateTotals();
  });
 
   $(document).on('input', '.rate-input', function () {
     var $row = $(this).closest('tr');
     var quantity = parseInt($row.find('.quantity-input').val()) || 1;
     var retailRate = parseFloat($(this).val()) || 0;
 
     var subtotal = quantity * retailRate;
     $row.find('.subtotal-input').val(subtotal.toFixed(2));
 
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