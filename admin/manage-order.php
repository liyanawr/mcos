<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1 class="text-center">Manage Orders</h1>
        <br /><br />

        <table class="table-full" style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
            <thead>
                <tr style="border-bottom: 2px solid #f1f2f6; text-align: left; background-color: #f8f9fa;">
                    <th style="padding: 10px;">ID</th>
                    <th style="padding: 10px;">Customer</th>
                    <th style="padding: 10px;">Total</th>
                    <th style="padding: 10px;">Status</th>
                    <th style="padding: 10px;">Payment</th>
                    <th style="padding: 10px;">Receipt</th>
                    <th style="padding: 10px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    // Join ORDER with DELIVERY and PAYMENT and CUSTOMER
                    $sql = "SELECT o.order_ID, c.cust_username, o.grand_total, d.delivery_status, p.payment_status, p.receipt_file 
                            FROM `ORDER` o 
                            JOIN CUSTOMER c ON o.cust_ID = c.cust_ID 
                            LEFT JOIN DELIVERY d ON o.order_ID = d.order_ID 
                            LEFT JOIN PAYMENT p ON o.order_ID = p.order_ID 
                            ORDER BY o.order_ID DESC";

                    $res = mysqli_query($conn, $sql);

                    if(mysqli_num_rows($res) > 0) {
                        while($row = mysqli_fetch_assoc($res)) {
                            $order_id = $row['order_ID'];
                            $customer = $row['cust_username'];
                            $total = $row['grand_total'];
                            $status = $row['delivery_status'] ?? "Ordered";
                            $p_status = $row['payment_status'] ?? "Unpaid";
                            $receipt = $row['receipt_file'];
                            ?>
                            <tr style="border-bottom: 1px solid #f1f2f6;">
                                <td style="padding: 10px;">#<?php echo $order_id; ?></td>
                                <td style="padding: 10px;"><?php echo $customer; ?></td>
                                <td style="padding: 10px;">RM <?php echo $total; ?></td>
                                <td style="padding: 10px;">
                                    <span style="color: <?php echo ($status=='Delivered')?'green':'orange'; ?>; font-weight:bold;">
                                        <?php echo $status; ?>
                                    </span>
                                </td>
                                <td style="padding: 10px;"><?php echo $p_status; ?></td>
                                <td style="padding: 10px;">
                                    <?php if($receipt != "") { ?>
                                        <a href="../images/receipts/<?php echo $receipt; ?>" target="_blank" style="color: blue;">View Receipt</a>
                                    <?php } else { echo "No File"; } ?>
                                </td>
                                <td style="padding: 10px;">
                                    <a href="update-order.php?id=<?php echo $order_id; ?>" class="btn-secondary" style="background-color: #3498db; padding: 5px 10px; border-radius: 3px; text-decoration: none; color: white;">Update Status</a>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='7' class='error'>No Orders Found.</td></tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('partials/footer.php'); ?>