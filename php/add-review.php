<?php
session_start();

# if the admin is logged in 
if(isset($_SESSION['user_id']) && isset($_SESSION['user_email'])){

    # database connection file
    include "../db_conn.php";

    # validation helper function
    include "func-validation.php";

    /**
     * Check if review data is submitted
     */
    if(isset($_POST['booking_id']) && isset($_POST['rating'])
            && isset($_POST['comment']) && isset($_POST['review_date'])) {
        /**
         * Get data from POST request and store it in var
         */
        $bookingid = $_POST['booking_id'];
        $rating = $_POST['rating'];
        $comment = $_POST['comment'];
        $reviewdate = $_POST['review_date'];

        # making URL data format
        $user_input = 'bookingid='.$bookingid.'&rating='.$rating.'&comment='.$comment.'&reviewdate='.$reviewdate;

        # simple form validation for all fields
        $text = 'Booking ID';
        $location = "../add-review.php";
        $ms = "error";
        is_empty($bookingid, $text, $location, $ms, $user_input);

        $text = 'Rating';
        $location = "../add-review.php";
        $ms = "error";
        is_empty($rating, $text, $location, $ms, $user_input);

        $text = 'Comment';
        $location = "../add-review.php";
        $ms = "error";
        is_empty($comment, $text, $location, $ms, $user_input);

        $text = 'Review Date';
        $location = "../add-review.php";
        $ms = "error";
        is_empty($reviewdate, $text, $location, $ms, $user_input);

        # insert data into review table
        $sql = "INSERT INTO review (bookingId, rating, comment, reviewDate)
                VALUES(?,?,?,?)";

        $stmt = $conn->prepare($sql);
        $res = $stmt->execute([$bookingid, $rating, $comment, $reviewdate]);

        if ($res){
            # success message
            $sm = "The review is successfully added!";
            header("Location: ../add-review.php?success=$sm");
            exit;
        } else{
            $em = "Unknown error occurred!";
            header("Location: ../add-review.php?error=$em");
            exit;
        }

    } else {
        header("Location: ../renterAdmin.php");
        exit;
    }

}else{
    header("Location: ../login.php");
    exit;
}
?>
