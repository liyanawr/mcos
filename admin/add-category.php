<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Add Category</h1>
        <br><br>

        <form action="" method="POST" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Category Title: </td>
                    <td><input type="text" name="details" placeholder="Category Title" required></td>
                </tr>
                <tr>
                    <td>Select Image: </td>
                    <td><input type="file" name="image"></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Add Category" class="btn-secondary">
                    </td>
                </tr>
            </table>
        </form>

        <?php 
            if(isset($_POST['submit'])) {
                $details = $_POST['details'];

                if(isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
                    $temp = explode('.', $_FILES['image']['name']);
                    $ext = end($temp);
                    $image_name = "Category_".rand(000, 999).'.'.$ext;
                    $source_path = $_FILES['image']['tmp_name'];
                    $destination_path = "../images/category/".$image_name;

                    if(!is_dir('../images/category/')) mkdir('../images/category/', 0777, true);
                    move_uploaded_file($source_path, $destination_path);
                } else {
                    $image_name = "";
                }

                $sql = "INSERT INTO category (category_details, category_pict) VALUES ('$details', '$image_name')";
                if(mysqli_query($conn, $sql)) {
                    header('location:'.SITEURL.'admin/manage-category.php');
                }
            }
        ?>
    </div>
</div>

<?php include('partials/footer.php'); ?>