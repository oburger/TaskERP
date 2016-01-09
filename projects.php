<?php
// Authentifizierung
if(isset($_COOKIE["nickname"]) && isset($_COOKIE["password"]) && login($_COOKIE["nickname"], $_COOKIE["password"]) && permission($_COOKIE["nickname"], "projects")) {
    
    // Spezialroutine:
    // Prüfen des GET-Parameters, um festzustellen ob Dokument verknüpft werden soll
    if(isset($_GET["pro_id"]) && isset($_GET["doc_id"])) {
        $sql = "INSERT INTO rel_doc_pro (doc_id, pro_id) VALUES (".$_GET["doc_id"].", ".$_GET["pro_id"].");";
        $result = $conn->query($sql);
        
        echo "<p>Erfolgreich verknüpft.</p>";
    }

    if($_GET["page"]=="projects_add") {
        $sql = "INSERT INTO projects (contact_firm, user, date, project, start, end, status) "
                . "VALUES ('".htmlspecialchars($_POST["kontakt"])."','".htmlspecialchars($_COOKIE["nickname"])."','".date("Y-m-d")."','".htmlspecialchars($_POST["projektname"])."','".htmlspecialchars(preg_replace('#^(\d{2})\.(\d{2})\.(\d{4})$#', '\3-\2-\1', $_POST["beginn"]))."','".htmlspecialchars(preg_replace('#^(\d{2})\.(\d{2})\.(\d{4})$#', '\3-\2-\1', $_POST["ende"]))."','".htmlspecialchars($_POST["status"])."');";

        $conn->query($sql) or die($conn->error);
    }
          
    // Formular zum Eintragen von Projekten

    // Abfrage Dokumentendaten
    $sql = "SELECT * FROM contacts_firm;";
    $result = $conn->query($sql);
?>
    <div class="btn-group" role="group">
        <button id="hinzufügen" type="button" class="btn btn-default">Projekt hinzufügen</button>
        <button id="ausblenden" type="button" class="btn btn-default fadeIn">Formular reduzieren</button>
    </div>

    <br /><br />

    <form class="form-horizontal fadeIn" id="documentForm" method="POST" action="index.php?page=projects_add" enctype="multipart/form-data">
        <div class="form-group">
          <label for="projektname" class="col-sm-1 control-label">Projekt</label>
          <div class="col-sm-5">
            <input type="text" class="form-control" id="projektname" placeholder="" name="projektname">
          </div>
        </div>
        <div class="form-group">
          <label for="kontakt" class="col-sm-1 control-label">Kontakt</label>
          <div class="col-sm-5">
            <select multiple class="form-control" id="kontakt" name="kontakt">
                <option selected="selected"></option>
<?php
                if($result->num_rows>0) {
                    while($row = $result->fetch_assoc()) {
                        if($row["freigabe"]==true) {
                            echo "<option value=\"".$row["id"]."\">".$row["firm"]."</option>";
                        }
                    }
                } else {
                    echo "Keine Einträge vorhanden.";
                }
?>
              </select>
          </div>
        </div>
        <div class="form-group">
          <label for="beginn" class="col-sm-1 control-label">Beginn</label>
          <div class="col-sm-5">
            <input type="datetime" class="form-control datepicker" id="beginn" placeholder="" name="beginn">
          </div>
        </div>
        <div class="form-group">
          <label for="ende" class="col-sm-1 control-label">Ende</label>
          <div class="col-sm-5">
            <input type="datetime" class="form-control datepicker" id="ende" placeholder="" name="ende">
          </div>
        </div>
        <div class="form-group">
          <label for="status" class="col-sm-1 control-label">Status</label>
          <div class="col-sm-5">
            <select multiple class="form-control" id="status" name="status">
                <option selected="selected">Offen</option>
                <option>In Bearbeitung</option>
                <option>Abgeschlossen</option>
            </select>
          </div>
        </div>
        <div class="form-group">
        <div class="col-sm-offset-1 col-sm-10">
          <button type="submit" class="btn btn-default">Hinzufügen</button>
          <button type="reset" class="btn btn-default">Reset</button>
        </div>
      </div>
    </form>

<script>
$(function() {
   $( ".datepicker" ).datepicker({
     // Anpassung auf deutsches Datumsformat
     dateFormat: 'dd.mm.yy',
     monthNames: ['Januar','Februar','März','April','Mai','Juni','Juli','August','September','Oktober','November','Dezember'],
     dayNames: ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag','Samstag'],
     dayNamesMin: ['So', 'Mo', 'Die', 'Mi', 'Do', 'Fre', 'Sa'],
     firstDay: 1
   });
 });
</script>

<script>
$(".fadeIn").hide();
$("#hinzufügen").click(function() {
  $(".fadeIn").fadeIn("slow");
});
$("#ausblenden").click(function() {
  $(".fadeIn").fadeOut("slow");
});
</script>

<?php   
    // Auflistung der Projekte

    // Abfrage Dokumentendaten
    $sql = "SELECT * FROM projects;";
    $result = $conn->query($sql);
?>
    <table class="table">
        <tr>
            <th>Aktionen</th>
            <th>PNr</th>
            <th>Projekt</th>
            <th>Kontakt</th>
            <th>Status</th>
            <th>Beginn</th>
            <th>Ende</th>
        </tr>

<?php
    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
                echo "<td></td>";
                if(isset($_GET["doc_id"])) {
                    echo "<td><a href=\"index.php?page=link_pro&doc_id=".$_GET["doc_id"]."&pro_id=".$row["id"]."\">".$row["id"]."</a></td>";
                } else {
                    echo "<td>".$row["id"]."</td>";
                }
                echo "<td><a href=\"index.php?page=projects_detail&pro_id=".$row["id"]." \">".$row["project"]."</a></td>";

                // Abfrage Unternehmens ID nach Firmierung
                $sql2 = "SELECT * FROM contacts_firm WHERE id = '".$row["contact_firm"]."';";
                $result2 = $conn->query($sql2);
                $row2 = $result2->fetch_assoc(); 
                if($row2["freigabe"]==true) {
                    echo "<td>".$row2["firm"]."</td>";
                } elseif($row2["freigabe"]=="") {
                    echo "<td></td>";
                } else {
                    echo "<td>Kontakt nicht freigegeben.</td>";
                }

                echo "<td>".$row["status"]."</td>";
                echo "<td>".$row["start"]."</td>";
                echo "<td>".$row["end"]."</td>";
            echo "</tr>";
            
//            // Ansicht für Bearbeiten-Funktion
//            echo "<form>";
//            echo "<tr class=\"hiddenRow\" style=\"background-color:lightgrey;\">";
//                echo "<td></td>";
//                echo "<td></td>";
//                echo "<td><input type=\"text\" value=\"".$row["project"]."\" /></td>";
//
//                // Abfrage Unternehmens ID nach Firmierung
//                $sql2 = "SELECT * FROM contacts_firm WHERE id = '".$row["contact_firm"]."';";
//                $result2 = $conn->query($sql2);
//                $row2 = $result2->fetch_assoc(); 
//                if($row2["freigabe"]==true) {
//                    echo "<td>".$row2["firm"]."</td>";
//                } elseif($row2["freigabe"]=="") {
//                    echo "<td></td>";
//                } else {
//                    echo "<td>Kontakt nicht freigegeben.</td>";
//                }
//
//                echo "<td>".$row["status"]."</td>";
//                echo "<td>".$row["start"]."</td>";
//                echo "<td>".$row["end"]."</td>";
//            echo "</tr>";
//            echo "<tr class=\"hiddenRow\" style=\"background-color:lightgrey;\">";
//                echo "<td></td>";
//                echo "<td></td>";
//                echo "<td></td>";
//                echo "<td><button type=\"submit\" class=\"btn btn-default\">Aktualisieren</button></td>";
//                echo "<td></td>";
//                echo "<td></td>";
//                echo "<td></td>";
//            echo "</tr>";
//            echo "</form>";
        }
    } else {
        echo "Kein Projekt angelegt.";
    }
?>
    </table>

    <script>
    $(".hiddenRow").hide();
    $(".test").click(function() {
      $(".hiddenRow").fadeIn("slow");
    });
    </script>
<?php   

    
} else {
	die("Fehler bei der Authentifizierung.");
}
?>