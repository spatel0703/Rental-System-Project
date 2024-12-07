<?php
session_start();

# if the admin is logged in 
if(isset($_SESSION['user_id']) && isset($_SESSION['user_email'])){

    # database connection file
    include "db_conn.php";

     # rental helper function
     include "php/func-rentals.php";
     $rentals = get_all_rentals($conn);

     include "php/func-proptype.php";
     $proptypes = get_all_types($conn);

     include "php/func-availability.php";
     $pravails = get_all_avails($conn);

     if (isset($_GET['prop_name'])) {
        $prop_name = $_GET['prop_name'];

     }else $prop_name = '';

     if (isset($_GET['own_ID'])) {
        $own_ID = $_GET['own_ID'];

     }else $own_ID = '';

     if (isset($_GET['prop_loc'])) {
        $prop_loc = $_GET['prop_loc'];

     }else $prop_loc = '';

     if (isset($_GET['proptype'])) {
        $proptype = $_GET['proptype'];

     }else $proptype = '';

     if (isset($_GET['prop_loc'])) {
        $prop_loc = $_GET['prop_loc'];

     }else $prop_loc = '';

     if (isset($_GET['prop_price'])) {
        $prop_price = $_GET['prop_price'];

     }else $prop_price = '';

     if (isset($_GET['prop_amen'])) {
        $prop_amen = $_GET['prop_amen'];

     }else $prop_amen = '';

     if (isset($_GET['prop_image'])) {
        $prop_image = $_GET['prop_image'];

     }else $prop_image = '';

     if (isset($_GET['prop_avail'])) {
        $prop_avail = $_GET['prop_avail'];

     }else $prop_avail = '';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Rental</title>

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
                <a class="nav-link active" 
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

    <form action="php/add-rental.php"
          method = "post"
          enctype = "multipart/form-data"
          class = "shadow p-4 rounded mt-5"
          style = "width: 90%; max-width: 50rem;">

        <h1 class = "text-center pb-5 display-4 fs-3">
            Add New Rental Property
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
                Property Name
            </label>
            <input type="text" 
                class="form-control" 
                value = "<?=$prop_name?>"
                name = "prop_name">
        </div>

        
        <div class="mb-3">
            <label class="form-label">
                Owner ID
            </label>
         <select name="own_ID" class="form-control">
            <option value="<?= htmlspecialchars($_SESSION['user_id']); ?>">
                <?= htmlspecialchars($_SESSION['user_id']); ?>
            </option>
            </select>
        </div>


        <div class="mb-3">
            <label class="form-label">Property Type</label>
            <select name="proptype" class="form-control">
                <option value="0">Select type</option>
                <?php foreach ($proptypes as $proptype) { ?>
                    <option value="<?= htmlspecialchars($proptype); ?>">
                        <?= htmlspecialchars($proptype); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">
                Property Location
            </label>
            <input type="text" 
                class="form-control" 
                value = "<?=$prop_loc?>"
                name = "prop_loc">
        </div>
        
        <div class="mb-3">
            <label class="form-label">
                Price
            </label>
            <input type="text" 
                class="form-control" 
                value = "<?=$prop_price?>"
                name = "prop_price">
        </div>
        <div class="mb-3">
            <label class="form-label">
                Amenities
            </label>
            <input type="text" 
                class="form-control"
                value = "<?=$prop_amen?>" 
                name = "prop_amen">
        </div>
        <div class="mb-3">
            <label class="form-label">
                Image Property
            </label>
            <input type="file" 
                class="form-control" 
                value = "<?=$prop_image?>"
                name = "prop_image">

        <div class="mb-3">
            <label class="form-label">Availability</label>
            <select name="prop_avail" class="form-control">
                <option value="0">Select availability</option>
                <?php foreach ($pravails as $pravail) { ?>
                    <option value="<?= htmlspecialchars($pravail); ?>">
                        <?= htmlspecialchars($pravail); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <button type="submit" 
            class="btn btn-primary">
            Add Rental Property</button>
    </form>
    </div>
</body>
</html>


<?php }else{
    header("Location: login.php");
    exit;
}

?>