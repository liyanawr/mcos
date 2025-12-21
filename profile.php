<?php 
ob_start();
include('partials-front/menu.php'); 

// Check if customer is logged in
if(!isset($_SESSION['u_id']) || $_SESSION['user_role'] !== 'customer') {
    header('location: login.php');
    exit();
}

$cust_id = $_SESSION['u_id'];

// 1. Fetch Customer details from CUSTOMER table
$sql = "SELECT * FROM CUSTOMER WHERE cust_ID = $cust_id";
$res = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($res);

// 2. Fetch Recent Orders from ORDER table (matches your SQL schema)
// Note: `ORDER` is a reserved keyword in SQL, so we use backticks
$sql_orders = "SELECT * FROM `ORDER` WHERE cust_ID = $cust_id ORDER BY order_date DESC LIMIT 5";
$res_orders = mysqli_query($conn, $sql_orders);

// Update Customer Info Logic
if(isset($_POST['update_cust'])) {
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $dorm = mysqli_real_escape_string($conn, $_POST['dorm']);
    $bank = mysqli_real_escape_string($conn, $_POST['bank']);
    $acc = mysqli_real_escape_string($conn, $_POST['acc']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);

    $sql_up = "UPDATE CUSTOMER SET 
        cust_first_name='$fname', 
        cust_last_name='$lname', 
        cust_dorm='$dorm', 
        bank_name='$bank', 
        bank_account='$acc',
        cust_contact_no='$contact'
        WHERE cust_ID=$cust_id";

    if(mysqli_query($conn, $sql_up)) {
        $_SESSION['msg'] = "<div style='color: #2ecc71; padding: 10px; text-align:center;'>Profile Updated Successfully.</div>";
        header("location: profile.php");
        exit();
    }
}
?>

