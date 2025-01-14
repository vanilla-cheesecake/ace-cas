<nav class="navbar navbar-expand navbar-light navbar-bg">
  <a class="sidebar-toggle js-sidebar-toggle">
    <i class="hamburger align-self-center"></i>
  </a>

  <div class="navbar-collapse collapse">
    <ul class="navbar-nav navbar-align">


      <li class="nav-item dropdown">
        <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
          <i class="align-middle" data-feather="settings"></i>
        </a>

        <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
          <img src="photos/logo/owemji.jpg" class="avatar img-fluid rounded me-1" /> <span class="text-dark">
            <?php if (isset($_SESSION['user_name'])): ?>
              <span><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
            <?php endif; ?></span>
        </a>
        <div class="dropdown-menu dropdown-menu-end">
          <a class="dropdown-item" href="pages-profile.html"><i class="align-middle me-1" data-feather="user"></i>
            Profile</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="api/logout_controller.php">Log out</a>
        </div>
      </li>
    </ul>
  </div>
</nav>