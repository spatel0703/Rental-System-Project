<?php

function get_all_types($con){
    $sql = "SHOW COLUMNS FROM rentals LIKE 'proptype'";
    $stmt = $con->prepare($sql);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $column = $stmt->fetch();
        preg_match("/^enum\((.*)\)$/", $column['Type'], $matches);

        if (isset($matches[1])) {
            $proptypes = array_map(function($value) {
                return trim($value, "'");
            }, explode(",", $matches[1]));
        } else {
            $proptypes = [];
        }
    } else {
        $proptypes = [];
    }

    return $proptypes;
}

?>
