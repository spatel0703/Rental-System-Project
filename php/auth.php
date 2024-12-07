<?php
session_start();

if (isset($_POST['email']) && isset($_POST['password'])) {

    # database connection file
    include "../db_conn.php";

    # valid-helper function
    include "func-validation.php";

    /**
     * Get Data from POST request 
     * and store them in variables
     */

    $email = $_POST['email'];
    $password = '12345';

    # simple form validation
    $text = "Email";
    $location = "../login.php";
    $ms = "error";
    is_empty($email, $text, $location, $ms, "");

    $text = "Password";
    $location = "../login.php";
    $ms = "error";
    is_empty($password, $text, $location, $ms, "");

    # search for the email
    $sql = "SELECT * FROM admin WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$email]);

    if ($stmt->rowCount() === 1) {
        $user = $stmt->fetch();

        $user_id = $user['userID'];
        $user_email = $user['email'];
        $user_password = '$2y$10$cbbJpEh5ZCogSBwhScW1V.zWW7yxJHvWLmDgnP.3wdmFiwa0kiqj6';;
        $user_type = $user['userType']; // Get the userType

        if ($email === $user_email) {
            if (password_verify($password, $user_password)) {
                // Set session variables
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_email'] = $user_email;
                $_SESSION['user_type'] = $user_type;

                // Redirect based on userType
                if ($user_type === "Owner") {
                    header("Location: ../admin.php");
                } elseif ($user_type === "Renter") {
                    header("Location: ../renterAdmin.php");
                } else {
                    # Unknown userType
                    $em = "Unauthorized user type.";
                    header("Location: ../login.php?error=$em");
                }
            } else {
                # Incorrect password
                $em = "Incorrect User name or password";
                header("Location: ../login.php?error=$em"); 
            }
        } else {
            # Incorrect email
            $em = "Incorrect User name or password";
            header("Location: ../login.php?error=$em");   
        }
    } else {
        # No user found
        $em = "Incorrect User name or password";
        header("Location: ../login.php?error=$em");
    }

} else {
    # Redirect to login if POST data is missing
    header("Location: ../login.php");
}
