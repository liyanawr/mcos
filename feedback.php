<?php include('partials-front/menu.php'); ?>

<div class="main-content" style="background-color: #f1f2f6; padding: 3% 0;">
    <div class="container">
        <div class="feedback-box" style="background-color: white; padding: 40px; border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); max-width: 800px; margin: 0 auto;">
            
            <h1 class="text-center" style="margin-bottom: 10px; color: #2f3542;">Order Feedback</h1>
            
            <?php 
                if(isset($_GET['id'])) {
                    $order_id = $_GET['id'];
                    $cust_id = $_SESSION['u_id'];
                } else {
                    header('location:'.SITEURL.'myorders.php');
                    exit();
                }

                // 1. CHECK IF FEEDBACK ALREADY EXISTS
                $sql_check = "SELECT * FROM FEEDBACK WHERE order_ID = $order_id";
                $res_check = mysqli_query($conn, $sql_check);
                $already_submitted = mysqli_num_rows($res_check) > 0;

                // Handle Form Submission
                if(isset($_POST['submit_feedback']) && !$already_submitted) {
                    $feedback_text = mysqli_real_escape_string($conn, $_POST['feedback_text']);
                    $feedback_cat = $_POST['feedback_cat'];
                    
                    // Insert with order_ID
                    $sql_fb = "INSERT INTO FEEDBACK (feedback, cust_ID, order_ID, feedback_cat_ID) 
                               VALUES ('$feedback_text', $cust_id, $order_id, $feedback_cat)";
                    
                    if(mysqli_query($conn, $sql_fb)) {
                        echo "<div class='alert alert-success text-center' style='color: green; padding: 10px; background: #e6f9ed; border-radius: 5px;'>Thank you! Your feedback has been submitted.</div>";
                        $already_submitted = true; // Hide form after submission
                    } else {
                        echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
                    }
                }
            ?>

            <p class="text-center" style="color: #747d8c; margin-bottom: 30px;">Order ID: #<?php echo $order_id; ?></p>

            <?php if($already_submitted): ?>
                <div style="text-align: center; padding: 40px;">
                    <div style="font-size: 50px; margin-bottom: 10px;">✅</div>
                    <h3 style="color: #2f3542;">Feedback Already Received</h3>
                    <p style="color: #747d8c;">You have already submitted feedback for this order. Thank you for your input!</p>
                    <br>
                    <a href="myorders.php" class="btn-primary" style="padding: 10px 25px; text-decoration: none; border-radius: 5px; background-color: #3742fa; color: white;">View My Orders</a>
                </div>
            <?php else: ?>
                <div style="background-color: #f8f9fa; padding: 20px; border-radius: 10px; margin-bottom: 30px;">
                    <h4 style="margin-bottom: 10px; color: #57606f;">Items from this order:</h4>
                    <ul style="list-style: none; padding: 0;">
                        <?php 
                            $sql_items = "SELECT m.menu_name FROM ORDER_MENU om 
                                          JOIN MENU m ON om.menu_ID = m.menu_ID 
                                          WHERE om.order_ID = $order_id";
                            $res_items = mysqli_query($conn, $sql_items);
                            while($item = mysqli_fetch_assoc($res_items)) {
                                echo "<li style='padding: 5px 0; border-bottom: 1px solid #ddd;'>• ".$item['menu_name']."</li>";
                            }
                        ?>
                    </ul>
                </div>

                <form action="" method="POST">
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: bold;">Feedback Category</label>
                        <select name="feedback_cat" class="form-control" style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ced4da;" required>
                            <?php 
                                $sql_cat = "SELECT * FROM FEEDBACK_CATEGORY";
                                $res_cat = mysqli_query($conn, $sql_cat);
                                while($cat = mysqli_fetch_assoc($res_cat)) {
                                    echo "<option value='".$cat['feedback_cat_ID']."'>".$cat['feedback_cat_name']."</option>";
                                }
                            ?>
                        </select>
                    </div>

                    <div class="form-group" style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: bold;">Rating</label>
                        <select name="rating" class="form-control" style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ced4da;">
                            <option value="5">⭐⭐⭐⭐⭐ (Excellent)</option>
                            <option value="4">⭐⭐⭐⭐ (Good)</option>
                            <option value="3">⭐⭐⭐ (Average)</option>
                            <option value="2">⭐⭐ (Poor)</option>
                            <option value="1">⭐ (Very Bad)</option>
                        </select>
                    </div>

                    <div class="form-group" style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: bold;">Your Comments</label>
                        <textarea name="feedback_text" rows="5" class="form-control" style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ced4da;" placeholder="How was your food and delivery experience?" required></textarea>
                    </div>

                    <div class="text-center" style="margin-top: 30px;">
                        <a href="order-details.php?id=<?php echo $order_id; ?>" class="btn-secondary" style="padding: 10px 25px; text-decoration: none; border-radius: 5px; background-color: #747d8c; color: white; margin-right: 10px;">Return</a>
                        <button type="submit" name="submit_feedback" class="btn-primary" style="padding: 10px 25px; border: none; border-radius: 5px; background-color: #2ecc71; color: white; cursor: pointer;">Submit Feedback</button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include('partials-front/footer.php'); ?>
