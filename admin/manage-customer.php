<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1 class="text-center">Manage Customers</h1>
        <br /><br />

        <table class="table-full" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid #f1f2f6; text-align: left;">
                    <th style="padding: 10px;">No</th>
                    <th style="padding: 10px;">Full Name</th>
                    <th style="padding: 10px;">Dorm</th>
                    <th style="padding: 10px;">Contact</th>
                    <th style="padding: 10px;">Bank Info</th>
                    <th style="padding: 10px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $sql = "SELECT * FROM CUSTOMER";
                    $res = mysqli_query($conn, $sql);

                    if($res == TRUE) {
                        $count = mysqli_num_rows($res);
                        $sn = 1;

                        if($count > 0) {
                            while($rows = mysqli_fetch_assoc($res)) {
                                $id = $rows['cust_ID'];
                                $full_name = $rows['cust_first_name']." ".$rows['cust_last_name'];
                                $dorm = $rows['cust_dorm'];
                                $contact = $rows['cust_contact_no'];
                                $bank = $rows['bank_name']." (".$rows['bank_account'].")";
                                ?>
                                <tr style="border-bottom: 1px solid #f1f2f6;">
                                    <td style="padding: 10px;"><?php echo $sn++; ?>. </td>
                                    <td style="padding: 10px;"><?php echo $full_name; ?></td>
                                    <td style="padding: 10px;"><?php echo $dorm; ?></td>
                                    <td style="padding: 10px;"><?php echo $contact; ?></td>
                                    <td style="padding: 10px; font-size: 0.85rem; color: #7f8c8d;"><?php echo $bank; ?></td>
                                    <td style="padding: 10px;">
                                        <a href="delete-customer.php?id=<?php echo $id; ?>" class="btn-danger" style="background-color: #e74c3c; padding: 5px 10px; font-size: 0.8rem; border-radius: 3px; text-decoration: none; color: white;">Remove User</a>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo "<tr><td colspan='6' class='error'>No Customers Registered.</td></tr>";
                        }
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('partials/footer.php'); ?>