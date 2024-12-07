<?php
session_start();

# if the admin is logged in 
if(isset($_SESSION['user_id']) && isset($_SESSION['user_email'])){


    # database connection file
    include "db_conn.php";

    # rental helper function
    include "php/func-rentals.php";
    $rentals = get_all_rentals($conn);

    # admin owner helper function
    include "php/func-owner.php";
    $owners = get_all_owner($conn);

    # booking helper function
    include "php/func-booking.php";
    $bookings = get_all_booking($conn);

    # review helper function
    include "php/func-reviews.php";
    $reviews = get_all_review($conn);

    $rentals = array_filter($rentals, function($rental) {
        return $rental['ownerId'] == $_SESSION['user_id'];
    });

    $bookings = array_filter($bookings, function($booking) use ($rentals) {
        return in_array($booking['propertyID'], array_column($rentals, 'propertyID'));
    });

     # Filter reviews for the logged-in user's bookings
     $reviews = array_filter($reviews, function($review) use ($bookings) {
        return in_array($review['bookingId'], array_column($bookings, 'bookingId'));
    });

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN</title>

    <!-- bootstrap 5 CDN -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
`
    <!-- bootstrap 5 Js CDN -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</head>
<body>
    <div class="container">
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="admin.php">Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse"
                id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                <a class="nav-link" 
                    aria-current="page" 
                    href="index.php">Rentals</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" 
                    href="add-rental.php">Add Rental Property</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" 
                    href="add-booking.php">Add Booking</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" 
                    href="logout.php">Logout</a>
                </li>
            </ul>
            </div>
        </div>
        </nav>

        <form action="search.php"
              method = "get"
              style = "width: 100%; max-width: 30rem">

            <div class="input-group my-5">
            <input type="text" 
                  class="form-control" 
                  name = "key"
                  placeholder="Search Rental..." 
                  aria-label="Search Rental..." 
                  aria-describedby="basic-addon2">

            <button class="input-group-text
                           btn btn-primary" 
                  id="basic-addon2">
                  <img src="image/search.png"
                       width = "20">
            </button>
            </div>
        </form>

        <?php if ($rentals == 0){ ?>
            <div class = "alert alert-warning 
                          text center p-5" 
                role = "alert">
                <img src = "image/empty.png"
                            width = "100">
                <br>
                There is no rental in the database
            </div>
        <?php }else{?>

        <!-- List of all rentals -->
        <h4 class = "mt-5">All Rentals</h4>
        <table class="table table-bordered shadow">
            <thead>
                <tr>
                    <th>Property ID</th>
                    <th>Property Name</th>
                    <th>Owner ID</th>
                    <th>Property Type</th>
                    <th>Property Location</th>
                    <th>Price</th>
                    <th>Amenities</th>
                    <th>Image</th>
                    <th>Availability</th>
                    <th>Availability Option</th>
                </tr>
            </thead>
            <tbody>
               <?php
               #$i = 0; 
               foreach($rentals as $rental) {
                    #$i++
                ?>
               <tr>
                <td><?=$rental['propertyID']?></td>
                <td><?=$rental['propName']?></td>
                <td>
                    <?php if ($owners == 0) {
                        echo "Undefined";
                    }else{
                        foreach($owners as $owner) {
                            if ($owner['userID'] == $rental['ownerId']) {
                                echo $owner['username'];
                            }
                        }
                    } ?></td>
                <td><?=$rental['proptype']?></td>
                <td><?=$rental['proplocation']?></td>
                <td><?=$rental['price']?></td>
                <td><?=$rental['amenities']?></td>
                <td>
                    <img width = "100" src="uploads/Cover/<?=$rental['imageProp']?>" >
                    </td>
                <td><?=$rental['availability']?></td>
                <td>
                    <a href="edit-rental.php?id=<?=$rental['propertyID']?>" class = "btn btn-warning">Edit Rental</a>
                    <a href="php/delete-rental.php?id=<?=$rental['propertyID']?>" class = "btn btn-danger">Delete Rental</a>
                </td>
               </tr>
               <?php } ?>
            </tbody>
        </table>
        <?php } ?>


        <?php if ($bookings == 0){ ?>
            <div class = "alert alert-warning 
                          text center p-5" 
                role = "alert">
                <img src = "image/empty.png"
                            width = "100">
                <br>
                There is no booking in the database
            </div>
        <?php }else{?>
        <!-- List of all bookings -->
        <h4 class = "mt-5">All Bookings</h4>
        <table class = "table table-bordered shadow">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Property ID</th>
                    <th>Renter ID</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Action</th>

                </tr>
            </thead> 
            <tbody>
                <?php 
                #$j=0;
                foreach($bookings as $booking) {
                    #$j++;

    
                ?>
                <tr>
                    <td><?=$booking['bookingId']?></td>
                    <td>
                    <?php if ($rentals == 0) {
                        echo "Undefined";
                    }else{
                        foreach($rentals as $rental) {
                            if ($rental['propertyID'] == $booking['propertyID']) {
                                echo $rental['propertyID'];
                            }
                        }
                    } ?>
                    </td>
                    <td>
                    <?php if ($owners == 0) {
                        echo "Undefined";
                    }else{
                        foreach($owners as $owner) {
                            if ($owner['userID'] == $booking['renterID']) {
                                echo $owner['userID'];
                            }
                        }
                    } ?>
                    </td>
                    <td><?=$booking['start']?></td>
                    <td><?=$booking['end']?></td>
                    <td><?=$booking['totalPrice']?></td>
                    <td><?=$booking['status']?></td>
                    <td>
                        <a href="edit-booking.php?id=<?=$booking['bookingId']?>" class = "btn btn-warning">Edit Booking</a>
                        <a href="php/delete-booking.php?id=<?=$booking['bookingId']?>" class = "btn btn-danger">Delete Booking</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>   
        </table>
        <?php } ?>
        
        <?php if ($reviews == 0){ ?>
            <div class = "alert alert-warning 
                          text center p-5" 
                role = "alert">
                <img src = "image/empty.png"
                            width = "100">
                <br>
                There is no review in the database
            </div>
        <?php }else{?>
        <!-- List of all bookings -->
        <h4 class = "mt-5">All Reviews</h4>
        <table class = "table table-bordered shadow">
            <thead>
                <tr>
                    <th>Review ID</th>
                    <th>Booking ID</th>
                    <th>Rating</th>
                    <th>Comment</th>
                    <th>Review Date</th>

                </tr>
            </thead> 
            <tbody>
                <?php 
                #$k=0;
                foreach($reviews as $review) {
                    #$k++;

    
                ?>
                <tr>
                    <td><?=$review['reviewID']?></td>
                    <td>
                    <?php if ($bookings == 0) {
                        echo "Undefined";
                    }else{
                        foreach($bookings as $booking) {
                            if ($booking['bookingId'] == $review['bookingId']) {
                                echo $booking['bookingId'];
                            }
                        }
                    } ?>
                    </td>
                    <td><?=$review['rating']?></td>
                    <td><?=$review['comment']?></td>
                    <td><?=$review['reviewDate']?></td>
                </tr>
                <?php } ?>
            </tbody>   
        </table>
        <?php } ?>
    </div>
</body>
</html>


<?php }else{
    header("Location: login.php");
    exit;
}

?>