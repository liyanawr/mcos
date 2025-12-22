<?php 
ob_start();
include('partials-front/menu.php'); 

// 1. Get the Food ID from the URL
if(isset($_GET['food_id'])) {
    $food_id = mysqli_real_escape_string($conn, $_GET['food_id']);

    // 2. Fetch Food Details from MENU table
    $sql = "SELECT * FROM MENU WHERE menu_ID = $food_id";
    $res = mysqli_query($conn, $sql);

    if(mysqli_num_rows($res) == 1) {
        $food = mysqli_fetch_assoc($res);
        $title = $food['menu_name'];
        $price = $food['menu_price'];
        $details = $food['menu_details'];
        $image_name = $food['menu_pict'];
    } else {
        header('location:'.SITEURL);
        exit();
    }
} else {
    header('location:'.SITEURL);
    exit();
}
?>

    <div class="main-content" style="background-color: #f1f2f6; padding: 50px 0;">
        <div class="container" style="max-width: 1000px; margin: 0 auto; padding: 0 20px;">
            
            <div style="background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1); display: flex; flex-wrap: wrap; margin-bottom: 40px;">
                <div style="flex: 1; min-width: 300px;">
                    <?php if($image_name == ""): ?>
                        <div class='error' style="padding: 100px; text-align: center; background: #eee;">Image not Available.</div>
                    <?php else: ?>
                        <img src="images/food/<?php echo $image_name; ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php endif; ?>
                </div>
                
                <div style="flex: 1; padding: 40px; min-width: 300px;">
                    <h2 style="color: #2f3542; margin-top: 0;"><?php echo $title; ?></h2>
                    <p style="font-size: 1.5rem; color: #ff4757; font-weight: bold; margin: 20px 0;">RM <?php echo number_format($price, 2); ?></p>
                    
                    <h4 style="color: #747d8c; margin-bottom: 10px;">Description</h4>
                    <p style="line-height: 1.6; color: #57606f; margin-bottom: 30px;"><?php echo $details; ?></p>
                    
                    <form action="add-to-cart.php" method="GET">
                        <input type="hidden" name="food_id" value="<?php echo $food_id; ?>">
                        <div style="margin-bottom: 20px;">
                            <label style="font-weight: bold; display: block; margin-bottom: 5px;">Quantity</label>
                            <input type="number" name="qty" value="1" min="1" style="padding: 10px; width: 80px; border-radius: 5px; border: 1px solid #ddd;">
                        </div>
                        <button type="submit" class="btn-primary" style="background: #2f3542; color: white; padding: 15px 30px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; width: 100%;">Add to Cart</button>
                    </form>
                </div>
            </div>

            <div style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
    <h3 style="border-bottom: 2px solid #f1f2f6; padding-bottom: 15px; margin-bottom: 25px; color: #2f3542;">Customer Reviews</h3>

    <?php 
        // We find feedback by looking for orders that contained this specific food item
        $sql_reviews = "SELECT f.*, c.cust_first_name, c.cust_last_name 
                        FROM FEEDBACK f 
                        JOIN CUSTOMER c ON f.cust_ID = c.cust_ID 
                        WHERE f.order_ID IN (
                            SELECT order_ID FROM ORDER_MENU WHERE menu_ID = $food_id
                        )
                        ORDER BY f.created_at DESC";
        
        $res_reviews = mysqli_query($conn, $sql_reviews);
        
        if($res_reviews && mysqli_num_rows($res_reviews) > 0) {
            while($review = mysqli_fetch_assoc($res_reviews)) {
                ?>
                <div style="border-bottom: 1px solid #f1f2f6; padding: 20px 0;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <strong style="color: #2f3542;"><?php echo $review['cust_first_name']." ".$review['cust_last_name']; ?></strong>
                        <small style="color: #a4b0be;"><?php echo date('d M Y', strtotime($review['created_at'])); ?></small>
                    </div>
                    <p style="color: #57606f; font-style: italic; margin: 0;">"<?php echo $review['feedback']; ?>"</p>
                </div>
                <?php
            }
        } else {
            echo "<p style='color: #a4b0be; text-align: center; padding: 20px;'>No reviews for this item yet.</p>";
        }
    ?>
</div>

    </div>
</div>

<?php include('partials-front/footer.php'); ?>