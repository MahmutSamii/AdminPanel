<?php
try {
    $db = new PDO("mysql:host=localhost;dbname=adminpanel;charset=utf8", "root", "");
} catch (PDOException $e) {
    echo $e->getMessage();
}
