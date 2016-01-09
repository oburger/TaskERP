<?php

if(isset($_COOKIE["nickname"]) && isset($_COOKIE["password"]) && login($_COOKIE["nickname"], $_COOKIE["password"]) && permission($_COOKIE["nickname"], "link")) {
    
    // Dokument verknüpfen
    if(isset($_GET["doc_id"])) {
        echo "<a href=\"index.php?page=link_acc_act&doc_id=".$_GET["doc_id"]."\"><h2>Umsatz verknüpfen</h2></a>";
        echo "<a href=\"index.php?page=link_pro&doc_id=".$_GET["doc_id"]."\"><h2>Projekt verknüpfen</h2></a>";
    }
    
    // Umsatz verknüpfen
    if(isset($_GET["acc_act_id"])) {
        echo "<a href=\"\"><h2>Dokument verknüpfen</h2></a>";
        echo "<a href=\"\"><h2>Projekt verknüpfen</h2></a>";
    }
    
    // Projekt verknüpfen
    if(isset($_GET["projekt_id"])) {
        echo "<a href=\"\"><h2>Dokument verknüpfen</h2></a>";
        echo "<a href=\"\"><h2>Umsatz verknüpfen</h2></a>";
    }
    
    $conn->close();
} else {
    die("Fehler bei der Authentifizierung.");
}

?>