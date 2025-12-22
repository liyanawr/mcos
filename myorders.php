<?php include('partials-front/menu.php'); ?>

<section class="food-search text-center">
    <div class="container">
        <form action="" method="POST">
            <input type="search" name="search" placeholder="Search Order ID, Date, or Status.." required>
            <input type="submit" name="submit" value="Search" class="btn btn-primary">
        </form>
    </div>
</section>
<div class="main-content" style="background-color: #f1f2f6; padding: 3% 0;">
    <div class="wrapper" style="max-width: 1100px; margin: 0 auto; padding: 0 20px;">
        
        <h2 class="text-center" style="margin-bottom: 30px; color: #2f3542;">My Order History</h2>

        <?php 
            // Check if search is performed
            if(isset($_POST['search'])) {
                $search = mysqli_real_escape_string($conn, $_POST['search']);
                echo "<p class='text-center'>Results for: <b>\"$search\"</b> | <a href='myorders.php' style='color: #ff4757;'>Clear Search</a></p><br>";
            }
        ?>

        <div style="background: #2f3542; color: white; padding: 25px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
            
            <table style="width: 100%; border-collapse: collapse; background: rgba(255,255,255,0.05); border-radius: 10px; overflow: hidden;">
                <thead>
                    <tr style="background: rgba(255,255,255,0.1); text-align: left;">
                        <th style="padding: 15px;">No.</th>
                        <th style="padding: 15px;">Order ID</th>
                        <th style="padding: 15px;">Date</th>
                        <th style="padding: 15px;">Status</th>
                        <th style="padding: 15px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        // 1. Get current customer ID from session
                        $cust_id = $_SESSION['u_id'];

                        // 2. Base SQL Query
                        $sql = "SELECT o.order_ID, o.order_date, d.delivery_status 
                                FROM `ORDER` o 
                                LEFT JOIN DELIVERY d ON o.order_ID = d.order_ID 
                                WHERE o.cust_id = $cust_id";

                        // 3. If searching, add search filters
                        if(isset($_POST['search'])) {
                            $sql .= " AND (o.order_ID LIKE '%$search%' 
                                      OR o.order_date LIKE '%$search%' 
                                      OR d.delivery_status LIKE '%$search%')";
                        }

                        $sql .= " ORDER BY o.order_ID DESC";

                        $res = mysqli_query($conn, $sql);
                        $count = mysqli_num_rows($res);
                        $sn = 1;

                        if($count > 0) {
                            while($row = mysqli_fetch_assoc($res)) {
                                $order_id = $row['order_ID'];
                                $order_date = $row['order_date'];
                                $status = $row['delivery_status'] ?? "Pending";
                                ?>

                                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                    <td style="padding: 15px;"><?php echo $sn++; ?>.</td>
                                    <td style="padding: 15px; font-weight: bold; color: #ff4757;">#<?php echo $order_id; ?></td>
                                    <td style="padding: 15px;"><?php echo date('d M Y', strtotime($order_date)); ?></td>
                                    <td style="padding: 15px;">
                                        <?php 
                                            if($status == "Ordered" || $status == "Pending") {
                                                echo "<span style='background: #57606f; padding: 4px 10px; border-radius: 5px; font-size: 0.85rem;'>$status</span>";
                                            } elseif($status == "On Delivery") {
                                                echo "<span style='background: #e67e22; padding: 4px 10px; border-radius: 5px; font-size: 0.85rem;'>$status</span>";
                                            } elseif($status == "Delivered") {
                                                echo "<span style='background: #2ecc71; padding: 4px 10px; border-radius: 5px; font-size: 0.85rem;'>$status</span>";
                                            } elseif($status == "Cancelled") {
                                                echo "<span style='background: #ff4757; padding: 4px 10px; border-radius: 5px; font-size: 0.85rem;'>$status</span>";
                                            }
                                        ?>
                                    </td>
                                    <td style="padding: 15px;">
                                        <a href="<?php echo SITEURL; ?>order-details.php?id=<?php echo $order_id; ?>" 
                                           style="color: #3498db; text-decoration: none; font-weight: bold; border: 1px solid #3498db; padding: 5px 12px; border-radius: 5px; transition: 0.3s;">
                                           View Details
                                        </a>
                                    </td>
                                </tr>

                                <?php
                            }
                        } else {
                            echo "<tr><td colspan='5' style='padding: 30px; text-align: center; color: #a4b0be;'>No orders found matching your search.</td></tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
        
        <div class="clearfix"></div>
    </div>
</div>

<?php include('partials-front/footer.php'); ?>
