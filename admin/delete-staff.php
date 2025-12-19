<?php 
    include('../config/constants.php');

    if(isset($_GET['id'])) {
        $id = $_GET['id'];

        $sql = "DELETE FROM STAFF WHERE staff_ID=$id";
        $res = mysqli_query($conn, $sql);

        if($res == true) {
            $_SESSION['delete'] = "<div class='success'>Staff Deleted Successfully.</div>";
            header('location:'.SITEURL.'admin/manage-staff.php');
        } else {
            $_SESSION['delete'] = "<div class='error'>Failed to Delete Staff.</div>";
            header('location:'.SITEURL.'admin/manage-staff.php');
        }
    } else {
        header('location:'.SITEURL.'admin/manage-staff.php');
    }
?>