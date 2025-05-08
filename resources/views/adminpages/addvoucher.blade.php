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
                  <form id="voucherform">
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
                            <select class="form-select form-control" id="vendors" name="voucher_type">
                              <option>Choose One</option>
                              <option>Cash Payment</option>
                              <option>Cash Receipt</option>
                            </select>
                          </div>
                        </div>

                        <div class="col-md-6 col-lg-4" style="display: none">
                          <div class="form-group">
                              <label for="cash_in_hand">Cash In Hand</label>
                              <input type="number" id="cashinhand" name="cash_in_hand" class="form-control" disabled>
                              <span id="nameError" class="text-danger"></span>
                          </div>
                      </div>

                       

                        <div class="col-md-6 col-lg-4">
                          <div class="form-group">
                              <label for="invoice_date">Voucher Date</label>
                              <input type="date" id="from_date" name="created_at" class="form-control" >
                              <span id="nameError" class="text-danger"></span>
                          </div>
                      </div>

                        <div class="col-md-12 col-lg-12">
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
                                        <select class="form-select form-control product-select" name="account[]" onchange="updateProductData(this)">
                                            <option value="">Choose One</option>
                                            @foreach($accounts as $account)
                                                <option value="{{ $account->id }}">{{ $account->sub_head_name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td style="min-width: 120px; max-width: 120px;">
                                        <input type="number" name="balance[]" class="form-control balance" disabled>
                                    </td>
                                    <td style="min-width: 120px; max-width: 120px;">
                                        <input type="text" name="narration[]" class="form-control">
                                    </td>
                                    <td style="min-width: 120px; max-width: 120px;">
                                      <input type="number" name="amount[]" class="form-control amount" oninput="calculateTotal()">
                                  </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">X</button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                              <tr>
                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                <td>
                                    <input type="number" class="form-control" id="totalAmount" readonly>
                                </td>
                                <td></td>
                            </tr>
                            </tfoot>
                        </table>
                        
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
    $(document).ready(function() {
    $('#vendors').on('change', function() {
        let voucherType = $(this).val();

        $.ajax({
            url: '/get-cash-in-hand',
            method: 'POST',
            data: {
                voucher_type: voucherType,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#cashinhand').val(response.cash_in_hand);
                $('.col-md-6.col-lg-4[style="display: none"]').show();
            },
            error: function(xhr) {
                console.log(xhr.responseJSON);
            }
        });
    });
});
  
function updateProductData(selectElement) {
    const accountId = selectElement.value;
    const row = selectElement.closest('tr');
    const balanceInput = row.querySelector('.balance');

    if (accountId) {
        $.ajax({
            url: '/get-account-balance',
            method: 'GET',
            data: { account_id: accountId },
            success: function(response) {
                if (response.balance !== undefined) {
                    balanceInput.value = response.balance;
                } else {
                    balanceInput.value = "0";
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                balanceInput.value = "0";
            }
        });
    } else {
        balanceInput.value = "0";
    }
}

function calculateTotal() {
    let total = 0;

    document.querySelectorAll('.amount').forEach(function(input) {
        const amount = parseFloat(input.value);
        if (!isNaN(amount)) {
            total += amount;
        }
    });

    document.getElementById('totalAmount').value = total.toFixed(2);
}

function addRow() {
    const rowHTML = `
        <tr>
            <td>
                <select class="form-select form-control product-select" name="account[]" onchange="updateProductData(this)">
                    <option value="">Choose One</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}">{{ $account->sub_head_name }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="number" name="balance[]" class="form-control balance" disabled>
            </td>
            <td>
                <input type="text" name="narration[]" class="form-control">
            </td>
            <td>
                <input type="number" name="amount[]" class="form-control amount" oninput="calculateTotal()">
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">X</button>
            </td>
        </tr>
    `;

    document.getElementById('tableBody').insertAdjacentHTML('beforeend', rowHTML);
}

function removeRow(button) {
    const row = button.closest('tr');
    row.remove();
    calculateTotal();
}

  </script>
  
  
  
      
      
  </body>
</html>