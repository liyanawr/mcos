<?php include('partials-front/menu.php'); ?>

<section class="food-search text-center">
    <div class="container">
        <?php $search = mysqli_real_escape_string($conn, $_POST['search']); ?>
        <h2>Categories Matching <a href="#" class="text-white">"<?php echo $search; ?>"</a></h2>
    </div>
</section>

<section class="categories">
    <div class="container">
        <?php 
            $sql = "SELECT * FROM category WHERE category_details LIKE '%$search%'";
            $res = mysqli_query($conn, $sql);
            if(mysqli_num_rows($res) > 0) {
                while($row = mysqli_fetch_assoc($res)) {
                    $id = $row['category_ID'];
                    $title = $row['category_details'];
                    $image_name = $row['category_pict'];
                    ?>
                    <a href="<?php echo SITEURL; ?>category-foods.php?category_id=<?php echo $id; ?>">
                        <div class="box-3 float-container">
                            <?php if($image_name == "") echo "Image not found"; else { ?>
                                <img src="<?php echo SITEURL; ?>images/category/<?php echo $image_name; ?>" class="img-responsive img-curve">
                            <?php } ?>
                            <h3 class="float-text text-white"><?php echo $title; ?></h3>
                        </div>
                    </a>
                    <?php
                }
            } else { echo "<div class='error'>No Categories found.</div>"; }
        ?>
        <div class="clearfix"></div>
    </div>
</section>

<?php include('partials-front/footer.php'); ?>