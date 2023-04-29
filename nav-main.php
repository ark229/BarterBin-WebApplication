<?php

require_once('config.php');
require_once('session.php');

echo '<pre>';
print_r($_SESSION);
echo '</pre>';

?>

<!DOCTYPE html>



<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />
    <title>Bargin Bin - <Main></Main>
    </title>
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

    <nav class="navbar navbar-light navbar-expand-md py-3" style="border-bottom: 3px solid var(--bs-gray-500);">
        <div class="container"><a class="navbar-brand d-flex align-items-center" href="index.php" style="width: 340px;height: 230px;"><img src="assets/img/barter-bin-high-resolution-logo-color-on-transparent-background.png" style="width: 330px;"></a><button data-bs-toggle="collapse" class="navbar-toggler" data-bs-target="#navcol-2"><span class="visually-hidden">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navcol-2">
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['user_id'])) : ?>
                        <li class="nav-item"><a class="nav-link active" href="editBarter.php" style="font-weight: bold;color: var(--bs-green);font-size: 20px;">Edit Barter</a></li>
                        <li class="nav-item"><a class="nav-link" href="contact.php" target="_blank" style="color: var(--bs-green);font-weight: bold;font-size: 20px;">Contact</a></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php" style="color: var(--bs-green);font-weight: bold;font-size: 19px;">Logout</a></li>
                    <?php else : ?>
                        <li class="nav-item"><a class="nav-link" href="login.php" style="color: var(--bs-green);font-weight: bold;font-size: 20px;">Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>




</body>

</html>