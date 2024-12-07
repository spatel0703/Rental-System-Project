<?php  
session_start();

# If the admin is logged in
if (isset($_SESSION['user_id']) &&
    isset($_SESSION['user_email'])) {

	# Database Connection File
	include "../db_conn.php";

    # rental helper function
    # include "php/func-rentals.php";
    # $rentals = get_all_rentals($conn);

    # validation helper function
    include "func-validation.php";

    # file upload helper function
    include "func-file-upload.php";


    /* 
	  if all input fields are filled
	*/
	if (isset($_POST['prop_id']) &&
    isset($_POST['prop_name']) && isset($_POST['own_ID'])
    && isset($_POST['proptype']) && isset($_POST['prop_loc']) && isset($_POST['prop_price'])
    && isset($_POST['prop_amen']) && isset($_FILES['prop_image']) && isset($_POST['prop_avail']) 
    && isset($_POST['current_image'])) {


		/**
         * Get data from POST request and store it in var
         */
        $propid = $_POST['prop_id'];
        $propname = $_POST['prop_name'];
        $ownID = $_POST['own_ID'];
        $ptype = $_POST['proptype'];
        $proploc = $_POST['prop_loc'];
        $propprice = $_POST['prop_price'];
        $propamen = $_POST['prop_amen'];
        $propavail = $_POST['prop_avail'];

        /**
         * get current image from POST request to store them in var
         */

        $currentimage = $_POST['current_image'];

        # simple form validation
        $text = 'Property Name';
        $location = "../edit-rental.php";
        $ms = "id=$propid&error";
        is_empty($propname, $text, $location, $ms, "");
        
        $text = 'Owner ID';
        $location = "../edit-rental.php";
        $ms = "id=$propid&error";
        is_empty($ownID, $text, $location, $ms, "");
        
        $text = 'Property Type';
        $location = "../edit-rental.php";
        $ms = "id=$propid&error";
        is_empty($ptype, $text, $location, $ms, "");
        
        $text = 'Property Location';
        $location = "../edit-rental.php";
        $ms = "id=$propid&error";
        is_empty($proploc, $text, $location, $ms, "");
        
        $text = 'Property Price';
        $location = "../edit-rental.php";
        $ms = "id=$propid&error";
        is_empty($propprice, $text, $location, $ms, "");
        
        $text = 'Property Amenities';
        $location = "../edit-rental.php";
        $ms = "id=$propid&error";
        is_empty($propamen, $text, $location, $ms, "");
        
        $text = 'Property Availability';
        $location = "../edit-rental.php";
        $ms = "id=$propid&error";
        is_empty($propavail, $text, $location, $ms, "");


        /**
         * if admin tries to update property image
         */
        if (!empty($_FILES['prop_image']['name'])){
            # property image uploading
            $allowed_image_exs = array("jpg", "jpeg", "png");
            $path = "cover";
            #$prop_image = upload_file($_FILES['prop_image'], $allowed_exs, $path);
            $prop_image = upload_file($_FILES['prop_image'], $allowed_image_exs, $path);

            
            /**
             * if error occured while updating property image
             */
            if ($prop_image['status'] == "error"){
                $em = $prop_image['data'];
    
                /**
                 * redirect to '../edit-rental.php' and passing error message & the id
                 */
    
                header("Location: ../edit-rental.php?error=$em&id=$propid");
                exit;
    
            }else{
                # current property image path
                $prop_image_current_path = "../uploads/cover/current_image";

                # delete from server
                unlink($prop_image_current_path);

                # getting new property image name
                $prop_image_URL = $prop_image['data'];

                $sql = "UPDATE rentals
                    SET propName = ?,
                        ownerId = ?,
                        proptype = ?,
                        proplocation = ?,
                        price = ?,
                        amenities = ?,
                        imageProp = ?,
                        availability = ?
                    WHERE propertyID = ?";
                $stmt = $conn->prepare($sql);
                $res = $stmt->execute([$propname, $ownID, $ptype, $proploc, $propprice, 
                                    $propamen, $prop_image_URL, $propavail, $propid]);

            /**
             * if there is no error while updating data
             */

                if ($res) {
                    # success message
                    $sm = "Successfully updated!";
                    header("Location: ../edit-rental.php?success=$sm&id=$propid");
                    exit;
                }else{
                    # Error message
                    $em = "Unknown Error Occurred!";
                    header("Location: ../edit-rental.php?error=$em&id=$propid");
                    exit;
                }

            }
        } else{
            /**
             * update just data
             */

            $sql = "UPDATE rentals
                    SET propName = ?,
                        ownerId = ?,
                        proptype = ?,
                        proplocation = ?,
                        price = ?,
                        amenities = ?,
                        availability = ?
                    WHERE propertyID = ?";
            $stmt = $conn->prepare($sql);
            $res = $stmt->execute([$propname, $ownID, $ptype, $proploc, $propprice, 
                                    $propamen, $propavail, $propid]);

            /**
             * if there is no error while updating data
             */

                if ($res) {
                    # success message
                    $sm = "Successfully updated!";
                    header("Location: ../edit-rental.php?success=$sm&id=$propid");
                    exit;
                }else{
                    # Error message
                    $em = "Unknown Error Occurred!";
                    header("Location: ../edit-rental.php?error=$em&id=$propid");
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
