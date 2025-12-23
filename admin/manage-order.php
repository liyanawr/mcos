<?php include('partials/menu.php'); ?>

<div class="main-content" style="background-color: #f1f2f6; padding: 3% 0;">
    <div class="wrapper">
        <h1 class="text-center" style="margin-bottom: 30px; color: #2f3542;">Manage Orders</h1>

        <div style="background: #2f3542; color: white; padding: 25px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
            <table style="width: 100%; border-collapse: collapse; background: rgba(255,255,255,0.05); border-radius: 10px; overflow: hidden;">
                <thead>
                    <tr style="background: rgba(255,255,255,0.1); text-align: left;">
                        <th style="padding: 15px;">ID</th>
                        <th style="padding: 15px;">Customer</th>
                        <th style="padding: 15px;">Total</th>
                        <th style="padding: 15px;">Status</th>
                        <th style="padding: 15px;">Staff Handled</th>
                        <th style="padding: 15px;">Receipt</th>
                        <th style="padding: 15px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        // Query joining ORDER, CUSTOMER, DELIVERY, PAYMENT, and STAFF
                        $sql = "SELECT o.order_ID, c.cust_username, o.grand_total, d.delivery_status, 
                                       p.payment_status, p.receipt_file, s.staff_username
                                FROM `ORDER` o 
                                JOIN CUSTOMER c ON o.cust_ID = c.cust_ID 
                                LEFT JOIN DELIVERY d ON o.order_ID = d.order_ID 
                                LEFT JOIN PAYMENT p ON o.order_ID = p.order_ID 
                                LEFT JOIN STAFF s ON o.staff_ID = s.staff_ID
                                ORDER BY o.order_ID DESC";

                        $res = mysqli_query($conn, $sql);

                        if(mysqli_num_rows($res) > 0) {
                            while($row = mysqli_fetch_assoc($res)) {
                                $order_id = $row['order_ID'];
                                $customer = $row['cust_username'];
                                $total = $row['grand_total'];
                                $status = $row['delivery_status'] ?? "Ordered";
                                $handled_by = $row['staff_username'] ?? "<i>Unassigned</i>";
                                $receipt = $row['receipt_file'];
                                ?>
                                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                    <td style="padding: 15px; font-weight: bold; color: #ff4757;">#<?php echo $order_id; ?></td>
                                    <td style="padding: 15px;"><?php echo $customer; ?></td>
                                    <td style="padding: 15px;">RM <?php echo number_format($total, 2); ?></td>
                                    <td style="padding: 15px;">
                                        <?php 
                                            $color = ($status == 'Delivered') ? '#2ecc71' : (($status == 'Cancelled') ? '#ff4757' : '#e67e22');
                                            echo "<span style='background: $color; padding: 4px 10px; border-radius: 5px; font-size: 0.8rem; font-weight: bold;'>$status</span>";
                                        ?>
                                    </td>
                                    <td style="padding: 15px; font-size: 0.85rem; color: #a4b0be;"><?php echo $handled_by; ?></td>
                                    <td style="padding: 15px;">
                                        <?php if($receipt != ""): ?>
                                            <a href="../images/receipts/<?php echo $receipt; ?>" target="_blank" style="color: #3498db; text-decoration: none; font-weight: bold;">View</a>
                                        <?php else: ?>
                                            <small style="color:#747d8c;">No File</small>
                                        <?php endif; ?>
                                    </td>
                                    <td style="padding: 15px;">
                                        <a href="update-order.php?id=<?php echo $order_id; ?>" 
                                           style="color: white; background: #3498db; padding: 5px 12px; border-radius: 5px; text-decoration: none; font-size: 0.8rem; font-weight: bold;">
                                           Update
                                        </a>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo "<tr><td colspan='7' style='padding: 30px; text-align: center; color: #a4b0be;'>No orders found.</td></tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include('partials/footer.php'); ?>
