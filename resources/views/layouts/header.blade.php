<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="{{ url('admin/home') }}" class="nav-link">Home</a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <!-- <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#" id="getNotification">
        <i class="far fa-bell"></i>
        <span class="badge badge-warning navbar-badge notificationCount"></span>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right notIficationMessage">
        <span class="dropdown-item dropdown-header">Notifications</span>
        <div class="dropdown-divider"></div>
        <div class="notificationMessages">

        </div>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
      </div>
    </li> -->

    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-user"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">

        <a class="dropdown-item" href="{{ url('admin/profile',auth()->user()->id) }}"><i class="fas fa-user mr-2"></i> Profile </a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
          <i class="fas fa-sign-out-alt mr-2"></i> Logout
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
        </form>

      </div>
    </li>
  </ul>
</nav>