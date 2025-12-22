<?php include('partials-front/menu.php'); ?>

<section class="food-search text-center">
    <div class="container">
        <form action="<?php echo SITEURL; ?>food-search.php" method="POST">
            <input type="search" name="search" placeholder="Search for Food.." required>
            <input type="submit" name="submit" value="Search" class="btn btn-primary">
        </form>
    </div>
</section>
<section class="food-menu">
    <div class="container">
        <h2 class="text-center">Food Menu</h2>

        <?php 
            // 1. Updated Table name to 'menu' and column to 'menu_availability'
            $sql = "SELECT * FROM menu WHERE menu_availability='1'";

            // Execute the Query
            $res = mysqli_query($conn, $sql);

            // Count Rows
            $count = mysqli_num_rows($res);

            // Check whether the foods are available or not
            if($count > 0)
            {
                // Foods Available
                while($row = mysqli_fetch_assoc($res))
                {
                    // 2. Map variables to your specific database columns
                    $id = $row['menu_ID'];
                    $title = $row['menu_name'];
                    $description = $row['menu_details'];
                    $price = $row['menu_price'];
                    $image_name = $row['menu_pict'];
                    ?>
                    
                    <div class="food-menu-box">
                        <div class="food-menu-img">
                            <?php 
                                // Check whether image available or not
                                if($image_name == "")
                                {
                                    echo "<div class='error'>Image not Available.</div>";
                                }
                                else
                                {
                                    ?>
                                    <img src="<?php echo SITEURL; ?>images/food/<?php echo $image_name; ?>" alt="<?php echo $title; ?>" class="img-responsive img-curve">
                                    <?php
                                }
                            ?>
                        </div>

                        <div class="food-menu-desc">
                            <h4><?php echo $title; ?></h4>
                            <p class="food-price">RM <?php echo $price; ?></p>
                            <p class="food-detail">
                                <?php echo $description; ?>
                            </p>
                            <br>

                            <a href="food-detail.php?food_id=<?php echo $id; ?>" class="btn btn-primary">Order Now</a>
                        </div>
                    </div>

                    <?php
                }
            }
            else
            {
                // Food not Available
                echo "<div class='error'>Food not found.</div>";
            }
        ?>

        <div class="clearfix"></div>
    </div>
</section>
<?php include('partials-front/footer.php'); ?>
