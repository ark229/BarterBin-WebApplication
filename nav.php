<?php

require_once('config.php');
require_once('session.php');

?>

<!DOCTYPE html>



<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />
  <title>Bargin Bin - Save Your Money & Barter Today!</title>
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css" />
  <link rel="stylesheet" href="assets/css/Animated-Type-Heading.css" />
  <link rel="stylesheet" href="assets/css/DA_About.css" />
  <link rel="stylesheet" href="assets/css/Navbar-Centered-Brand-icons.css" />
</head>

<body style="
      --bs-info: #0dcaf0;
      --bs-info-rgb: 13, 202, 240;
      --bs-light: #c8ccd0;
      --bs-light-rgb: 200, 204, 208;
      --bs-body-color: #eff1f4;
    ">


  <nav class="navbar navbar-light navbar-expand-md py-3" style="box-shadow: 0px 0px var(--bs-warning)">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="index.php">
        <span><img src="assets/img/barter-bin-high-resolution-logo-color-on-transparent-background.png" style="
                width: 352px;
                margin: 6px;
                padding: 0px;
                height: 219.469px;
              " /></span>
      </a>
      <button data-bs-toggle="collapse" class="navbar-toggler" data-bs-target="#navcol-4">
        <span class="visually-hidden">Toggle navigation</span><span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse flex-grow-0 order-md-first" id="navcol-4">
        <div class="d-md-none my-2">
          <?php
          if (isset($_SESSION['user_id'])) {
            echo '<a class="btn btn-light me-2" role="button" style="font-weight: bold; background: var(--bs-teal)" href="logout.php">LOGOUT</a>';
          } else {
            echo '<a class="btn btn-light me-2" role="button" style="font-weight: bold; background: var(--bs-teal)" href="login.php">LOGIN</a>';
          }
          ?>
        </div>
      </div>
      <div class="d-none d-md-block">
        <?php
        if (isset($_SESSION['user_id'])) {
          echo '<a class="btn btn-light me-2" role="button" style="
                  --bs-secondary: #6c757d;
                  --bs-secondary-rgb: 108, 117, 125;
                  background: #c8ccd0;
                  backdrop-filter: opacity(0.94) brightness(121%) contrast(76%);
                  -webkit-backdrop-filter: opacity(0.94) brightness(121%)
                    contrast(76%);
                  --bs-body-font-size: 9rem;
                  --bs-body-font-weight: bold;
                  border-color: var(--bs-teal);
                  box-shadow: 0px 0px var(--bs-yellow);
                  padding-right: 11px;
                  margin-left: -36px;
                " href="logout.php">LOGOUT</a>';
        } else {
          echo '<a class="btn btn-light me-2" role="button" style="
                  --bs-secondary: #6c757d;
                  --bs-secondary-rgb: 108, 117, 125;
                  background: #c8ccd0;
                  backdrop-filter: opacity(0.94) brightness(121%) contrast(76%);
                  -webkit-backdrop-filter: opacity(0.94) brightness(121%)
                    contrast(76%);
                  --bs-body-font-size: 9rem;
                  --bs-body-font-weight: bold;
                  border-color: var(--bs-teal);
                  box-shadow: 0px 0px var(--bs-yellow);
                  padding-right: 11px;
                  margin-left: -36px;
                " href="login.php">LOGIN HERE</a>';
        }
        ?>
      </div>
    </div>
  </nav>


</body>

</html>