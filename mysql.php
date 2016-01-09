<?php    
    // Datenbankverbindung    
    $conn = new mysqli("localhost","root","","taskerp");
    if($conn->connect_error) {
            die("Connection failed");
    }
    
    $set = mysqli_set_charset($conn, 'utf8');
?>