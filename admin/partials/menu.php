<?php 
    // 1. Include constants for DB connection and Session
    include('../config/constants.php'); 

    // 2. Authorization: Strict check for Staff role
    if(!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'staff') {
        $_SESSION['no-login-message'] = "<div class='error text-center'>Please login to access Admin Panel.</div>";
        header('location:'.SITEURL.'admin/login.php');
        exit;
    }

    // 3. Prevent browser caching for security
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - MCOS</title>

    <link rel="icon" type="image/png" href="../images/mcoslogo.png">
    <link rel="stylesheet" href="../css/style.css">

    <style>
    /* 1. Fix the content width so it doesn't hit the edges */
    .wrapper { 
        width: 80%; 
        max-width: 1200px; /* Limits the width on ultra-wide screens */
        margin: 0 auto; 
        padding: 1% 2%; /* Added horizontal padding */
    }

    /* 2. Better Navbar alignment */
    .navbar {
        background-color: white;
        padding: 10px 0;
        border-bottom: 1px solid #f1f2f6;
    }

    .navbar .container {
        display: flex;
        justify-content: space-between; /* Pushes Logo left and Menu right */
        align-items: center;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2%;
    }

    /* 3. Space out the menu links */
    .menu ul {
        list-style-type: none;
        margin: 0;
        padding: 0;
        display: flex;
        gap: 30px; /* Consistent spacing between all items */
    }

    .menu ul li a { 
        color: #2f3542; 
        font-weight: bold; 
        text-decoration: none; 
        transition: 0.3s;
    }

    .menu ul li a:hover { 
        color: #ff4757; 
    }

    .text-center { text-align: center; }
</style>
</head>

<body>
    <section class="navbar">
        <div class="container">
            <div class="logo">
                <a href="<?php echo SITEURL; ?>admin/index.php" title="Logo">
                    <img src="../images/mcoslogo.png" alt="Restaurant Logo" class="img-responsive">
                </a>
            </div>

            <div class="menu text-right">
                <ul>
                    <li><a href="index.php">Dashboard</a></li>
                    <li><a href="manage-profile.php">Profile</a></li>
                    <li><a href="manage-staff.php">Staff</a></li>
                    <li><a href="manage-customer.php">Customers</a></li>
                    <li><a href="manage-category.php">Categories</a></li>
                    <li><a href="manage-food.php">Menu</a></li>
                    <li><a href="manage-order.php">Orders</a></li>
                    <li><a href="../logout.php">Logout</a></li>
                </ul>
            </div>

            <div class="clearfix"></div>
        </div>
    </section>
</html>