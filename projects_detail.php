<?php

// Authentifizierung
if(isset($_COOKIE["nickname"]) && isset($_COOKIE["password"]) && login($_COOKIE["nickname"], $_COOKIE["password"]) && permission($_COOKIE["nickname"], "projects")) {
    
    // Mit Projekt verknüpfte Dokumente abfragen
    $sql1 = "SELECT * FROM rel_doc_pro WHERE pro_id = '16';";	
    $result1 = $conn->query($sql1);
    
    if($result1->num_rows > 0) { 
        
    } else {
        echo "Keine Daten vorhanden.";
    }
    

    
    
    
    
    
    
    
?>

    

<?php
    
} else {
    die("Fehler bei der Authentifizierung.");
}

?>