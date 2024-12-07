<?php
session_start();

# if the admin is logged in 
if(isset($_SESSION['user_id']) && isset($_SESSION['user_email'])){

    # if booking id doesn't exist
    if (!isset($_GET['id'])) {
        # redirects to admin.php page
        header("Location: admin.php");
        exit;
    }

    $id = $_GET['id'];
    
    # database connection file
    include "db_conn.php";

     # rental ID helper function
     include "php/func-booking.php";
     $booking = get_all_bookings_ids($conn, $id);

     if ($booking == 0) {
        header("Location: admin.php");
        exit;
     }

     # booking helper function
     $bookings = get_all_booking($conn);


     # gets property ids, property ID function
     include "php/func-booking-foreign.php";
     $propIDS = get_property_ids($conn);

     $renterIDS = get_renter_ids($conn, $_SESSION['user_id']);

     include "php/func-status.php";
     $propstatuses = get_all_statuses($conn);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Rental</title>

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

    <form action="php/add-booking.php"
          method = "post"
          enctype = "multipart/form-data"
          class = "shadow p-4 rounded mt-5"
          style = "width: 90%; max-width: 50rem;">

        <h1 class = "text-center pb-5 display-4 fs-3">
            Edit Booking
        </h1>
        <?php if (isset($_GET['error'])) { ?>
            <div class="alert alert-danger" role="alert">
                <?=htmlspecialchars($_GET['error']);?>
            </div>
        <?php } ?>
        <?php if (isset($_GET['success'])) { ?>
            <div class="alert alert-success" role="alert">
                <?=htmlspecialchars($_GET['success']);?>
            </div>
        <?php } ?>

        <div class="mb-3">
            <label class="form-label">
                Property ID
            </label>
            <input type="text" 
                hidden
                value = "<?=$booking['bookingId']?>"
                name = "book_ID">

            <input type="text" 
                class="form-control" 
                value = "<?=$booking['propertyID']?>"
                name = "prop_ID">
        </div>


        <div class="mb-3">
            <label class="form-label">
                Renter ID
            </label>
         <select name="renter_ID" class="form-control">
            <option value="<?= htmlspecialchars($booking['renterID']); ?>">
                <?= htmlspecialchars($booking['renterID']); ?>
            </option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">
                Start Date
            </label>
            <input type="date" 
                class="form-control" 
                value = "<?=$booking['start']?>"
                name = "start_date">
        </div>

        <div class="mb-3">
            <label class="form-label">
                End Date
            </label>
            <input type="date" 
                class="form-control" 
                value = "<?=$booking['end']?>"
                name = "end_date">
        </div>

        <div class="mb-3">
            <label class="form-label">
                Price of Rental
            </label>
            <input type="text" 
                class="form-control" 
                value = "<?=$booking['totalPrice']?>"
                name = "total_price">
        </div>

        <div class="mb-3">
            <label class="form-label">Status of Rental</label>
            <select name="prop_status" class="form-control">
                <option value="0">Select availability</option>
                <?php foreach ($propstatuses as $propstatus) { ?>
                    <option value="<?= htmlspecialchars($propstatus); ?>">
                        <?= htmlspecialchars($propstatus); ?>
                    </option>
                <?php } ?>
            </select>
        </div>


        <button type="submit" 
            class="btn btn-primary">
            Update Booking</button>
    </form>
    </div>
</body>
</html>


<?php }else{
    header("Location: login.php");
    exit;
}

?>