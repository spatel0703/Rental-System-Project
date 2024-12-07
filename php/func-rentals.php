<?php

function get_all_rentals($con){
    $sql = "SELECT * FROM rentals ORDER by propertyID DESC";
    $stmt = $con->prepare($sql);
    $stmt->execute();

    if($stmt->rowCount() > 0){
        $rentals = $stmt->fetchAll();
    }else {
        $rentals = 0;
    }

    return $rentals;

}

function get_all_rentals_ids($con, $id){
    $sql = "SELECT * FROM rentals WHERE propertyID=?";
    $stmt = $con->prepare($sql);
    $stmt->execute([$id]);

    if($stmt->rowCount() > 0){
        $rental = $stmt->fetch();
    }else {
        $rental = 0;
    }

    return $rental;

}

# search rentals function
function search_rentals($con, $key){
    # search algorithm
    $key = "%{$key}%";

    $sql = "SELECT * FROM rentals 
            WHERE propName LIKE ?
            OR proptype LIKE ?
            OR proplocation LIKE ?
            OR price LIKE ?
            OR amenities LIKE ?
            OR AVAILABILITY LIKE ?";
    $stmt = $con->prepare($sql);
    $stmt->execute([$key, $key, $key, $key, $key, $key]);

    if($stmt->rowCount() > 0){
        $s_rentals = $stmt->fetchAll();
    }else {
        $s_rentals = 0;
    }

    return $s_rentals;

}

function get_rentals_bytype($con, $id){
    $sql = "SELECT * FROM rentals WHERE proptype=?";
    $stmt = $con->prepare($sql);
    $stmt->execute([$id]);

    if($stmt->rowCount() > 0){
        $rentals_bytype = $stmt->fetchAll();
    }else {
        $rentals_bytype = 0;
    }

    return $rentals_bytype;

}

// Inside php/func-rentals.php
function get_rentals_type($conn, $proptype) {
    $sql = "SELECT * FROM rentals WHERE proptype = :proptype";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':proptype', $proptype, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


?>