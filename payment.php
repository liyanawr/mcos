<?php 
ob_start(); 
include('partials-front/menu.php'); 

// Validate session data from order.php
if(empty($_SESSION["u_id"]) || !isset($_SESSION['temp_order'])) {
    header('location:index.php');
    exit;
}

$u_id = $_SESSION['u_id'];
$temp = $_SESSION['temp_order'];

// Fetch customer details for display
$sql_cust = "SELECT * FROM CUSTOMER WHERE cust_ID = $u_id";
$res_cust = mysqli_query($conn, $sql_cust);
$cust = mysqli_fetch_assoc($res_cust);
?>

<section class="payment-section" style="background-color: #f1f2f6; padding: 4% 0;">
    <div class="container">
        <div style="background: white; padding: 40px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); max-width: 600px; margin: 0 auto;">
            
            <h2 class="text-center" style="color: #2f3542; margin-bottom: 20px;">Confirm & Pay</h2>

            <?php 
                if(isset($_POST['confirm_payment'])) {
                    $payment_method = $_POST['bank_name'];
                    
                    if(isset($_FILES['receipt']['name']) && $_FILES['receipt']['name'] != "") {
                        $temp_file = explode('.', $_FILES['receipt']['name']);
                        $ext = end($temp_file);
                        $receipt_name = "Receipt-".time()."-".rand(000,999).".".$ext;
                        $source_path = $_FILES['receipt']['tmp_name'];
                        $destination_path = "images/receipts/".$receipt_name;

                        if(!is_dir('images/receipts/')) mkdir('images/receipts/', 0777, true);

                        if(move_uploaded_file($source_path, $destination_path)) {
                            
                            // START DATABASE TRANSACTION
                            // 1. Insert into `ORDER`
                            $gt = $temp['grand_total'];
                            $dc = $temp['delivery_charge'];
                            $sql1 = "INSERT INTO `ORDER` (grand_total, delivery_charge, cust_ID, staff_ID) VALUES ($gt, $dc, $u_id, 2)";
                            
                            if(mysqli_query($conn, $sql1)) {
                                $order_id = mysqli_insert_id($conn);

                                // 2. Insert into ORDER_MENU
                                $fid = $temp['food_id'];
                                $qty = $temp['qty'];
                                $st = $temp['price'] * $qty;
                                $sql2 = "INSERT INTO ORDER_MENU (order_ID, menu_ID, order_quantity, sub_total) VALUES ($order_id, $fid, $qty, $st)";
                                mysqli_query($conn, $sql2);

                                // 3. Insert into PAYMENT
                                $sql3 = "INSERT INTO PAYMENT (amount_paid, payment_method, payment_status, receipt_file, order_ID) 
                                         VALUES ($gt, '$payment_method', 'Pending Verification', '$receipt_name', $order_id)";
                                mysqli_query($conn, $sql3);

                                // Success
                                unset($_SESSION['temp_order']);
                                $_SESSION['pay_notice'] = "<div class='success text-center'>Order successfully placed! Payment is being verified.</div>";
                                header('location:myorders.php');
                                exit;
                            }
                        } else {
                            echo "<div class='error text-center'>File upload failed. Check folder permissions.</div>";
                        }
                    } else {
                        echo "<div class='error text-center'>Please upload payment proof.</div>";
                    }
                }
            ?>

            <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; margin-bottom: 25px; border-left: 5px solid #2ecc71;">
                <p><strong>Item:</strong> <?php echo $temp['food_name']; ?> (x<?php echo $temp['qty']; ?>)</p>
                <p><strong>Delivery to:</strong> <?php echo $cust['cust_dorm']; ?></p>
                <hr>
                <p style="font-size: 1.2rem; color: #ff4757;"><strong>Total to Pay: RM <?php echo number_format($temp['grand_total'], 2); ?></strong></p>
            </div>

            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="font-weight:bold;">Select Bank</label>
                    <select name="bank_name" class="form-control" style="width:100%; padding:10px;" required>
                        <option value="Maybank">Maybank</option>
                        <option value="CIMB Bank">CIMB Bank</option>
                        <option value="Bank Islam">Bank Islam</option>
                        <option value="TNG eWallet">TNG eWallet</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="font-weight:bold;">Upload Receipt</label>
                    <input type="file" name="receipt" required>
                </div>

                <button type="submit" name="confirm_payment" class="btn-primary" style="width:100%; padding:12px; background:#2ecc71; border:none; color:white; border-radius:5px; cursor:pointer;">Confirm Payment</button>
                <div class="text-center" style="margin-top:10px;">
                    <a href="order.php?food_id=<?php echo $temp['food_id']; ?>" style="color:#747d8c; text-decoration:none;">Cancel & Go Back</a>
                </div>
            </form>
        </div>
    </div>
</section>

<?php include('partials-front/footer.php'); ?>