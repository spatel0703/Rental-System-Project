<?php

function get_all_statuses($con){
    $sql = "SHOW COLUMNS FROM booking LIKE 'status'";
    $stmt = $con->prepare($sql);
    $stmt->execute();

    $propstatus = []; // Initialize the variable here

    if ($stmt->rowCount() > 0) {
        $column = $stmt->fetch();
        preg_match("/^enum\((.*)\)$/", $column['Type'], $matches);

        if (isset($matches[1])) {
            $propstatuses = array_map(function($value) {
                return trim($value, "'");
            }, explode(",", $matches[1]));
        }
    }

    return $propstatuses;
}

?>
