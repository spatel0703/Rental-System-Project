<?php
session_start();


# If proptype is not set
if (!isset($_GET['proptype'])) {
    header("Location: index.php");
    exit;
}


# Get proptype from GET request and sanitize it
$proptype = htmlspecialchars($_GET['proptype'], ENT_QUOTES, 'UTF-8');

# database connection file
include "db_conn.php";

# rental helper function
include "php/func-rentals.php";
$rentals = get_all_rentals($conn);

$rentals_bytype = get_rentals_bytype($conn, $proptype);

$rental_type = get_rentals_type($conn, $proptype);

# admin owner helper function
include "php/func-owner.php";
$owners = get_all_owner($conn);

# booking helper function
include "php/func-booking.php";
$bookings = get_all_booking($conn);

# review helper function
include "php/func-reviews.php";
$reviews = get_all_review($conn);

include "php/func-proptype.php";
$proptypes = get_all_types($conn);



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=htmlspecialchars($proptype)?></title>

    <!-- bootstrap 5 CDN -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
`
    <!-- bootstrap 5 Js CDN -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <link rel = "stylesheet" href = "css/style.css">

</head>
<body>
    <div class="container">
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Rental System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse"
                id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                <a class="nav-link active" 
                    aria-current="page" 
                    href="index.php">Open Rentals</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" 
                    href="#">Contact</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" 
                    href="#">About</a>
                </li>
                <li class="nav-item">
                <?php 
                if (isset($_SESSION['user_id'])) {

                ?>
                    <a class="nav-link" 
                    href="admin.php">Admin</a>
                <?php } else { ?>
                <a class="nav-link" 
                    href="login.php">Login</a>
                <?php }?>

                </li>
            </ul>
            </div>
        </div>
        </nav>

        <h1 class = "display-4 p-3 fs-3"> 
            <a href="index.php"
                class = "nd">
                <img src="image/back-arrow.PNG" 
                     width="35">
            </a>
           <?=htmlspecialchars($proptype)?>
        </h1>

        <div class = "d-flex pt-3">
            <?php if ($rentals == 0) { ?>

                <div class = "alert alert-warning
                              text-center p-5"
                        role = "alert">

                <img src="image/empty.png" width = "100">
                <br>
                There is no rental in the database

                </div>

            <?php } else{ ?>


        </div>

        
        <div class = "pdf-list d-flex flex-wrap">
            <?php foreach($rental_type as $rental){ ?>
            <div class = "card m-1">
                <img src="uploads/cover/<?=$rental['imageProp']?>"
                    class = "card-img-top">
                <div class = "card-body">
                    <h5 class = "card-title"> <?=$rental['propName']?> </h5>
                    <p class = "card-text">
                        <i><b>Type of Property:</b></i>
                        <?=$rental['proptype']?>
                        <br><b>Location:</b> <?=$rental['proplocation']?>
                    </p>
                    <p class = "card-text">
                        <?=$rental['amenities']?> 
                    </p>
                </div>
            </div>
            <?php } ?>
        </div>
            <?php } ?>

            <div class = "PropertyType">
                <!-- List of Property Types -->
                <div class = "list-group">
                    <?php if ($proptypes == 0){
                        # do nothing
                    } else { ?>
                    <a href="#"
                        class = "list-group-item list-group-item-action active"
                        >Property Type</a>
                        <?php foreach ($proptypes as $proptype) { ?>


                        <a href="proptype.php?id=<?=htmlspecialchars($proptype)?>"
                            class = "list-group-item list-group-item-action"><?=htmlspecialchars($proptype)?></a>
                        <?php } } ?>
                </div>
            
            </div>
        </div>
    </div>
</body>
</html>