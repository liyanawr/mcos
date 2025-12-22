<?php 
ob_start(); 
include('partials-front/menu.php'); 

// 1. Check if user is logged in
if(empty($_SESSION["u_id"])) {
    header('location:login.php');
    exit;
}

$u_id = $_SESSION['u_id'];

// 2. Fetch customer details for delivery address display
$sql_cust = "SELECT * FROM CUSTOMER WHERE cust_ID = $u_id";
$res_cust = mysqli_query($conn, $sql_cust);
$cust = mysqli_fetch_assoc($res_cust);

// 3. Calculate Totals from CART table
$sql_cart_total = "SELECT SUM(m.menu_price * c.quantity) as subtotal 
                   FROM CART c 
                   JOIN MENU m ON c.menu_ID = m.menu_ID 
                   WHERE c.cust_ID = $u_id";
$res_total = mysqli_query($conn, $sql_cart_total);
$row_total = mysqli_fetch_assoc($res_total);

$subtotal = $row_total['subtotal'] ?? 0;
$delivery = 2.00;
$grand_total = $subtotal + $delivery;

// If cart is empty, redirect back to foods
if($subtotal == 0) {
    header('location:foods.php');
    exit;
}

// 4. Handle Payment Submission
if(isset($_POST['confirm_payment'])) {
    $payment_method = $_POST['bank_name'];
    
    if(isset($_FILES['receipt']['name']) && $_FILES['receipt']['name'] != "") {
        $ext = pathinfo($_FILES['receipt']['name'], PATHINFO_EXTENSION);
        $receipt_name = "Receipt-".time()."-".rand(000,999).".".$ext;
        $source_path = $_FILES['receipt']['tmp_name'];
        $destination_path = "images/receipts/".$receipt_name;

        if(!is_dir('images/receipts/')) mkdir('images/receipts/', 0777, true);

        if(move_uploaded_file($source_path, $destination_path)) {
            
            // A. Insert into `ORDER` (Staff ID 2 is default handler)
            $sql1 = "INSERT INTO `ORDER` (grand_total, delivery_charge, cust_ID, staff_ID) 
                    VALUES ($grand_total, $delivery, $u_id, 2)";
            
            if(mysqli_query($conn, $sql1)) {
                $order_id = mysqli_insert_id($conn);

                // B. Move items from CART to ORDER_MENU
                $sql_get_cart = "SELECT c.*, m.menu_price FROM CART c 
                                JOIN MENU m ON c.menu_ID = m.menu_ID 
                                WHERE c.cust_ID = $u_id";
                $res_get_cart = mysqli_query($conn, $sql_get_cart);

                while($item = mysqli_fetch_assoc($res_get_cart)) {
                    $m_id = $item['menu_ID'];
                    $qty = $item['quantity'];
                    $st = $item['menu_price'] * $qty;

                    $sql2 = "INSERT INTO ORDER_MENU (order_ID, menu_ID, order_quantity, sub_total) 
                            VALUES ($order_id, $m_id, $qty, $st)";
                    mysqli_query($conn, $sql2);
                }

                // C. Insert into PAYMENT
                $sql3 = "INSERT INTO PAYMENT (amount_paid, payment_method, payment_status, receipt_file, order_ID) 
                         VALUES ($grand_total, '$payment_method', 'Pending Verification', '$receipt_name', $order_id)";
                mysqli_query($conn, $sql3);

                // D. CLEAR CART
                mysqli_query($conn, "DELETE FROM CART WHERE cust_ID = $u_id");

                $_SESSION['pay_notice'] = "<div class='success text-center'>Order successfully placed!</div>";
                header('location:myorders.php');
                exit;
            }
        }
    }
}
?>

<section class="payment-section" style="background-color: #f1f2f6; padding: 4% 0;">
    <div class="container">
        <div style="background: white; padding: 40px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); max-width: 600px; margin: 0 auto;">
            
            <h2 class="text-center" style="color: #2f3542; margin-bottom: 20px;">Confirm & Pay</h2>

            <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; margin-bottom: 25px; border-left: 5px solid #2ecc71;">
                <p><strong>Deliver to:</strong> <?php echo $cust['cust_dorm']; ?></p>
                <p><strong>Contact:</strong> <?php echo $cust['cust_contact_no']; ?></p>
                <hr>
                <p>Subtotal: RM <?php echo number_format($subtotal, 2); ?></p>
                <p>Delivery: RM <?php echo number_format($delivery, 2); ?></p>
                <p style="font-size: 1.2rem; color: #ff4757;"><strong>Total to Pay: RM <?php echo number_format($grand_total, 2); ?></strong></p>
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
                    <input type="file" name="receipt" required class="form-control">
                </div>

                <button type="submit" name="confirm_payment" class="btn-primary" style="width:100%; padding:12px; background:#2ecc71; border:none; color:white; border-radius:5px; cursor:pointer; font-weight:bold;">Confirm Payment</button>
                <div class="text-center" style="margin-top:15px;">
                    <a href="cart.php" style="color:#747d8c; text-decoration:none;">Cancel & Back to Cart</a>
                </div>
            </form>
        </div>
    </div>
</section>

<?php include('partials-front/footer.php'); ?>
