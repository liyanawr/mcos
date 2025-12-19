<?php include('partials/menu.php'); ?>

<div class="main-content" style="background-color: #f1f2f6; padding: 3% 0;">
    <div class="wrapper" style="max-width: 1200px; margin: 0 auto; background: white; padding: 40px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        <h1 class="text-center" style="color: #2f3542; margin-bottom: 30px;">Manage Staff</h1>

        <?php 
            if(isset($_SESSION['update'])) {
                echo $_SESSION['update'];
                unset($_SESSION['update']);
            }
            if(isset($_SESSION['delete'])) {
                echo $_SESSION['delete'];
                unset($_SESSION['delete']);
            }
        ?>

        <div class="text-right" style="margin-bottom: 20px;">
            <a href="add-staff.php" class="btn-primary" style="padding: 10px 20px; background-color: #2ecc71; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;">+ Add Staff</a>
        </div>

        <table class="table-full" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid #f1f2f6; text-align: left; background-color: #f8f9fa;">
                    <th style="padding: 15px;">No</th>
                    <th style="padding: 15px;">Full Name</th>
                    <th style="padding: 15px;">Username</th>
                    <th style="padding: 15px;">Contact</th>
                    <th style="padding: 15px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    // Query to get all staff
                    $sql = "SELECT * FROM STAFF";
                    $res = mysqli_query($conn, $sql);

                    if($res == TRUE) {
                        $count = mysqli_num_rows($res);
                        $sn = 1;

                        if($count > 0) {
                            while($rows = mysqli_fetch_assoc($res)) {
                                $id = $rows['staff_ID'];
                                $full_name = $rows['staff_first_name']." ".$rows['staff_last_name'];
                                $username = $rows['staff_username'];
                                $contact = $rows['staff_contact_no'];
                                ?>
                                <tr style="border-bottom: 1px solid #f1f2f6;">
                                    <td style="padding: 15px;"><?php echo $sn++; ?>. </td>
                                    <td style="padding: 15px;"><?php echo $full_name; ?></td>
                                    <td style="padding: 15px;"><?php echo $username; ?></td>
                                    <td style="padding: 15px;"><?php echo $contact; ?></td>
                                    <td style="padding: 15px;">
                                        <a href="update-staff.php?id=<?php echo $id; ?>" class="btn-secondary" style="background-color: #3498db; padding: 7px 12px; font-size: 0.85rem; border-radius: 5px; text-decoration: none; color: white; margin-right: 5px;">Update</a>
                                        <a href="delete-staff.php?id=<?php echo $id; ?>" class="btn-danger" style="background-color: #e74c3c; padding: 7px 12px; font-size: 0.85rem; border-radius: 5px; text-decoration: none; color: white;">Delete</a>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo "<tr><td colspan='5' style='padding: 20px; text-align: center; color: #e74c3c;'>No Staff Added Yet.</td></tr>";
                        }
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('partials/footer.php'); ?>