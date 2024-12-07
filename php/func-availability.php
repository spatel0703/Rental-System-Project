<?php

function get_all_avails($con){
    $sql = "SHOW COLUMNS FROM rentals LIKE 'availability'";
    $stmt = $con->prepare($sql);
    $stmt->execute();

    $pravails = []; // Initialize the variable here

    if ($stmt->rowCount() > 0) {
        $column = $stmt->fetch();
        preg_match("/^enum\((.*)\)$/", $column['Type'], $matches);

        if (isset($matches[1])) {
            $pravails = array_map(function($value) {
                return trim($value, "'");
            }, explode(",", $matches[1]));
        }
    }

    return $pravails;
}

?>
