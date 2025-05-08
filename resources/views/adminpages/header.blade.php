<div class="main-header">
  <div class="main-header-logo">
    <!-- Logo Header -->
    <div class="logo-header" data-background-color="dark">
      <a href="index.html" class="logo">
        <img
          src="{{asset('lite/assets/img/kaiadmin/logo_light.svg')}}"
          alt="navbar brand"
          class="navbar-brand image"
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
  <!-- Navbar Header -->
  <nav
    class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom"
  >
    <div class="container-fluid">
      <nav
        class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex"
      >
      <div class="input-group">
        <div class="input-group-prepend">
          <button class="btn btn-search pe-1" onclick="searchPage()">
            <i class="fa fa-search search-icon"></i>
          </button>
        </div>
        <input
          type="text"
          id="searchInput"
          placeholder="Search ..."
          class="form-control"
          onkeyup="searchPage(event)"
        />
      </div>
      
      <script>
        const routes = {
          "admin": "admin",
          "users": "users",
          "messages": "messages",
          "settings": "website-settings",
          "banner": "add-banner-details",
          "highlight": "add-highlight",
          "overview": "add-overview",
          "workstream": "add-workstream",
          "network": "add-network",
          "working group participation": "add-Working_Group_Participation",
          "members": "add-members",
          "team": "add-team",
          "news": "add-news",
          "about": "add-about",
          "initiative": "add-initiative",
          "section1": "add-section-1",
          "section2": "add-section-2",
          "resource": "add-resource",
          "join": "add-join",
          "membership": "add",
          "membership section2": "add-membership-section2",
          "membership category": "add-membership-category",
          "support1": "add-support-section1",
          "support2": "add-support-section2",
          "support3": "add-support-section3",
          "career opportunities": "add-career-opportunities",
          "profile": "admin_profile"
        };
      
        function searchPage(event) {
          if (event && event.type === "keyup" && event.key !== "Enter") return;
      
          let query = document.getElementById("searchInput").value.toLowerCase().trim();
          if (!query) return;
      
          let foundRoute = null;
      
          for (let key in routes) {
            if (key.toLowerCase().includes(query)) {
              foundRoute = key === "admin" ? "/" + routes[key] : "/admin/" + routes[key];
              break;
            }
          }
      
          if (foundRoute) {
            window.location.href = foundRoute;
          } else {
            alert("No matching page found!");
          }
        }
      </script>
      
      </nav>

      <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
        <li
          class="nav-item topbar-icon dropdown hidden-caret d-flex d-lg-none"
        >
          <a
            class="nav-link dropdown-toggle"
            data-bs-toggle="dropdown"
            href="#"
            role="button"
            aria-expanded="false"
            aria-haspopup="true"
          >
            <i class="fa fa-search"></i>
          </a>
          <ul class="dropdown-menu dropdown-search animated fadeIn">
            <form class="navbar-left navbar-form nav-search">
              <div class="input-group">
                <input
                  type="text"
                  placeholder="Search ..."
                  class="form-control"
                />
              </div>
            </form>
          </ul>
        </li>
     
      
    

        <li class="nav-item topbar-user dropdown hidden-caret">
          <a
            class="dropdown-toggle profile-pic"
            data-bs-toggle="dropdown"
            href="#"
            aria-expanded="false"
          >
            <div class="avatar-sm">
              @if(Auth::check() && Auth::user()->userType == 1)
              <img
              src="{{ Auth::user()->image ? asset('images/' . Auth::user()->image) : '' }}" 
                alt="..."
                class="avatar-img rounded-circle image"
              />
              @else
              <img class="avatar-img rounded-circle image" src="{{ asset('images/dummy-image.jpg') }}" />
              @endif
            </div>
            <span class="profile-username">
              <span class="op-7">Hi,</span>
              <span class="fw-bold name">{{$userName}}</span>
            </span>
          </a>
          <ul class="dropdown-menu dropdown-user animated fadeIn">
            <div class="dropdown-user-scroll scrollbar-outer">
              <li>
                <div class="user-box">
                  <div class="avatar-lg">
                    @if(Auth::check() && Auth::user()->userType == 1)
                    <img
                      src="{{ Auth::user()->image ? asset('images/' . Auth::user()->image) : '' }}" 
                      alt="image profile"
                      class="avatar-img rounded"
                    />
                    @else
                    <img class="avatar-img rounded" src="{{ asset('images/dummy-image.jpg') }}" />
                    @endif
                  </div>
                  <div class="u-text">
                    <h4 class="name">{{$userName}}</h4>
                    <p class="text-muted email">{{ $userEmail }}</p>
                    @if(Auth::check() && Auth::user()->userType == 1)
                    <a
                      href="/admin/admin_profile" onclick="loadProfilePage(); return false;"
                      class="btn btn-xs btn-secondary btn-sm "
                      >View Profile</a
                    >
                    @endif
                  </div>
                </div>
              </li>
              <li>                
                <div class="dropdown-divider"></div>
                <a class="dropdown-item logout">Logout</a>
              </li>
            </div>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
  <!-- End Navbar -->
</div>