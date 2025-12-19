<?php 
    // 1. Process database logic BEFORE any HTML is sent to avoid header warnings
    ob_start(); 
    include('partials/menu.php'); // menu.php includes constants.php which starts the session

    // 2. Initial Data Fetching
    if(isset($_GET['id'])) {
        $id = mysqli_real_escape_string($conn, $_GET['id']);
        $sql2 = "SELECT * FROM menu WHERE menu_ID=$id";
        $res2 = mysqli_query($conn, $sql2);
        
        if(mysqli_num_rows($res2) == 1) {
            $row2 = mysqli_fetch_assoc($res2);
            $name = $row2['menu_name'];
            $description = $row2['menu_details'];
            $price = $row2['menu_price'];
            $current_image = $row2['menu_pict'];
            $current_availability = $row2['menu_availability'];
        } else {
            header('location:'.SITEURL.'admin/manage-food.php');
            exit();
        }
    } else {
        header('location:'.SITEURL.'admin/manage-food.php');
        exit();
    }

    // 3. Process Form Submission
    if(isset($_POST['submit'])) {
        $id = $_POST['id'];
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $price = $_POST['price'];
        $available = $_POST['available'];
        $current_image = $_POST['current_image'];

        // Handle Image Upload
        if(isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
            $tmp = explode('.', $_FILES['image']['name']);
            $ext = end($tmp);
            $image_name = "Food-Name-".rand(0000, 9999).'.'.$ext;
            
            $source_path = $_FILES['image']['tmp_name'];
            $destination_path = "../images/food/".$image_name;
            
            $upload = move_uploaded_file($source_path, $destination_path);
            
            if($upload == true) {
                if($current_image != "") {
                    $remove_path = "../images/food/".$current_image;
                    if(file_exists($remove_path)) {
                        unlink($remove_path);
                    }
                }
            }
        } else {
            $image_name = $current_image;
        }

        // Update Database
        $sql3 = "UPDATE menu SET 
            menu_name = '$name', 
            menu_details = '$description', 
            menu_price = $price, 
            menu_pict = '$image_name', 
            menu_availability = $available 
            WHERE menu_ID=$id";
        
        if(mysqli_query($conn, $sql3)) {
            $_SESSION['update'] = "<div class='success'>Menu Updated Successfully.</div>";
            header('location:'.SITEURL.'admin/manage-food.php'); // Redirect works here
            exit();
        }
    }
?>

<div class="main-content" style="background-color: #f1f2f6; padding: 3% 0;">
    <div class="wrapper" style="max-width: 800px; margin: 0 auto; background: white; padding: 40px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        
        <h1 class="text-center" style="color: #2f3542; margin-bottom: 30px;">Update Menu Item</h1>

        <form action="" method="POST" enctype="multipart/form-data">
            <table class="table-no-border" style="width: 100%;">
                <tr style="height: 60px;">
                    <td style="width: 30%;"><strong>Name:</strong></td>
                    <td><input type="text" name="name" class="form-control" value="<?php echo $name; ?>" style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 5px;"></td>
                </tr>
                <tr>
                    <td style="padding-top: 15px; vertical-align: top;"><strong>Description:</strong></td>
                    <td style="padding-top: 15px;">
                        <textarea name="description" class="form-control" cols="30" rows="5" style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 5px;"><?php echo $description; ?></textarea>
                    </td>
                </tr>
                <tr style="height: 60px;">
                    <td><strong>Price (RM):</strong></td>
                    <td><input type="number" step="0.01" name="price" class="form-control" value="<?php echo $price; ?>" style="width: 100%; padding: 8px; border: 1px solid #ced4da; border-radius: 5px;"></td>
                </tr>
                <tr>
                    <td style="padding-top: 20px; vertical-align: top;"><strong>Current Image:</strong></td>
                    <td style="padding-top: 20px;">
                        <?php if($current_image != "") { ?>
                            <img src="<?php echo SITEURL; ?>images/food/<?php echo $current_image; ?>" width="150px" style="border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                        <?php } else { echo "<span class='error'>No Image Available</span>"; } ?>
                    </td>
                </tr>
                <tr style="height: 60px;">
                    <td><strong>New Image:</strong></td>
                    <td><input type="file" name="image"></td>
                </tr>
                <tr style="height: 60px;">
                    <td><strong>Available:</strong></td>
                    <td>
                        <input <?php if($current_availability==1) echo "checked"; ?> type="radio" name="available" value="1"> Yes 
                        <input <?php if($current_availability==0) echo "checked"; ?> type="radio" name="available" value="0" style="margin-left: 15px;"> No
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="padding-top: 30px; text-align: center;">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="hidden" name="current_image" value="<?php echo $current_image; ?>">
                        <input type="submit" name="submit" value="Update Menu" class="btn-primary" style="padding: 12px 30px; border: none; background-color: #ff4757; color: white; border-radius: 5px; cursor: pointer; font-weight: bold;">
                        <a href="manage-food.php" style="margin-left: 15px; text-decoration: none; color: #747d8c;">Cancel</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<?php include('partials/footer.php'); ?>