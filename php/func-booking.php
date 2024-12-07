<?php

function get_all_booking($con){
    $sql = "SELECT * FROM booking";
    $stmt = $con->prepare($sql);
    $stmt->execute();

    if($stmt->rowCount() > 0){
        $bookings = $stmt->fetchAll();
    }else {
        $bookings = 0;
    }

    return $bookings;

}


function get_all_bookings_ids($con, $id){
    $sql = "SELECT * FROM booking WHERE bookingId=?";
    $stmt = $con->prepare($sql);
    $stmt->execute([$id]);

    if($stmt->rowCount() > 0){
        $booking = $stmt->fetch();
    }else {
        $booking = 0;
    }

    return $booking;

}

?>