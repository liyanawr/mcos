<?php 
    include('../config/constants.php'); 

    // 1. Authorization: Strict check for Staff role
    if(!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'staff') {
        header('location:'.SITEURL.'login.php'); // Redirect to main login
        exit;
    }

    // 2. Identify if this is the Head Admin (ID 1)
    $is_admin = ($_SESSION['u_id'] == 1); 

    // 3. Prevent browser caching
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - MCOS</title>
    <link rel="icon" type="image/png" href="../images/mcoslogo.png">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .wrapper { width: 90%; max-width: 1300px; margin: 0 auto; padding: 2% 0; }
        .navbar { background: white; border-bottom: 2px solid #f1f2f6; }
        .menu ul { list-style: none; display: flex; gap: 20px; padding: 0; margin: 0; }
        .menu ul li a { color: #2f3542; font-weight: bold; text-decoration: none; transition: 0.3s; font-size: 0.9rem;}
        .menu ul li a:hover { color: #ff4757; }
        .btn-action { padding: 5px 10px; border-radius: 4px; color: white; text-decoration: none; font-size: 0.8rem; }
        .table-admin { width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .table-admin th { background: #2f3542; color: white; padding: 15px; text-align: left; }
        .table-admin td { padding: 15px; border-bottom: 1px solid #f1f2f6; }
    </style>
</head>
<body>
    <section class="navbar">
        <div class="wrapper" style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0;">
            <div class="logo">
                <a href="index.php"><img src="../images/mcoslogo.png" style="width: 80px;"></a>
            </div>
            <div class="menu">
                <ul>
                    <li><a href="index.php">Dashboard</a></li>
                    <li><a href="manage-staff.php">Staff</a></li>
                    <li><a href="manage-customer.php">Customers</a></li>
                    <li><a href="manage-category.php">Categories</a></li>
                    <li><a href="manage-food.php">Menu</a></li>
                    <li><a href="manage-order.php">Orders</a></li>
                    <li><a href="manage-profile.php">Profile</a></li>
                    <li><a href="../logout.php" style="color: #ff4757;">Logout</a></li>
                </ul>
            </div>
        </div>
    </section>
