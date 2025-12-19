<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1 class="text-center">Manage Menu Items</h1>
        <br /><br />

        <div class="text-right">
            <a href="add-food.php" class="btn-primary" style="padding: 10px; background-color: #2ecc71; color: white; text-decoration: none; border-radius: 5px;">+ Add Food</a>
        </div>
        <br /><br />

        <table class="table-full" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid #f1f2f6; text-align: left;">
                    <th style="padding: 10px;">No</th>
                    <th style="padding: 10px;">Name</th>
                    <th style="padding: 10px;">Price</th>
                    <th style="padding: 10px;">Image</th>
                    <th style="padding: 10px;">Available?</th>
                    <th style="padding: 10px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $sql = "SELECT * FROM menu";
                    $res = mysqli_query($conn, $sql);
                    $sn = 1;

                    if(mysqli_num_rows($res) > 0) {
                        while($row = mysqli_fetch_assoc($res)) {
                            $id = $row['menu_ID'];
                            $name = $row['menu_name'];
                            $price = $row['menu_price'];
                            $image_name = $row['menu_pict'];
                            $available = $row['menu_availability'];
                            ?>
                            <tr style="border-bottom: 1px solid #f1f2f6;">
                                <td style="padding: 10px;"><?php echo $sn++; ?>. </td>
                                <td style="padding: 10px;"><?php echo $name; ?></td>
                                <td style="padding: 10px;">RM <?php echo $price; ?></td>
                                <td style="padding: 10px;">
                                    <?php if($image_name != "") { ?>
                                        <img src="<?php echo SITEURL; ?>images/food/<?php echo $image_name; ?>" width="80px" style="border-radius: 5px;">
                                    <?php } else { echo "No Image"; } ?>
                                </td>
                                <td style="padding: 10px;"><?php echo ($available == 1) ? "Yes" : "No"; ?></td>
                                <td style="padding: 10px;">
                                    <a href="update-food.php?id=<?php echo $id; ?>" class="btn-secondary" style="background-color: #3498db; padding: 5px 10px; border-radius: 3px; text-decoration: none; color: white;">Update</a>
                                    <a href="delete-food.php?id=<?php echo $id; ?>" class="btn-danger" style="background-color: #e74c3c; padding: 5px 10px; border-radius: 3px; text-decoration: none; color: white;">Delete</a>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='6' class='error'>No Food Added.</td></tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('partials/footer.php'); ?>