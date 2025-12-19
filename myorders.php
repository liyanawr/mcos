<?php include('partials-front/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1 class="text-center">My Orders</h1>
        <br /><br />

        <center>
        <table class="content-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Order ID</th>
                    <th>Order Date</th>
                    <th>Delivery Status</th>
                    <th>Details</th>
                    <th>Give Feedback</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    // 1. Get current customer ID from session (assuming you stored it as u_id during login)
                    $cust_id = $_SESSION['u_id'];

                    // 2. Updated SQL Query to join ORDER and DELIVERY tables
                    // We use LEFT JOIN in case a delivery record hasn't been created yet
                    $sql = "SELECT o.order_ID, o.order_date, d.delivery_status 
                            FROM `ORDER` o 
                            LEFT JOIN DELIVERY d ON o.order_ID = d.order_ID 
                            WHERE o.cust_id = $cust_id 
                            ORDER BY o.order_ID DESC";

                    $res = mysqli_query($conn, $sql);
                    $count = mysqli_num_rows($res);
                    $sn = 1;

                    if($count > 0) {
                        while($row = mysqli_fetch_assoc($res)) {
                            $order_id = $row['order_ID'];
                            $order_date = $row['order_date'];
                            $status = $row['delivery_status'] ?? "Pending"; // Default to Pending if no delivery record
                            ?>

                            <tr>
                                <td><?php echo $sn++; ?>.</td>
                                <td>#<?php echo $order_id; ?></td>
                                <td><?php echo $order_date; ?></td>
                                <td>
                                    <?php 
                                        // Dynamic styling based on delivery status
                                        if($status == "Ordered" || $status == "Pending") {
                                            echo "<label>$status</label>";
                                        } elseif($status == "On Delivery") {
                                            echo "<label style='color: orange;'>$status</label>";
                                        } elseif($status == "Delivered") {
                                            echo "<label style='color: green;'>$status</label>";
                                        } elseif($status == "Cancelled") {
                                            echo "<label style='color: red;'>$status</label>";
                                        }
                                    ?>
                                </td>
                                <td>
                                    <a href="<?php echo SITEURL; ?>order-details.php?id=<?php echo $order_id; ?>" class="btn-secondary">View Details</a>
                                </td>
                            </tr>

                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='6' class='error text-center'>You have not placed any orders yet!!!</td></tr>";
                    }
                ?>
            </tbody>
        </table>
        </center>
    </div>
</div>

<?php include('partials-front/footer.php'); ?>