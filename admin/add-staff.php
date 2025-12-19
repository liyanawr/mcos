<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Add Staff</h1>
        <br><br>

        <form action="" method="POST">
            <table class="tbl-30">
                <tr>
                    <td>First Name: </td>
                    <td><input type="text" name="first_name" placeholder="First Name" required></td>
                </tr>
                <tr>
                    <td>Last Name: </td>
                    <td><input type="text" name="last_name" placeholder="Last Name" required></td>
                </tr>
                <tr>
                    <td>Contact No: </td>
                    <td><input type="text" name="contact" placeholder="Contact Number" required></td>
                </tr>
                <tr>
                    <td>Username: </td>
                    <td><input type="text" name="username" placeholder="Username" required></td>
                </tr>
                <tr>
                    <td>Password: </td>
                    <td><input type="password" name="password" placeholder="Password" required></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Add Staff" class="btn-secondary">
                    </td>
                </tr>
            </table>
        </form>

        <?php 
            if(isset($_POST['submit'])) {
                $first = $_POST['first_name'];
                $last = $_POST['last_name'];
                $contact = $_POST['contact'];
                $username = $_POST['username'];
                $password = $_POST['password'];

                $sql = "INSERT INTO STAFF (staff_first_name, staff_last_name, staff_contact_no, staff_username, staff_password) 
                        VALUES ('$first', '$last', '$contact', '$username', '$password')";
                
                if(mysqli_query($conn, $sql)) {
                    header('location:'.SITEURL.'admin/manage-staff.php');
                }
            }
        ?>
    </div>
</div>

<?php include('partials/footer.php'); ?>