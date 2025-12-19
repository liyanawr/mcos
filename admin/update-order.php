<?php 
    ob_start(); 
    include('partials/menu.php'); 

    if(isset($_GET['id'])) {
        $id = mysqli_real_escape_string($conn, $_GET['id']);
        $sql = "SELECT o.order_ID, d.delivery_status, p.payment_status 
                FROM `ORDER` o 
                LEFT JOIN DELIVERY d ON o.order_ID = d.order_ID 
                LEFT JOIN PAYMENT p ON o.order_ID = p.order_ID 
                WHERE o.order_ID=$id";
        $res = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($res);
        $delivery_status = $row['delivery_status'] ?? "Ordered";
        $payment_status = $row['payment_status'] ?? "Pending";
    }

    if(isset($_POST['submit'])) {
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $d_status = mysqli_real_escape_string($conn, $_POST['delivery_status']);
        $p_status = mysqli_real_escape_string($conn, $_POST['payment_status']);

        // 1. UPDATE OR INSERT DELIVERY STATUS
        $check_d = mysqli_query($conn, "SELECT * FROM DELIVERY WHERE order_ID = $id");
        if(mysqli_num_rows($check_d) > 0) {
            $sql_d = "UPDATE DELIVERY SET delivery_status = '$d_status' WHERE order_ID = $id";
        } else {
            $sql_d = "INSERT INTO DELIVERY (order_ID, delivery_status) VALUES ($id, '$d_status')";
        }
        mysqli_query($conn, $sql_d);

        // 2. UPDATE OR INSERT PAYMENT STATUS
        $check_p = mysqli_query($conn, "SELECT * FROM PAYMENT WHERE order_ID = $id");
        if(mysqli_num_rows($check_p) > 0) {
            $sql_p = "UPDATE PAYMENT SET payment_status = '$p_status' WHERE order_ID = $id";
        } else {
            // Adjust this if your PAYMENT table requires more fields like payment_method
            $sql_p = "INSERT INTO PAYMENT (order_ID, payment_status) VALUES ($id, '$p_status')";
        }
        mysqli_query($conn, $sql_p);

        header('location:'.SITEURL.'admin/manage-order.php');
        exit();
    }
?>

<div class="main-content" style="background-color: #f1f2f6; padding: 3% 0;">
    <div class="wrapper" style="max-width: 800px; margin: 0 auto; background: white; padding: 40px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        
        <h1 class="text-center" style="color: #2f3542; margin-bottom: 30px;">Update Order Status</h1>

        <form action="" method="POST">
            <table class="table-no-border" style="width: 100%;">
                <tr style="height: 50px;">
                    <td style="width: 30%;"><strong>Order ID:</strong></td>
                    <td><span class="badge badge-dark" style="font-size: 1.1rem; padding: 8px 15px;">#<?php echo $id; ?></span></td>
                </tr>
                <tr style="height: 60px;">
                    <td><strong>Delivery Status:</strong></td>
                    <td>
                        <select name="delivery_status" class="form-control" style="padding: 8px; width: 100%; border-radius: 5px; border: 1px solid #ced4da;">
                            <option <?php if($delivery_status=="Ordered") echo "selected"; ?> value="Ordered">Ordered</option>
                            <option <?php if($delivery_status=="On Delivery") echo "selected"; ?> value="On Delivery">On Delivery</option>
                            <option <?php if($delivery_status=="Delivered") echo "selected"; ?> value="Delivered">Delivered</option>
                            <option <?php if($delivery_status=="Cancelled") echo "selected"; ?> value="Cancelled">Cancelled</option>
                        </select>
                    </td>
                </tr>
                <tr style="height: 60px;">
                    <td><strong>Payment Status:</strong></td>
                    <td>
                        <select name="payment_status" class="form-control" style="padding: 8px; width: 100%; border-radius: 5px; border: 1px solid #ced4da;">
                            <option <?php if($payment_status=="Pending") echo "selected"; ?> value="Pending">Pending</option>
                            <option <?php if($payment_status=="Verified") echo "selected"; ?> value="Verified">Verified</option>
                            <option <?php if($payment_status=="Failed") echo "selected"; ?> value="Failed">Failed</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="padding-top: 30px; text-align: center;">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="submit" name="submit" value="Update Status" class="btn-primary" style="padding: 12px 30px; border: none; background-color: #ff4757; color: white; border-radius: 5px; cursor: pointer; font-weight: bold;">
                        <a href="manage-order.php" style="margin-left: 15px; text-decoration: none; color: #747d8c;">Cancel</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<?php include('partials/footer.php'); ?>