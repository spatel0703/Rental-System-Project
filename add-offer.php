<?php
session_start();

# if the admin is logged in 
if(isset($_SESSION['user_id']) && isset($_SESSION['user_email'])){

    # database connection file
    include "db_conn.php";

     # booking helper function
     include "php/func-booking.php";
     $bookings = get_all_booking($conn);




     # gets property ids, property ID function
     include "php/func-booking-foreign.php";
     $propIDS = get_property_ids($conn);

     $renterIDS = get_renter_ids($conn, $_SESSION['user_id']);

     include "php/func-status.php";
     $propstatuses = get_all_statuses($conn);

     if (isset($_GET['prop_ID'])) {
        $prop_ID = $_GET['prop_ID'];

     }else $prop_ID = '';

     if (isset($_GET['renter_ID'])) {
        $renter_ID = $_GET['renter_ID'];

     }else $renter_ID = '';

     if (isset($_GET['start_date'])) {
        $start_date = $_GET['start_date'];

     }else $start_date = '';

     if (isset($_GET['end_date'])) {
        $end_date = $_GET['end_date'];

     }else $end_date = '';

     if (isset($_GET['total_price'])) {
        $total_price = $_GET['total_price'];

     }else $total_price = '';

     if (isset($_GET['prop_status'])) {
        $prop_status = $_GET['prop_status'];

     }else $prop_status = '';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Offer</title>

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
            <a class="navbar-brand" href="renterAdmin.php">Admin</a>
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
                <a class="nav-link active" 
                    href="add-offer.php">Make an Offer</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" 
                    href="logout.php">Logout</a>
                </li>
            </ul>
            </div>
        </div>
        </nav>

    <form action="php/add-offer.php"
          method = "post"
          enctype = "multipart/form-data"
          class = "shadow p-4 rounded mt-5"
          style = "width: 90%; max-width: 50rem;">

        <h1 class = "text-center pb-5 display-4 fs-3">
            Make an Offer
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
            <label class="form-label">Property ID</label>
            <select name="prop_ID" class="form-control">
                <?php foreach ($propIDS as $propertyID) : ?>
                    <option value="<?= htmlspecialchars($propertyID['propertyID']); ?>">
                        <?= htmlspecialchars($propertyID['propertyID']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">
                Renter ID
            </label>
         <select name="renter_ID" class="form-control">
            <option value="<?= htmlspecialchars($_SESSION['user_id']); ?>">
                <?= htmlspecialchars($_SESSION['user_id']); ?>
            </option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">
                Start Date
            </label>
            <input type="date" 
                class="form-control" 
                value = "<?=$start_date?>"
                name = "start_date">
        </div>

        <div class="mb-3">
            <label class="form-label">
                End Date
            </label>
            <input type="date" 
                class="form-control" 
                value = "<?=$end_date?>"
                name = "end_date">
        </div>

        <div class="mb-3">
            <label class="form-label">
                Price of Rental
            </label>
            <input type="text" 
                class="form-control" 
                value = "<?=$total_price?>"
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
            Add Offer</button>
    </form>
    </div>
</body>
</html>


<?php }else{
    header("Location: login.php");
    exit;
}

?>