<?php include('partials-front/menu.php'); ?>

<div class="main-content" style="background-color: #f1f2f6; padding: 3% 0;">
    <div class="container">
        
        <div class="order-details-box" style="background-color: white; padding: 40px; border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
            
            <h1 class="text-center" style="margin-bottom: 30px; color: #2f3542;">Order Invoice</h1>
            
            <?php 
                if(isset($_GET['id'])) {
                    $order_id = $_GET['id'];

                    // SQL Query joining ORDER, DELIVERY, and STAFF tables
                    $sql = "SELECT o.*, d.delivery_status, d.delivery_time, d.delivery_date, 
                                   s.staff_first_name, s.staff_last_name, s.staff_contact_no
                            FROM `ORDER` o 
                            LEFT JOIN DELIVERY d ON o.order_ID = d.order_ID 
                            LEFT JOIN STAFF s ON d.staff_ID = s.staff_ID 
                            WHERE o.order_ID = $order_id";

                    $res = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_assoc($res);

                    if($row) {
                        $order_date = $row['order_date'];
                        $grand_total = $row['grand_total'];
                        $delivery_charge = $row['delivery_charge'];
                        $status = $row['delivery_status'] ?? "Processing";
                        $staff_name = $row['staff_first_name'] ? $row['staff_first_name']." ".$row['staff_last_name'] : "Not Assigned Yet";
                    } else {
                        header('location:'.SITEURL.'myorders.php');
                    }
                } else {
                    header('location:'.SITEURL.'myorders.php');
                }
            ?>

            <div class="row" style="display: flex; justify-content: space-between; border-bottom: 2px solid #f1f2f6; padding-bottom: 20px; margin-bottom: 30px;">
                <div class="order-info-left">
                    <p><strong>Order ID:</strong> #<?php echo $order_id; ?></p>
                    <p><strong>Date:</strong> <?php echo $order_date; ?></p>
                    <p><strong>Delivery Staff:</strong> <?php echo $staff_name; ?></p>
                </div>
                <div class="order-info-right" style="text-align: right;">
                    <p><strong>Status:</strong></p>
                    <span style="padding: 8px 15px; border-radius: 20px; font-size: 0.9rem; font-weight: bold; background-color: <?php echo ($status=='Delivered') ? '#2ecc71' : '#f1c40f'; ?>; color: white;">
                        <?php echo $status; ?>
                    </span>
                </div>
            </div>

            <h3 style="color: #57606f; margin-bottom: 15px;">Items Ordered</h3>
            <table class="content-table" style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                <thead>
                    <tr style="background-color: #ff4757; color: white; text-align: left;">
                        <th style="padding: 12px;">No</th>
                        <th style="padding: 12px;">Item Name</th>
                        <th style="padding: 12px;">Price</th>
                        <th style="padding: 12px;">Qty</th>
                        <th style="padding: 12px;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $sql2 = "SELECT om.*, m.menu_name 
                                 FROM ORDER_MENU om 
                                 JOIN MENU m ON om.menu_ID = m.menu_ID 
                                 WHERE om.order_ID = $order_id";
                        
                        $res2 = mysqli_query($conn, $sql2);
                        $sn = 1;
                        while($item = mysqli_fetch_assoc($res2)) {
                            ?>
                            <tr style="border-bottom: 1px solid #f1f2f6;">
                                <td style="padding: 12px;"><?php echo $sn++; ?></td>
                                <td style="padding: 12px;"><?php echo $item['menu_name']; ?></td>
                                <td style="padding: 12px;">RM <?php echo number_format($item['sub_total'] / $item['order_quantity'], 2); ?></td>
                                <td style="padding: 12px;"><?php echo $item['order_quantity']; ?></td>
                                <td style="padding: 12px;">RM <?php echo number_format($item['sub_total'], 2); ?></td>
                            </tr>
                            <?php
                        }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" style="text-align: right; padding: 10px;">Delivery Charge:</td>
                        <td style="padding: 10px;">RM <?php echo number_format($delivery_charge, 2); ?></td>
                    </tr>
                    <tr style="font-size: 1.2rem; font-weight: bold;">
                        <td colspan="4" style="text-align: right; padding: 10px;">Grand Total:</td>
                        <td style="padding: 10px; color: #ff4757;">RM <?php echo number_format($grand_total, 2); ?></td>
                    </tr>
                </tfoot>
            </table>
            
            <div class="text-center" style="margin-top: 30px;">
            <a href="myorders.php" class="btn-secondary" style="padding: 10px 25px; text-decoration: none; border-radius: 5px; margin-right: 10px; background-color: #747d8c; color: white;">Back to My Orders</a>
            
            <a href="feedback.php?id=<?php echo $order_id; ?>" class="btn-primary" style="padding: 10px 25px; text-decoration: none; border-radius: 5px; background-color: #ff4757; color: white;">Give Feedback</a>
            </div>
        </div>

    </div>
</div>

<?php include('partials-front/footer.php'); ?>