
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>De Mol Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="Dashboard.">
    <meta name="msapplication-tap-highlight" content="no">
<link href="./main.css" rel="stylesheet"></head>
<body>

  <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
    <!-- Navbar -->
    <div class="app-header header-shadow">
        <div class="app-header__logo">
          <div class="logo-src"></div>
          <div class="header__pane ml-auto">
            <div>
              <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                  <span class="hamburger-box">
                      <span class="hamburger-inner"></span>
                  </span>
              </button>
            </div>
          </div>
        </div>
        <div class="app-header__mobile-menu">
          <div>
            <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
              <span class="hamburger-box">
                <span class="hamburger-inner"></span>
              </span>
            </button>
          </div>
        </div>
        <div class="app-header__menu">
          <span>
            <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
              <span class="btn-icon-wrapper">
                <i class="fa fa-ellipsis-v fa-w-6"></i>
              </span>
            </button>
          </span>
        </div>    
    </div>        

    <div class="app-main">

      <!-- Sidebar -->
      <div class="app-sidebar sidebar-shadow">    
        <div class="scrollbar-sidebar">
          <div class="app-sidebar__inner">
            <ul class="vertical-nav-menu">
              <li>
                <a href="index.php">
                  <i class="metismenu-icon pe-7s-rocket"></i>
                  Dashboard
                </a>
              </li>
              <li class="app-sidebar__heading">Main</li>
              <li>
                <a href="users.php">
                  <i class="metismenu-icon pe-7s-display2"></i>
                  Users
                </a>
                <a href="groups.php">
                  <i class="metismenu-icon pe-7s-display2"></i>
                  Groups
                </a>
                <a href="candidates.php">
                  <i class="metismenu-icon pe-7s-display2"></i>
                  Candidates
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div> 
                
      <!-- Main -->
      <div class="app-main__outer">
        <div class="app-main__inner">
