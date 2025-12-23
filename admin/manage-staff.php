<?php include('partials/menu.php'); ?>

<div class="main-content" style="background-color: #f1f2f6; padding: 3% 0;">
    <div class="wrapper">
        <h1 class="text-center" style="margin-bottom: 30px;">Manage Staff</h1>

        <?php if($is_admin): ?>
        <div style="text-align: right; margin-bottom: 20px;">
            <a href="add-staff.php" class="btn-action" style="background: #2ecc71; padding: 10px 20px; font-weight: bold;">+ Add New Staff</a>
        </div>
        <?php endif; ?>

        <table class="table-admin">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Full Name</th>
                    <th>Username</th>
                    <th>Status</th>
                    <th>Contact</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $sql = "SELECT s.*, 
                            CASE 
                                WHEN ft.staff_ID IS NOT NULL THEN 'Full-Time'
                                WHEN pt.staff_ID IS NOT NULL THEN 'Part-Time'
                                ELSE 'Unassigned'
                            END AS emp_status
                            FROM STAFF s
                            LEFT JOIN FULL_TIME ft ON s.staff_ID = ft.staff_ID
                            LEFT JOIN PART_TIME pt ON s.staff_ID = pt.staff_ID";
                    $res = mysqli_query($conn, $sql);
                    $sn = 1;

                    while($rows = mysqli_fetch_assoc($res)) {
                        $id = $rows['staff_ID'];
                        $full_name = $rows['staff_first_name']." ".$rows['staff_last_name'];
                        $username = $rows['staff_username'];
                        $status = $rows['emp_status'];
                        $contact = $rows['staff_contact_no'];
                ?>
                <tr>
                    <td><?php echo $sn++; ?>.</td>
                    <td><?php echo $full_name; ?></td>
                    <td><?php echo $username; ?></td>
                    <td>
                        <span style="background: #f1f2f6; padding: 4px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: bold;">
                            <?php echo $status; ?>
                        </span>
                    </td>
                    <td><?php echo $contact; ?></td>
                    <td>
                        <?php if($is_admin && $id != 1): // Admin cannot delete themselves ?>
                            <a href="update-staff.php?id=<?php echo $id; ?>" class="btn-action" style="background: #3498db;">Update & Role</a>
                            <a href="delete-staff.php?id=<?php echo $id; ?>" class="btn-action" style="background: #e74c3c;">Delete</a>
                        <?php else: ?>
                            <span style="color: #a4b0be; font-size: 0.8rem;">View Only</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<?php include('partials/footer.php'); ?>
