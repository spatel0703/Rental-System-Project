<?php
session_start();

# if the admin is logged in 
if(isset($_SESSION['user_id']) && isset($_SESSION['user_email'])){

    # database connection file
    include "../db_conn.php";

    # validation helper function
    include "func-validation.php";

    /**
     * Check if booking id is submitted
     */
    if(isset($_POST['book_ID']) && isset($_POST['prop_ID']) && isset($_POST['renter_ID'])
            && isset($_POST['start_date']) && isset($_POST['end_date']) && isset($_POST['total_price'])
            && isset($_POST['prop_status'])) {
        /**
         * Get data from POST request and store it in var
         */

        $bookid = $_POST['book_ID'];
        $propid = $_POST['prop_ID'];
        $renterid = $_POST['renter_ID'];
        $psdate = $_POST['start_date'];
        $pedate = $_POST['end_date'];
        $proptprice = $_POST['total_price'];
        $propstatus = $_POST['prop_status'];

        # making URL data format
        # $user_input = 'propid='.$propid.'&renterid='.$renterid.'&psdate='.$psdate.'&pedate='.$pedate.
        #                  '&proptprice='.$proptprice.'&propstatus='.$propstatus;

        
        # simple form validation
        $text = 'Property ID';
        $location = "../edit-booking.php";
        $ms = "id=$bookid&error";
        is_empty($propid, $text, $location, $ms, "");

        $text = 'Renter ID';
        $location = "../edit-booking.php";
        $ms = "id=$bookid&error";
        is_empty($renterid, $text, $location, $ms, "");

        $text = 'Property Start Date';
        $location = "../edit-booking.php";
        $ms = "id=$bookid&error";
        is_empty($psdate, $text, $location, $ms, "");

        $text = 'Property End Date';
        $location = "../edit-booking.php";
        $ms = "id=$bookid&error";
        is_empty($pedate, $text, $location, $ms, "");

        $text = 'Property Price';
        $location = "../edit-booking.php";
        $ms = "id=$bookid&error";
        is_empty($proptprice, $text, $location, $ms, "");

        $text = 'Property Status';
        $location = "../edit-booking.php";
        $ms = "id=$bookid&error";
        is_empty($propstatus, $text, $location, $ms, "");


            # insert data into database
            $sql = "UPDATE booking
                    SET propertyID = ?,
                    renterID = ?,
                    start = ?,
                    end  = ?,
                    totalPrice = ?,
                    status = ?
                WHERE bookingId = ?";

            $stmt = $conn->prepare($sql);
            $res = $stmt->execute([$propid, $renterid, $psdate, $pedate, $proptprice, 
                                    $propstatus, $bookid]);

            if ($res){
                # success message
                $sm = "The rental is successfully created!";
                header("Location: ../edit-booking.php?success=$sm&id=$bookid");
                exit;
            } else{
                $em = "Unknown error occured!";
                header("Location: ../edit-booking.php?error=$em&id=$bookid");
                exit;
            }
        }


    else {
        header("Location: ../admin.php");
        exit;
    }



}else{
    header("Location: ../login.php");
    exit;
}
