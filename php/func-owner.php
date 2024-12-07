<?php

function get_all_owner($con){
    $sql = "SELECT * FROM admin";
    $stmt = $con->prepare($sql);
    $stmt->execute();

    if($stmt->rowCount() > 0){
        $owners = $stmt->fetchAll();
    }else {
        $owners = 0;
    }

    return $owners;

}


?>