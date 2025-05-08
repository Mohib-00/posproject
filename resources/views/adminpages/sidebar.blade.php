   <!-- Sidebar -->
   <div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
      <!-- Logo Header -->
      <div class="logo-header" data-background-color="dark">
        <a href="/admin" onclick="loadHomePage(); return false;" class="logo" style="color:white">
          <img
            src="{{asset('lite/assets/img/kaiadmin/logo_light.svg')}}"
            alt="navbar brand"
            class="navbar-brand"
            height="20"
          />
        </a>
        <div class="nav-toggle">
          <button class="btn btn-toggle toggle-sidebar">
            <i class="gg-menu-right"></i>
          </button>
          <button class="btn btn-toggle sidenav-toggler">
            <i class="gg-menu-left"></i>
          </button>
        </div>
        <button class="topbar-toggler more">
          <i class="gg-more-vertical-alt"></i>
        </button>
      </div>
      <!-- End Logo Header -->
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
      <div class="sidebar-content">
        <ul class="nav nav-secondary">
          <li class="nav-item active">
            <a
              data-bs-toggle="collapse"
              href="/admin" onclick="loadHomePage(); return false;"
              class="collapsed"
              aria-expanded="false"
            >
              <i class="fas fa-home"></i>
              <p>Dashboard</p>
              <span class="caret"></span>
            </a>
            <div class="collapse" id="dashboard">
              <ul class="nav nav-collapse">
                <li>
                  <a href="/admin" onclick="loadHomePage(); return false;">
                    <span class="sub-item">Home</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
          
          @if(Auth::check() && Auth::user()->userType == 1)
          <li class="nav-item">
            <a href="/admin/users" onclick="loadUsersPage(); return false;">
              <i class="icon-user"></i>
              <p>Users</p>
            </a>
          </li>
          @endif

          <li class="nav-item">
            <a href="/admin/POS" onclick="loadposPage(); return false;">
              <i style="color:blue" class="fas fa-dolly-flatbed"></i>
              <p>POS</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="/admin/sale_list" onclick="loadsalelistPage(); return false;">
              <i style="color:blue" class="fas fa-dolly-flatbed"></i>
              <p>Sale List</p>
            </a>
          </li>

          @if(Auth::check() && Auth::user()->userType == 1)
          <li class="nav-item">
            <a href="/admin/add_vendor" onclick="loadVendorsPage(); return false;">
              <i class="icon-user"></i>
              <p>Vendors</p>
            </a>
          </li>
          @endif

          @if(Auth::check() && Auth::user()->userType == 1)
          <li class="nav-item"> 
            <a data-bs-toggle="collapse" href="#tables">
              <i class="icon-people"></i>
              <p>Customers</p>
              <span class="caret"></span>
            </a>
            <div class="collapse" id="tables">
              <ul class="nav nav-collapse">
                <li>
                  <a href="/admin/customer_list" onclick="loadCustomerPage(); return false;">
                    <span class="sub-item">Client List</span>
                  </a>
                </li>
                <li>
                  <a href="/admin/area" onclick="loadAreaPage(); return false;">
                    <span class="sub-item">Area</span>
                  </a>
                </li>
                <li>
                  <a href="/admin/blocked_client_list" onclick="loadblockedclientPage(); return false;">
                    <span class="sub-item">Blocked Client List</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
          @endif

          @if(Auth::check() && Auth::user()->userType == 1)
          <li class="nav-item">
            <a data-bs-toggle="collapse" href="#base">
              <i class="icon-people"></i>
              <p>Employees</p>
              <span class="caret"></span>
            </a>
            <div class="collapse" id="base">
              <ul class="nav nav-collapse">
                <li>
                  <a href="/admin/employees_list" onclick="loadEmployeesPage(); return false;">
                    <span class="sub-item">Employee List</span>
                  </a>
                </li>
                <li>
                  <a href="/admin/employees_leave" onclick="loadEmployeleavePage(); return false;">
                    <span class="sub-item">Employee Leave</span>
                  </a>
                </li>
                <li>
                  <a href="/admin/designation" onclick="loaddesignationPage(); return false;">
                    <span class="sub-item">Designation List</span>
                  </a>
                </li>
                <li>
                  <a href="/admin/employee_attendance" onclick="loadAttendancePage(); return false;">
                    <span class="sub-item">Employee Attendance</span>
                  </a>
                </li>

                <li>
                  <a href="/admin/employee_attendance_report" onclick="loadAttendanceReportPage(); return false;">
                    <span class="sub-item">Attendance Report</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
          @endif

          @if(Auth::check() && Auth::user()->userType == 1)
          <li class="nav-item">
            <a data-bs-toggle="collapse" href="#sidebarLayouts">
              <i class="icon-diamond"></i>
              <p>Products</p>
              <span class="caret"></span>
            </a>
            <div class="collapse" id="sidebarLayouts">
              <ul class="nav nav-collapse">
                <li>
                  <a href="/admin/company_list" onclick="loadCompanyPage(); return false;">
                    <span class="sub-item">Company List</span>
                  </a>
                </li>
                <li>
                  <a href="/admin/category_list" onclick="loadCategoryPage(); return false;">
                    <span class="sub-item">Category List</span>
                  </a>
                </li>
                <li>
                  <a href="/admin/subcategory_list" onclick="loadsubPage(); return false;">
                    <span class="sub-item">Sub Category List</span>
                  </a>
                </li>
                <li>
                  <a href="/admin/products_list" onclick="loadProductPage(); return false;">
                    <span class="sub-item">Product List</span>
                  </a>
                </li>
                <li>
                  <a href="/admin/product_price_list" onclick="loadpricePage(); return false;">
                    <span class="sub-item">Product Price List</span>
                  </a>
                </li>
                <li>
                  <a href="/admin/product_import" onclick="loadimportPage(); return false;">
                    <span class="sub-item">Product Import</span>
                  </a>
                </li>
                <li>
                  <a href="/admin/format" onclick="loadformatPage(); return false;">
                    <span class="sub-item">Format Excel File</span>
                  </a>
                </li>
               
              </ul>
            </div>
          </li>
          @endif

          @if(Auth::check() && Auth::user()->userType == 1)
          <li class="nav-item">
            <a data-bs-toggle="collapse" href="#forms">
              <i class="icon-settings"></i>
              <p>Backup/Restore</p>
              <span class="caret"></span>
            </a>
            <div class="collapse" id="forms">
              <ul class="nav nav-collapse">
                <li>
                  <a href="">
                    <span class="sub-item">Backup/Reset</span>
                  </a>
                </li>
                <li>
                  <a href="">
                    <span class="sub-item">Restore</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
          @endif

          <li class="nav-item active" >
            <a data-bs-toggle="collapse" href="#maps">
              <i style="color:purple" class="far fa-window-maximize"></i>
              <p>PUR</p>
              <span class="caret"></span>
            </a>
            <div class="collapse" id="maps">
              <ul class="nav nav-collapse">
                <li>
                  <a href="/admin/purchase_list" onclick="loadpurchasePage(); return false;">
                    <span class="sub-item">Purchase</span>
                  </a>
                </li>
                <li>
                  <a href="">
                    <span class="sub-item">Purchase Return</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>

          <li class="nav-item active" >
            <a data-bs-toggle="collapse" href="#charts">
              <i style="color:purple" class="far fa-chart-bar"></i>
              <p>ACC</p>
              <span class="caret"></span>
            </a>
            <div class="collapse" id="charts">
              <ul class="nav nav-collapse">
                <li>
                  <a href="/admin/chart_of_account" onclick="loadaccountPage(); return false;">
                    <span class="sub-item">Chart Of Account</span>
                  </a>
                </li>
                <li>
                  <a href="/admin/voucher" onclick="loadvoucher(); return false;">
                    <span class="sub-item">Add Voucher</span>
                  </a>
                </li>
                
              </ul>
            </div>
          </li>
       
        </ul>
      </div>
    </div>
  </div>
  <!-- End Sidebar -->