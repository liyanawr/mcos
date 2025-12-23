<?php 
    ob_start(); 
    include('partials/menu.php'); 

    $current_staff_id = $_SESSION['u_id']; // The staff currently logged in

    if(isset($_GET['id'])) {
        $id = mysqli_real_escape_string($conn, $_GET['id']);
        $sql = "SELECT o.order_ID, d.delivery_status, p.payment_status 
                FROM `ORDER` o 
                LEFT JOIN DELIVERY d ON o.order_ID = d.order_ID 
                LEFT JOIN PAYMENT p ON o.order_ID = p.order_ID 
                WHERE o.order_ID=$id";
        $res = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($res);
    }

    if(isset($_POST['submit'])) {
        $id = $_POST['id'];
        $d_status = $_POST['delivery_status'];
        $p_status = $_POST['payment_status'];

        // 1. UPDATE ORDER with staff ID who managed it
        mysqli_query($conn, "UPDATE `ORDER` SET staff_ID = $current_staff_id WHERE order_ID = $id");

        // 2. UPDATE DELIVERY and log staff
        $check_d = mysqli_query($conn, "SELECT * FROM DELIVERY WHERE order_ID = $id");
        if(mysqli_num_rows($check_d) > 0) {
            $sql_d = "UPDATE DELIVERY SET delivery_status = '$d_status', staff_ID = $current_staff_id WHERE order_ID = $id";
        } else {
            $sql_d = "INSERT INTO DELIVERY (order_ID, delivery_status, staff_ID) VALUES ($id, '$d_status', $current_staff_id)";
        }
        mysqli_query($conn, $sql_d);

        // 3. UPDATE PAYMENT
        mysqli_query($conn, "UPDATE PAYMENT SET payment_status = '$p_status' WHERE order_ID = $id");

        header('location:'.SITEURL.'admin/manage-order.php');
        exit();
    }
?>

<div class="main-content" style="background-color: #f1f2f6; padding: 3% 0;">
    <div class="wrapper" style="max-width: 600px; background: white; padding: 40px; border-radius: 15px;">
        <h2 class="text-center">Update Order #<?php echo $id; ?></h2>
        <p class="text-center" style="font-size: 0.8rem; color: #a4b0be;">Logged by Staff ID: <?php echo $current_staff_id; ?></p>
        <form action="" method="POST">
            <br>
            <label>Delivery Status</label>
            <select name="delivery_status" class="form-control" style="width: 100%; padding: 10px; margin: 10px 0;">
                <option <?php if($row['delivery_status']=="Ordered") echo "selected"; ?> value="Ordered">Ordered</option>
                <option <?php if($row['delivery_status']=="On Delivery") echo "selected"; ?> value="On Delivery">On Delivery</option>
                <option <?php if($row['delivery_status']=="Delivered") echo "selected"; ?> value="Delivered">Delivered</option>
            </select>
            
            <label>Payment Status</label>
            <select name="payment_status" class="form-control" style="width: 100%; padding: 10px; margin: 10px 0;">
                <option <?php if($row['payment_status']=="Pending") echo "selected"; ?> value="Pending">Pending</option>
                <option <?php if($row['payment_status']=="Verified") echo "selected"; ?> value="Verified">Verified</option>
                <option <?php if($row['payment_status']=="Failed") echo "selected"; ?> value="Failed">Failed</option>
            </select>

            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="submit" name="submit" value="Update Order" class="btn-primary" style="width: 100%; background: #2f3542; color: white; border: none; padding: 15px; border-radius: 5px; cursor: pointer;">
        </form>
    </div>
</div>
