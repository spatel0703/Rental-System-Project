<?php

// Function to get all property IDs from the rentals table
function get_property_ids($con) {
    $sql = "SELECT propertyID FROM rentals";
    $stmt = $con->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll();
}

// Function to get all renter IDs from the admin table, excluding the current user in session
function get_renter_ids($con, $session_user_id) {
    $sql = "SELECT userID FROM admin WHERE userID != :session_user_id";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':session_user_id', $session_user_id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll();
}


?>