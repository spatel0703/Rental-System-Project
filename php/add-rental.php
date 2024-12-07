<?php
session_start();

# if the admin is logged in 
if(isset($_SESSION['user_id']) && isset($_SESSION['user_email'])){

    # database connection file
    include "../db_conn.php";

    # validation helper function
    include "func-validation.php";

    # file upload helper function
    include "func-file-upload.php";

    /**
     * Check if booking id is submitted
     */
    if(isset($_POST['prop_name']) && isset($_POST['own_ID'])
            && isset($_POST['proptype']) && isset($_POST['prop_loc']) && isset($_POST['prop_price'])
            && isset($_POST['prop_amen']) && isset($_FILES['prop_image']) && isset($_POST['prop_avail']) ) {
        /**
         * Get data from POST request and store it in var
         */
        $propname = $_POST['prop_name'];
        $ownID = $_POST['own_ID'];
        $ptype = $_POST['proptype'];
        $proploc = $_POST['prop_loc'];
        $propprice = $_POST['prop_price'];
        $propamen = $_POST['prop_amen'];
        $propavail = $_POST['prop_avail'];

        # making URL data format
        $user_input = 'propname='.$propname.'&ownID='.$ownID.'&ptype='.$ptype.'&proploc='.$proploc.
                        '&proprice='.$propprice.'&propamen='.$propamen.'&propavail='.$propavail;

        
        # simple form validation
        $text = 'Property Name';
        $location = "../add-rental.php";
        $ms = "error";
        is_empty($propname, $text, $location, $ms, $user_input);

        $text = 'Owner ID';
        $location = "../add-rental.php";
        $ms = "error";
        is_empty($ownID, $text, $location, $ms, $user_input);

        $text = 'Property Type';
        $location = "../add-rental.php";
        $ms = "error";
        is_empty($ptype, $text, $location, $ms, $user_input);

        $text = 'Property Location';
        $location = "../add-rental.php";
        $ms = "error";
        is_empty($proploc, $text, $location, $ms, $user_input);

        $text = 'Property Price';
        $location = "../add-rental.php";
        $ms = "error";
        is_empty($propprice, $text, $location, $ms, $user_input);

        $text = 'Property Amenities';
        $location = "../add-rental.php";
        $ms = "error";
        is_empty($propamen, $text, $location, $ms, $user_input);

        $text = 'Property Availability';
        $location = "../add-rental.php";
        $ms = "error";
        is_empty($propavail, $text, $location, $ms, $user_input);

        # property image uploading
        $allowed_image_exs = array("jpg", "jpeg", "png");
        $path = "cover";
        #$prop_image = upload_file($_FILES['prop_image'], $allowed_exs, $path);
        $prop_image = upload_file($_FILES['prop_image'], $allowed_image_exs, $path);

        /**
         * if error occured while uploading the property image
         */

        if ($prop_image['status'] == "error"){
            $em = $prop_image['data'];

            /**
             * redirect to '../add-rental.php' and passing error message & user_input
             */

            header("Location: ../add-rental.php?error=$em&$user_input");
            exit;

        } else{
            /**
             * get new image property name
             */

            $prop_image_URL = $prop_image['data'];

            echo $prop_image_URL;

            # insert data into database
            $sql = "INSERT INTO rentals (propName,
                                        ownerId,
                                        proptype,
                                        proplocation,
                                        price,
                                        amenities,
                                        imageProp,
                                        availability)
                    VALUES(?,?,?,?,?,?,?,?)";

            $stmt = $conn->prepare($sql);
            $res = $stmt->execute([$propname, $ownID, $ptype, $proploc, $propprice, 
                                    $propamen, $prop_image_URL, $propavail]);

            if ($res){
                # success message
                $sm = "The rental is successfully created!";
                header("Location: ../add-rental.php?success=$sm");
                exit;
            } else{
                $em = "Unknown error occured!";
                header("Location: ../add-rental.php?error=$em");
                exit;
            }
        }


    }else {
        header("Location: ../admin.php");
        exit;
    }



}else{
    header("Location: ../login.php");
    exit;
}
