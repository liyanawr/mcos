<?php 
ob_start();
include('partials/menu.php'); 

$staff_id = $_SESSION['u_id'];

// 1. Fetch Staff & Supervisor details
$sql = "SELECT s1.*, s2.staff_first_name AS super_fname, s2.staff_last_name AS super_lname, s2.staff_contact_no AS super_contact
        FROM staff s1
        LEFT JOIN staff s2 ON s1.supervisor_ID = s2.staff_ID
        WHERE s1.staff_ID = $staff_id";
$res = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($res);

// 2. Fetch Employment Type
$is_full_time = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM full_time WHERE staff_ID = $staff_id"));
$is_part_time = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM part_time WHERE staff_ID = $staff_id"));
$emp_type = ($is_full_time > 0) ? "Full-Time Staff" : (($is_part_time > 0) ? "Part-Time Staff" : "Not Assigned");

// 3. Fetch Work Logs
$sql_logs = "SELECT * FROM work_log WHERE staff_ID = $staff_id ORDER BY work_date DESC LIMIT 5";
$res_logs = mysqli_query($conn, $sql_logs);
?>

<div class="main-content" style="background-color: #f1f2f6; padding: 3% 0;">
    <div class="wrapper" style="max-width: 1100px; margin: 0 auto;">
        <h1 style="margin-bottom: 25px; color: #2f3542;">My Profile (Staff)</h1>
        
        <?php if(isset($_SESSION['msg'])) { echo $_SESSION['msg']; unset($_SESSION['msg']); } ?>

        <div style="background: #2f3542; color: white; padding: 35px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); margin-bottom: 30px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 1px solid #57606f; padding-bottom: 20px; margin-bottom: 25px;">
                <div>
                    <h3 style="color: #ff4757; margin-top: 0;">Employment Info</h3>
                    <p style="margin: 10px 0;"><strong>Status:</strong> <span style="background:#2ecc71; color:white; padding: 4px 12px; border-radius: 20px; font-size: 0.85rem;"><?php echo $emp_type; ?></span></p>
                    <p style="margin: 5px 0;"><strong>Username:</strong> <?php echo $row['staff_username']; ?></p>
                </div>
                <div style="text-align: right;">
                    <h3 style="color: #ff4757; margin-top: 0;">Supervisor Details</h3>
                    <?php if($row['supervisor_ID']): ?>
                        <p style="margin: 5px 0;"><strong>Name:</strong> <?php echo $row['super_fname'].' '.$row['super_lname']; ?></p>
                        <p style="margin: 5px 0;"><strong>Contact:</strong> <?php echo $row['super_contact']; ?></p>
                    <?php else: ?>
                        <p>Administrator (Direct Access)</p>
                    <?php endif; ?>
                </div>
            </div>

            <h4 style="color: #ff4757; margin-bottom: 15px;">Recent Work Logs</h4>
            <table style="width: 100%; border-collapse: collapse; background: rgba(255,255,255,0.05); border-radius: 10px; overflow: hidden;">
                <thead>
                    <tr style="background: rgba(255,255,255,0.1); text-align: left;">
                        <th style="padding: 12px;">Date</th>
                        <th style="padding: 12px;">Day</th>
                        <th style="padding: 12px;">Hours Worked</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($res_logs) > 0): ?>
                        <?php while($log = mysqli_fetch_assoc($res_logs)): ?>
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                <td style="padding: 12px;"><?php echo $log['work_date']; ?></td>
                                <td style="padding: 12px;"><?php echo $log['day_present']; ?></td>
                                <td style="padding: 12px;"><?php echo number_format($log['hours_worked'], 2); ?> hrs</td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="3" style="padding: 20px; text-align: center; color: #a4b0be;">No work logs found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div style="display: flex; gap: 30px; flex-wrap: wrap;">
            
            <div style="flex: 1; min-width: 450px; background: white; padding: 35px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                <h3 style="border-bottom: 2px solid #f1f2f6; padding-bottom: 15px; margin-bottom: 25px;">Update Personal Details</h3>
                <form action="" method="POST">
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label style="display:block; margin-bottom:8px; font-weight: 600;">First Name</label>
                        <input type="text" name="fname" value="<?php echo $row['staff_first_name']; ?>" class="form-control" style="width:100%; padding:12px; border-radius:8px; border:1px solid #ddd;" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label style="display:block; margin-bottom:8px; font-weight: 600;">Last Name</label>
                        <input type="text" name="lname" value="<?php echo $row['staff_last_name']; ?>" class="form-control" style="width:100%; padding:12px; border-radius:8px; border:1px solid #ddd;" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 25px;">
                        <label style="display:block; margin-bottom:8px; font-weight: 600;">Contact Number</label>
                        <input type="text" name="contact" value="<?php echo $row['staff_contact_no']; ?>" class="form-control" style="width:100%; padding:12px; border-radius:8px; border:1px solid #ddd;" required>
                    </div>
                    <input type="submit" name="update_profile" value="Save Changes" class="btn btn-primary" style="width:100%; background:#3742fa; color:white; border:none; padding:14px; border-radius:8px; font-weight:bold; cursor: pointer;">
                </form>
            </div>

            <div style="flex: 1; min-width: 450px; background: white; padding: 35px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                <h3 style="border-bottom: 2px solid #f1f2f6; padding-bottom: 15px; margin-bottom: 25px;">Security</h3>
                <form action="" method="POST">
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label style="display:block; margin-bottom:8px; font-weight: 600;">Current Password</label>
                        <input type="password" name="curr_pwd" class="form-control" style="width:100%; padding:12px; border-radius:8px; border:1px solid #ddd;" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label style="display:block; margin-bottom:8px; font-weight: 600;">New Password</label>
                        <input type="password" name="new_pwd" class="form-control" style="width:100%; padding:12px; border-radius:8px; border:1px solid #ddd;" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 25px;">
                        <label style="display:block; margin-bottom:8px; font-weight: 600;">Confirm Password</label>
                        <input type="password" name="conf_pwd" class="form-control" style="width:100%; padding:12px; border-radius:8px; border:1px solid #ddd;" required>
                    </div>
                    <input type="submit" name="change_pwd" value="Change Password" class="btn btn-danger" style="width:100%; background:#ff4757; color:white; border:none; padding:14px; border-radius:8px; font-weight:bold; cursor: pointer;">
                </form>
            </div>

        </div>
    </div>
</div>

<?php include('partials/footer.php'); ?>