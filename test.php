<?php
$request = file_get_contents('php://stdin');
$json = json_decode($request, true);
var_dump($json);