<div class="main-content" style="background-color: #f1f2f6; padding: 3% 0;">
    <div class="wrapper" style="max-width: 1100px; margin: 0 auto; padding: 0 20px;">
        <h1 style="margin-bottom: 25px; color: #2f3542;">My Profile (Customer)</h1>
        
        <?php if(isset($_SESSION['msg'])) { echo $_SESSION['msg']; unset($_SESSION['msg']); } ?>

        <div style="background: #2f3542; color: white; padding: 35px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); margin-bottom: 30px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 1px solid #57606f; padding-bottom: 20px; margin-bottom: 25px;">
                <div>
                    <h3 style="color: #ff4757; margin-top: 0;">Account Information</h3>
                    <p style="margin: 10px 0;"><strong>Membership:</strong> <span style="background:#2ecc71; color:white; padding: 4px 12px; border-radius: 20px; font-size: 0.85rem;">Active Customer</span></p>
                    <p style="margin: 5px 0;"><strong>Username:</strong> <?php echo $row['cust_username']; ?></p>
                    <p style="margin: 5px 0;"><strong>Customer ID:</strong> #<?php echo $cust_id; ?></p>
                </div>
                <div style="text-align: right;">
                    <h3 style="color: #ff4757; margin-top: 0;">Contact Details</h3>
                    <p style="margin: 5px 0;"><strong>Phone:</strong> <?php echo $row['cust_contact_no'] ? $row['cust_contact_no'] : 'Not set'; ?></p>
                    <p style="margin: 5px 0;"><strong>Address/Dorm:</strong> <?php echo $row['cust_dorm']; ?></p>
                </div>
            </div>

            <h4 style="color: #ff4757; margin-bottom: 15px;">Recent Orders</h4>
            <table style="width: 100%; border-collapse: collapse; background: rgba(255,255,255,0.05); border-radius: 10px; overflow: hidden;">
                <thead>
                    <tr style="background: rgba(255,255,255,0.1); text-align: left;">
                        <th style="padding: 12px;">Order ID</th>
                        <th style="padding: 12px;">Date</th>
                        <th style="padding: 12px;">Total (RM)</th>
                        <th style="padding: 12px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($res_orders) > 0): ?>
                        <?php while($order = mysqli_fetch_assoc($res_orders)): ?>
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                <td style="padding: 12px;">#<?php echo $order['order_ID']; ?></td>
                                <td style="padding: 12px;"><?php echo date('d M Y', strtotime($order['order_date'])); ?></td>
                                <td style="padding: 12px;"><?php echo number_format($order['grand_total'], 2); ?></td>
                                <td style="padding: 12px;"><a href="order-details.php?id=<?php echo $order['order_ID']; ?>" style="color: #3498db; text-decoration:none;">View</a></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4" style="padding: 20px; text-align: center; color: #a4b0be;">You haven't placed any orders yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div style="display: flex; gap: 30px; flex-wrap: wrap;">
            
            <div style="flex: 1; min-width: 450px; background: white; padding: 35px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                <h3 style="border-bottom: 2px solid #f1f2f6; padding-bottom: 15px; margin-bottom: 25px;">Personal Details</h3>
                <form action="" method="POST">
                    <div style="display:flex; gap:15px; margin-bottom: 20px;">
                        <div style="flex:1;">
                            <label style="display:block; margin-bottom:8px; font-weight: 600;">First Name</label>
                            <input type="text" name="fname" value="<?php echo $row['cust_first_name']; ?>" style="width:100%; padding:12px; border-radius:8px; border:1px solid #ddd;" required>
                        </div>
                        <div style="flex:1;">
                            <label style="display:block; margin-bottom:8px; font-weight: 600;">Last Name</label>
                            <input type="text" name="lname" value="<?php echo $row['cust_last_name']; ?>" style="width:100%; padding:12px; border-radius:8px; border:1px solid #ddd;" required>
                        </div>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label style="display:block; margin-bottom:8px; font-weight: 600;">Contact Number</label>
                        <input type="text" name="contact" value="<?php echo $row['cust_contact_no']; ?>" style="width:100%; padding:12px; border-radius:8px; border:1px solid #ddd;" required>
                    </div>
                    <div style="margin-bottom: 25px;">
                        <label style="display:block; margin-bottom:8px; font-weight: 600;">Dorm/Address</label>
                        <input type="text" name="dorm" value="<?php echo $row['cust_dorm']; ?>" style="width:100%; padding:12px; border-radius:8px; border:1px solid #ddd;" required>
                    </div>
                    <input type="submit" name="update_cust" value="Update Profile" style="width:100%; background:#3742fa; color:white; border:none; padding:14px; border-radius:8px; font-weight:bold; cursor: pointer;">
                </form>
            </div>

            <div style="flex: 1; min-width: 450px; background: white; padding: 35px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                <h3 style="border-bottom: 2px solid #f1f2f6; padding-bottom: 15px; margin-bottom: 25px;">Billing Information</h3>
                <form action="" method="POST">
                    <div style="margin-bottom: 20px;">
                        <label style="display:block; margin-bottom:8px; font-weight: 600;">Bank Name</label>
                        <input type="text" name="bank" value="<?php echo $row['bank_name']; ?>" placeholder="e.g. Maybank" style="width:100%; padding:12px; border-radius:8px; border:1px solid #ddd;">
                    </div>
                    <div style="margin-bottom: 25px;">
                        <label style="display:block; margin-bottom:8px; font-weight: 600;">Bank Account Number</label>
                        <input type="text" name="acc" value="<?php echo $row['bank_account']; ?>" placeholder="e.g. 1234567890" style="width:100%; padding:12px; border-radius:8px; border:1px solid #ddd;">
                    </div>
                    
                    <div style="background: #fff9db; padding: 15px; border-radius: 8px; border-left: 4px solid #fcc419; margin-bottom: 20px;">
                        <p style="font-size: 0.85rem; color: #856404; margin: 0;">This information is used to verify payments for your food orders.</p>
                    </div>

                    <input type="submit" name="update_cust" value="Save Billing Details" style="width:100%; background:#ff4757; color:white; border:none; padding:14px; border-radius:8px; font-weight:bold; cursor: pointer;">
                </form>
            </div>

        </div>
    </div>
</div>

<?php include('partials-front/footer.php'); ?>
