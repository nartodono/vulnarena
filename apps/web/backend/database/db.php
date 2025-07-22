<?php

$credential = require_once __DIR__ . "/../config/config.php";

return new mysqli(
    $credential['database']["server"],
    $credential['database']["username"],
    $credential['database']["password"],
    $credential['database']["database"]
);

?>
