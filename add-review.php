<?php
session_start();

# Check if the admin is logged in
if (isset($_SESSION['user_id']) && isset($_SESSION['user_email'])) {

    # Database connection file
    include "db_conn.php";

    # Fetch the bookingId from the URL
    if (isset($_GET['id'])) {
        $bookingId = $_GET['id'];
    } else {
        // Redirect if the bookingId is not found
        header("Location: renterAdmin.php");
        exit;
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make a Review</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">

    <!-- Bootstrap 5 JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="renterAdmin.php">Admin</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Rentals</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="add-offer.php">Make an Offer</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <form action="php/add-review.php" method="post" enctype="multipart/form-data" class="shadow p-4 rounded mt-5" style="width: 90%; max-width: 50rem;">
            <h1 class="text-center pb-5 display-4 fs-3">Add New Review</h1>

            <?php if (isset($_GET['error'])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($_GET['error']); ?>
                </div>
            <?php } ?>

            <?php if (isset($_GET['success'])) { ?>
                <div class="alert alert-success" role="alert">
                    <?= htmlspecialchars($_GET['success']); ?>
                </div>
            <?php } ?>

            <!-- Hidden input to store the bookingId -->
            <input type="hidden" name="booking_id" value="<?= htmlspecialchars($bookingId); ?>">

            <!-- Rating field -->
            <div class="mb-3">
                <label class="form-label">Rating (1-5)</label>
                <input type="number" class="form-control" name="rating" min="1" max="5" required>
            </div>

            <!-- Review Text -->
            <div class="mb-3">
                <label class="form-label">Review</label>
                <textarea class="form-control" name="comment" rows="5" required></textarea>
            </div>

            <!-- Review Date -->
            <div class="mb-3">
                <label class="form-label">Review Date</label>
                <input type="date" class="form-control" name="review_date" required>
            </div>

            <button type="submit" class="btn btn-primary">Submit Review</button>
        </form>
    </div>
</body>
</html>

<?php
} else {
    header("Location: login.php");
    exit;
}
?>
