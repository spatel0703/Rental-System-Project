<?php  
session_start();

# If the admin is logged in
if (isset($_SESSION['user_id']) && isset($_SESSION['user_email'])) {

    # Database Connection File
    include "../db_conn.php";

    /*
      Check if the booking ID is set
    */
    if (isset($_GET['id'])) {
        /* 
        Get data from GET request 
        and store it in variable
        */
        $id = $_GET['id'];

        # Simple form Validation
        if (empty($id)) {
            $em = "Error Occurred!";
            header("Location: ../renterAdmin.php?error=$em");
            exit;
        } else {
            # Get associated review(s) and delete them first
            $sql_review = "DELETE FROM review WHERE bookingId=?";
            $stmt_review = $conn->prepare($sql_review);
            $stmt_review->execute([$id]);

            # Now delete the booking record
            $sql2 = "SELECT * FROM booking WHERE bookingId=?";
            $stmt2 = $conn->prepare($sql2);
            $stmt2->execute([$id]);
            $the_booking = $stmt2->fetch();

            if ($stmt2->rowCount() > 0) {
                # DELETE the booking from Database booking
                $sql = "DELETE FROM booking WHERE bookingId=?";
                $stmt = $conn->prepare($sql);
                $res = $stmt->execute([$id]);

                /*
                    If there is no error while 
                    Deleting the booking data
                */
                if ($res) {
                    # Success message
                    $sm = "Successfully removed!";
                    header("Location: ../renterAdmin.php?success=$sm");
                    exit;
                } else {
                    $em = "Error Occurred!";
                    header("Location: ../renterAdmin.php?error=$em");
                    exit;
                }
            } else {
                $em = "Error Occurred!";
                header("Location: ../renterAdmin.php?error=$em");
                exit;
            }
        }
    } else {
        header("Location: ../renterAdmin.php");
        exit;
    }

} else {
    header("Location: ../login.php");
    exit;
}
