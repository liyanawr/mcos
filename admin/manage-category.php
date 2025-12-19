<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1 class="text-center">Manage Categories</h1>
        <br /><br />

        <div class="text-right">
            <a href="add-category.php" class="btn-primary" style="padding: 10px; background-color: #2ecc71; color: white; text-decoration: none; border-radius: 5px;">+ Add Category</a>
        </div>
        <br /><br />

        <table class="table-full" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid #f1f2f6; text-align: left;">
                    <th style="padding: 10px;">No</th>
                    <th style="padding: 10px;">Details (Title)</th>
                    <th style="padding: 10px;">Image</th>
                    <th style="padding: 10px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $sql = "SELECT * FROM category";
                    $res = mysqli_query($conn, $sql);
                    $sn = 1;

                    if(mysqli_num_rows($res) > 0) {
                        while($row = mysqli_fetch_assoc($res)) {
                            $id = $row['category_ID'];
                            $details = $row['category_details'];
                            $image_name = $row['category_pict'];
                            ?>
                            <tr style="border-bottom: 1px solid #f1f2f6;">
                                <td style="padding: 10px;"><?php echo $sn++; ?>. </td>
                                <td style="padding: 10px;"><?php echo $details; ?></td>
                                <td style="padding: 10px;">
                                    <?php 
                                        if($image_name != "") {
                                            ?>
                                            <img src="<?php echo SITEURL; ?>images/category/<?php echo $image_name; ?>" width="80px" style="border-radius: 5px;">
                                            <?php
                                        } else {
                                            echo "<div class='error'>No Image</div>";
                                        }
                                    ?>
                                </td>
                                <td style="padding: 10px;">
                                    <a href="update-category.php?id=<?php echo $id; ?>" class="btn-secondary" style="background-color: #3498db; padding: 5px 10px; border-radius: 3px; text-decoration: none; color: white;">Update</a>
                                    <a href="delete-category.php?id=<?php echo $id; ?>" class="btn-danger" style="background-color: #e74c3c; padding: 5px 10px; border-radius: 3px; text-decoration: none; color: white;">Delete</a>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='4' class='error'>No Categories Added.</td></tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('partials/footer.php'); ?>