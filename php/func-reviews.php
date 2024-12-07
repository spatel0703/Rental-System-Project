<?php

function get_all_review($con){
    $sql = "SELECT * FROM review";
    $stmt = $con->prepare($sql);
    $stmt->execute();

    if($stmt->rowCount() > 0){
        $reviews = $stmt->fetchAll();
    }else {
        $reviews = 0;
    }

    return $reviews;

}


?>