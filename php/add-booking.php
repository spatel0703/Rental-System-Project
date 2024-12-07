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
    if(isset($_POST['prop_ID']) && isset($_POST['renter_ID'])
            && isset($_POST['start_date']) && isset($_POST['end_date']) && isset($_POST['total_price'])
            && isset($_POST['prop_status'])) {
        /**
         * Get data from POST request and store it in var
         */
        $propid = $_POST['prop_ID'];
        $renterid = $_POST['renter_ID'];
        $psdate = $_POST['start_date'];
        $pedate = $_POST['end_date'];
        $proptprice = $_POST['total_price'];
        $propstatus = $_POST['prop_status'];

        # making URL data format
        $user_input = 'propid='.$propid.'&renterid='.$renterid.'&psdate='.$psdate.'&pedate='.$pedate.
                        '&proptprice='.$proptprice.'&propstatus='.$propstatus;

        
        # simple form validation
        $text = 'Property ID';
        $location = "../add-booking.php";
        $ms = "error";
        is_empty($propid, $text, $location, $ms, $user_input);

        $text = 'Renter ID';
        $location = "../add-booking.php";
        $ms = "error";
        is_empty($renterid, $text, $location, $ms, $user_input);

        $text = 'Property Start Date';
        $location = "../add-booking.php";
        $ms = "error";
        is_empty($psdate, $text, $location, $ms, $user_input);

        $text = 'Property End Date';
        $location = "../add-booking.php";
        $ms = "error";
        is_empty($pedate, $text, $location, $ms, $user_input);

        $text = 'Property Price';
        $location = "../add-booking.php";
        $ms = "error";
        is_empty($proptprice, $text, $location, $ms, $user_input);

        $text = 'Property Status';
        $location = "../add-booking.php";
        $ms = "error";
        is_empty($propstatus, $text, $location, $ms, $user_input);


            # insert data into database
            $sql = "INSERT INTO booking (propertyID,
                                        renterID,
                                        start,
                                        end,
                                        totalPrice,
                                        status)
                    VALUES(?,?,?,?,?,?)";

            $stmt = $conn->prepare($sql);
            $res = $stmt->execute([$propid, $renterid, $psdate, $pedate, $proptprice, 
                                    $propstatus]);

            if ($res){
                # success message
                $sm = "The booking is successfully created!";
                header("Location: ../add-booking.php?success=$sm");
                exit;
            } else{
                $em = "Unknown error occured!";
                header("Location: ../add-booking.php?error=$em");
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
