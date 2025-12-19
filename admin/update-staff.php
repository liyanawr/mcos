<?php 
    // 1. Logic MUST be at the top to avoid "Header already sent" errors
    ob_start(); 
    include('partials/menu.php'); 

    // 2. Fetch current staff data
    if(isset($_GET['id'])) {
        $id = mysqli_real_escape_string($conn, $_GET['id']);
        
        // Use STAFF table instead of tbl_admin
        $sql = "SELECT * FROM STAFF WHERE staff_ID=$id";
        $res = mysqli_query($conn, $sql);

        if($res == true && mysqli_num_rows($res) == 1) {
            $row = mysqli_fetch_assoc($res);
            $first_name = $row['staff_first_name'];
            $last_name = $row['staff_last_name'];
            $username = $row['staff_username'];
            $contact = $row['staff_contact_no'];
        } else {
            header('location:'.SITEURL.'admin/manage-staff.php');
            exit();
        }
    }

    // 3. Process Update
    if(isset($_POST['submit'])) {
        $id = $_POST['id'];
        $f_name = mysqli_real_escape_string($conn, $_POST['first_name']);
        $l_name = mysqli_real_escape_string($conn, $_POST['last_name']);
        $u_name = mysqli_real_escape_string($conn, $_POST['username']);
        $cont = mysqli_real_escape_string($conn, $_POST['contact']);

        $sql2 = "UPDATE STAFF SET
            staff_first_name = '$f_name',
            staff_last_name = '$l_name',
            staff_username = '$u_name',
            staff_contact_no = '$cont'
            WHERE staff_ID=$id";

        if(mysqli_query($conn, $sql2)) {
            $_SESSION['update'] = "<div class='success'>Staff Updated Successfully.</div>";
            header('location:'.SITEURL.'admin/manage-staff.php');
            exit();
        } else {
            $_SESSION['update'] = "<div class='error'>Failed to Update Staff.</div>";
            header('location:'.SITEURL.'admin/manage-staff.php');
            exit();
        }
    }
?>

<div class="main-content" style="background-color: #f1f2f6; padding: 3% 0;">
    <div class="wrapper" style="max-width: 800px; margin: 0 auto; background: white; padding: 40px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        
        <h1 class="text-center" style="color: #2f3542; margin-bottom: 30px;">Update Staff Member</h1>

        <form action="" method="POST">
            <table class="table-no-border" style="width: 100%;">
                <tr style="height: 60px;">
                    <td style="width: 30%;"><strong>First Name:</strong></td>
                    <td><input type="text" name="first_name" class="form-control" value="<?php echo $first_name; ?>" style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 5px;"></td>
                </tr>
                <tr style="height: 60px;">
                    <td><strong>Last Name:</strong></td>
                    <td><input type="text" name="last_name" class="form-control" value="<?php echo $last_name; ?>" style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 5px;"></td>
                </tr>
                <tr style="height: 60px;">
                    <td><strong>Username:</strong></td>
                    <td><input type="text" name="username" class="form-control" value="<?php echo $username; ?>" style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 5px;"></td>
                </tr>
                <tr style="height: 60px;">
                    <td><strong>Contact No:</strong></td>
                    <td><input type="text" name="contact" class="form-control" value="<?php echo $contact; ?>" style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 5px;"></td>
                </tr>
                <tr>
                    <td colspan="2" style="padding-top: 30px; text-align: center;">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="submit" name="submit" value="Update Staff" class="btn-primary" style="padding: 12px 30px; border: none; background-color: #ff4757; color: white; border-radius: 5px; cursor: pointer; font-weight: bold;">
                        <a href="manage-staff.php" style="margin-left: 15px; text-decoration: none; color: #747d8c;">Cancel</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<?php include('partials/footer.php'); ?>