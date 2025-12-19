<?php 
ob_start();
include('partials-front/menu.php'); // Assuming customer menu is here

if(!isset($_SESSION['u_id']) || $_SESSION['user_role'] !== 'customer') {
    header('location: login.php');
    exit();
}

$cust_id = $_SESSION['u_id'];
$sql = "SELECT * FROM CUSTOMER WHERE cust_ID = $cust_id";
$res = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($res);

// Update Customer Info
if(isset($_POST['update_cust'])) {
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $dorm = mysqli_real_escape_string($conn, $_POST['dorm']);
    $bank = mysqli_real_escape_string($conn, $_POST['bank']);
    $acc = mysqli_real_escape_string($conn, $_POST['acc']);

    $sql_up = "UPDATE CUSTOMER SET 
        cust_first_name='$fname', cust_last_name='$lname', 
        cust_dorm='$dorm', bank_name='$bank', bank_account='$acc' 
        WHERE cust_ID=$cust_id";

    mysqli_query($conn, $sql_up);
    header("location: manage-profile.php");
    exit();
}
?>

<div class="main-content" style="background-color: #f1f2f6; padding: 50px 0;">
    <div class="container" style="max-width: 900px; margin: 0 auto;">
        <h2 class="text-center">My Account</h2><br>

        <div style="display: flex; gap: 20px;">
            <div style="flex: 2; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                <form action="" method="POST">
                    <div style="display:flex; gap:10px;">
                        <div style="flex:1;"><label>First Name</label><input type="text" name="fname" value="<?php echo $row['cust_first_name']; ?>" class="form-control"></div>
                        <div style="flex:1;"><label>Last Name</label><input type="text" name="lname" value="<?php echo $row['cust_last_name']; ?>" class="form-control"></div>
                    </div><br>
                    <label>Dorm/Address</label>
                    <input type="text" name="dorm" value="<?php echo $row['cust_dorm']; ?>" class="form-control"><br>
                    <label>Bank Name</label>
                    <input type="text" name="bank" value="<?php echo $row['bank_name']; ?>" class="form-control"><br>
                    <label>Bank Account No</label>
                    <input type="text" name="acc" value="<?php echo $row['bank_account']; ?>" class="form-control"><br>
                    <input type="submit" name="update_cust" value="Save Changes" class="btn-primary" style="padding:10px 20px; width:100%; border:none; background:#ff4757; color:white; border-radius:5px;">
                </form>
            </div>

            <div style="flex: 1; background: #2f3542; color: white; padding: 30px; border-radius: 15px; text-align: center;">
                <img src="images/user-icon.png" style="width:80px; margin-bottom:15px;">
                <h4><?php echo $row['cust_username']; ?></h4>
                <p style="color: #a4b0be;">Customer ID: #<?php echo $cust_id; ?></p>
                <hr style="border:0; border-top:1px solid #57606f;">
                <a href="logout.php" style="color: #ff4757; text-decoration:none; font-weight:bold;">Logout</a>
            </div>
        </div>
    </div>
</div>

<?php include('partials-front/footer.php'); ?>