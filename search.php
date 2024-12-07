<?php
session_start();

# if search key is not set or empty
if (!isset($_GET['key']) || empty($_GET['key'])){
    header("Location: index.php");
    exit;

}
$key = $_GET['key'];

# database connection file
include "db_conn.php";

# rental helper function
include "php/func-rentals.php";
$s_rentals = search_rentals($conn, $key);

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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental System</title>

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
        </nav> <br>
        Search result for <b><?=$key?></b>

        <div class = "d-flex pt-3">
            <?php if ($s_rentals == 0) { ?>
                <div class = "alert alert-warning 
                          text center p-5 pdf-list" 
                role = "alert">
                <img src = "image/empty-search.png"
                            width = "100">
                <br>
                the key <b>"<?=$key?>"</b> didn't match to any record in the database
                </div>
            <?php }else { ?>
            <div class = "pdf-list d-flex flex-wrap">
                <?php foreach($s_rentals as $s_rental){ ?>
                <div class = "card m-1">
                    <img src="uploads/cover/<?=$s_rental['imageProp']?>"
                         class = "card-img-top">
                    <div class = "card-body">
                        <h5 class = "card-title"> <?=$s_rental['propName']?> </h5>
                        <p class = "card-text">
                            <i><b>Type of Property:
                                <?php foreach($rentals as $rental){ 
                                    if ($rental['propertyID'] == $s_rental['propertyID']) {
                                        echo $rental['proptype'];
                                        break;
                                    } 
                                    ?>
                                <?php } ?>
                            <br></b></i>
                            <?=$s_rental['proplocation']?>
                        </p>
                        <p class = "card-text">
                            <?=$s_rental['amenities']?> 
                        </p>
                    </div>
                </div>
                <?php } ?>

            </div> 
            <?php } ?>
        </div>
    </div>
</body>
</html>