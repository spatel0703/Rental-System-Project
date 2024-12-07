<?php
session_start();

# if the admin is logged in 
if(isset($_SESSION['user_id']) && isset($_SESSION['user_email'])){

    # if the property id doesn't exist
    if (!isset($_GET['id'])) {
        # redirects to admin.php page
        header("Location: admin.php");
        exit;
    }

    $id = $_GET['id'];

    # database connection file
    include "db_conn.php";

     # rental ID helper function
     include "php/func-rentals.php";
     $rental = get_all_rentals_ids($conn, $id);

     if ($rental == 0) {
        header("Location: admin.php");
        exit;
     }

     # rental helper function
     $rentals = get_all_rentals($conn);

     include "php/func-proptype.php";
     $proptypes = get_all_types($conn);

     include "php/func-availability.php";
     $pravails = get_all_avails($conn);
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

    <form action="php/edit-rental.php"
          method = "post"
          enctype = "multipart/form-data"
          class = "shadow p-4 rounded mt-5"
          style = "width: 90%; max-width: 50rem;">

        <h1 class = "text-center pb-5 display-4 fs-3">
            Edit Rental Property
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
                hidden
                value = "<?=$rental['propertyID']?>"
                name = "prop_id">

            <input type="text" 
                class="form-control" 
                value = "<?=$rental['propName']?>"
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
                    <option value="<?= htmlspecialchars($proptype); ?>"
                        <?= $rental['proptype'] == $proptype ? 'selected' : ''; ?>>
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
                value = "<?=$rental['proplocation']?>"
                name = "prop_loc">
        </div>
        
        <div class="mb-3">
            <label class="form-label">
                Price
            </label>
            <input type="text" 
                class="form-control" 
                value = "<?=$rental['price']?>"
                name = "prop_price">
        </div>
        <div class="mb-3">
            <label class="form-label">
                Amenities
            </label>
            <input type="text" 
                class="form-control"
                value = "<?=$rental['amenities']?>" 
                name = "prop_amen">
        </div>
        <div class="mb-3">
            <label class="form-label">
                Image Property
            </label>
            <input type="file" 
                class="form-control" 
                value = "<?=$rental['imageProp']?>"
                name = "prop_image">

                <input type="text" 
                hidden
                value = "<?=$rental['imageProp']?>"
                name = "current_image">

                <a href="uploads/cover/<?=$rental['imageProp']?>"
                    class = "link-dark">Current Image</a>
        </div>

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
            Update Rental Property</button>
    </form>
    </div>
</body>
</html>


<?php }else{
    header("Location: login.php");
    exit;
}

?>