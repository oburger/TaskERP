<?php

// Authentifizierung
if(isset($_COOKIE["nickname"]) && isset($_COOKIE["password"]) && login($_COOKIE["nickname"], $_COOKIE["password"]) && permission($_COOKIE["nickname"], "time_recording")) {
    
    if(isset($_GET["section"]) && $_GET["section"] == "delete") {
        $sql = "DELETE FROM time_recording WHERE id = ".$_GET["id"].";";
        if($conn->query($sql)) {
            echo "Datensatz gelöscht.";
        } else {
            echo $conn->error;
        }
    }
    
    
    // Daten einfügen nachMitarbeiter

    if(isset($_POST["job"]) && $_POST["start"] != "" && $_POST["end"] != "") {
        $zeit1 = strtotime($_POST["start"]);
        $zeit2 = strtotime($_POST["end"]);
        $difference = ($zeit2 - $zeit1)/60/60;
    } elseif(isset($_POST["job"])) {
        $difference = $_POST["difference"];
    }

    if(isset($_POST["formular"]) && $_POST["formular"] == "nachMitarbeiterFormular" && isset($_POST["job"])) {
        $sql = "INSERT INTO time_recording (employee, job, project, date, start, end, difference) VALUES ('".$_POST["mitarbeiter"]."','".$_POST["job"]."','".$_POST["project"]."','".preg_replace('#^(\d{2})\.(\d{2})\.(\d{4})$#', '\3-\2-\1', $_POST["date"])."','".$_POST["start"]."','".$_POST["end"]."','".$difference."');";
        $conn->query($sql) or die($conn->error);
    }
    
    // Daten einfügen nachProjekt
                
    if(isset($_POST["formular"]) && $_POST["formular"] == "nachProjektFormular") {
        $anz = count($_POST["employee"]);
        for($i = 0; $i < $anz; $i++) {
            $sql = "INSERT INTO time_recording (employee, job, project, date, start, end, difference) VALUES ('".$_POST["employee"][$i]."','".$_POST["job"]."','".$_POST["project"]."','".preg_replace('#^(\d{2})\.(\d{2})\.(\d{4})$#', '\3-\2-\1', $_POST["date"])."','".$_POST["start"]."','".$_POST["end"]."',$difference);";
            $conn->query($sql);
        }
    }

    
    
    
//    
//    $sql = "INSERT INTO time_recording (start) VALUES ('14:30:30');";    
//    $conn->query($sql) or die($conn->error);
//    
//    $sql = "SELECT id, start FROM time_recording;";
//    $result = $conn->query($sql) or die($conn->error);
//    
//    while($row = $result->fetch_assoc()) {
//        echo $row["start"]."<br />";
//    }
//    
    
    // Inhalt Time Recording Abfrage
    
    $sql = "SELECT id, employee, date, start, end, difference FROM time_recording;";
    $result = $conn->query($sql) or die($conn->error);   
    
    $timeRecordingAnzahl = 0;
    while($tabellenInhalt = mysqli_fetch_array($result)) {
        $timeRecording[$timeRecordingAnzahl]["id"] = $tabellenInhalt["id"];
        $timeRecording[$timeRecordingAnzahl]["employee"] = $tabellenInhalt["employee"];
        $timeRecording[$timeRecordingAnzahl]["date"] = $tabellenInhalt["date"];
        $timeRecording[$timeRecordingAnzahl]["start"] = $tabellenInhalt["start"];
        $timeRecording[$timeRecordingAnzahl]["end"] = $tabellenInhalt["end"];
        $timeRecording[$timeRecordingAnzahl]["difference"] = $tabellenInhalt["difference"];
        $timeRecordingAnzahl++;
    }

?>
    <ul class="nav nav-tabs nav-justified">
        <li role="presentation" id="nachMitarbeiterSelectorLi"><a href="#" id="nachMitarbeiterSelector">Nach Mitarbeiter</a></li>
        <li role="presentation" id="nachProjektSelectorLi"><a href="#" id="nachProjektSelector">Nach Projekt</a></li>
    </ul>   
    <br />
    
    <!--    <h2>Filter</h2>-->
    <div id="nachMitarbeiter">
        <form action="index.php?page=time_recording&" method="GET">
            <input type="hidden" name="page" value="<?php echo $_GET["page"]; ?>">
            <input type="hidden" name="section" value="nachMitarbeiter">
            <div class="form-group">
                <label for="mitarbeiter">Mitarbeiter</label>
                <select class="form-control" id="mitarbeiter" name="mitarbeiter">
                    <option></option>
                    <?php
                    $sql = "SELECT id, first_name, last_name FROM employees;";
                    $result = $conn->query($sql) or die($conn->error);
                    while($row = $result->fetch_assoc()) {
                        if(isset($_GET["mitarbeiter"]) && $_GET["mitarbeiter"] == $row["id"]) {
                            echo "<option selected=\"selected\" value=".$row["id"].">".$row["first_name"]." ".$row["last_name"]."</option>";
                        } else {
                            echo "<option value=".$row["id"].">".$row["first_name"]." ".$row["last_name"]."</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="tag">Tag</label>
                <?php
                if(isset($_GET["tag"])) {
                    echo "<input type=\"datetime\" class=\"form-control datepicker\" id=\"tag\" name=\"tag\" value=\"".$_GET["tag"]."\">";
                } else {
                    echo "<input type=\"datetime\" class=\"form-control datepicker\" id=\"tag\" name=\"tag\">";
                }
                ?>
            </div>
            <div class="form-group">
                <div class="form-group">
                    <button type="submit" class="btn btn-default">Anzeigen</button>
                </div>
            </div>
        </form>

    <!--    <h2>Zeiten</h2>-->

        <table class="table table-bordered table-striped">
            <tr>
                <th>Aktion</th>
                <th>Tätigkeit</th>
                <th>Projekt</th>
                <th>Tag</th>
                <th>Von</th>
                <th>Bis</th>
                <th>Stunden</th>
            </tr>
            <?php
            if(isset($_GET["mitarbeiter"]) && isset($_GET["tag"]) && $_GET["tag"] != "" && $_GET["mitarbeiter"] != "") {
                $sql = "SELECT id, employee, job, date, project, start, end, difference FROM time_recording WHERE employee = '".$_GET["mitarbeiter"]."' AND date = '".preg_replace('#^(\d{2})\.(\d{2})\.(\d{4})$#', '\3-\2-\1', $_GET["tag"])."';";
                $result = $conn->query($sql) or die($conn->error);
                if($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        if($row["project"]!="") {
                            $sql_p = "SELECT project FROM projects WHERE id = '".$row["project"]."';";
                            $result_p = $conn->query($sql_p) or die($conn->error);
                            $row_p = $result_p->fetch_assoc();
                            $project = $row_p["project"];
                        } else {
                            $project = "";
                        }
                        echo "
                        <tr>
                            <td style=\"width:30px;\"><a href=\"index.php?page=time_recording&section=delete&id=".$row["id"]."\"><span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\"></span></td>
                            <td>".$row["job"]."</td>
                            <td>".$project."</td>
                            <td>".$row["date"]."</td>
                            <td>".$row["start"]."</td>
                            <td>".$row["end"]."</td>
                            <td>".$row["difference"]."</td>
                        </tr>";
                    }
                    echo "
                            <tr>
                            <th>Ergebnis</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>Summe</th>
                            <th></th>
                        </tr>";
                }
            } elseif(isset($_GET["mitarbeiter"]) && $_GET["mitarbeiter"] != "") {
                $sql = "SELECT id, employee, job, date, project, start, end, difference FROM time_recording WHERE employee = '".$_GET["mitarbeiter"]."' ORDER BY date;";
                $result = $conn->query($sql) or die($conn->error);
                if($result->num_rows > 0) {
                    $summe = 0;
                    while($row = $result->fetch_assoc()) {
                        if($row["project"]!="") {
                            $sql_p = "SELECT project FROM projects WHERE id = '".$row["project"]."';";
                            $result_p = $conn->query($sql_p) or die($conn->error);
                            $row_p = $result_p->fetch_assoc();
                            $project = $row_p["project"];
                        } else {
                            $project = "";
                        }
                        
                        $summe += $row["difference"];
                        
                        echo "
                        <tr>
                            <td style=\"width:30px;\"><a href=\"index.php?page=time_recording&section=delete&id=".$row["id"]."\"><span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\"></span></td>
                            <td>".$row["job"]."</td>
                            <td>".$project."</td>
                            <td>".$row["date"]."</td>
                            <td>".$row["start"]."</td>
                            <td>".$row["end"]."</td>
                            <td>".$row["difference"]."</td>
                        </tr>";
                    }
                    echo "
                            <tr>
                            <th>Ergebnis</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>Summe</th>
                            <th>".$summe."</th>
                        </tr>";
                }
            } else {
                echo "
                    <tr>
                        <td style=\"width:30px;\"></td>
                        <td>Keine Auswahl getroffen.</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Ergebnis</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>Summe</th>
                        <th></th>
                    </tr>";
            }
            ?>
            
        </table>

        <h2>Zeit einfügen</h2>

        <form method="POST" action="<?php echo "index.php?page=time_recording&section=".$_GET["section"]."&mitarbeiter=".$_GET["mitarbeiter"]."&tag=".$_GET["tag"].""; ?>" name="nachMitarbeiterFormular">
            <input type="hidden" name="formular" value="nachMitarbeiterFormular">
            <div class="form-group">
                <label for="mitarbeiter">Mitarbeiter</label>
                <select class="form-control" id="mitarbeiter" name="mitarbeiter">
                    <option></option>
                    <?php
                    $sql = "SELECT id, first_name, last_name FROM employees;";
                    $result = $conn->query($sql) or die($conn->error);
                    while($row = $result->fetch_assoc()) {
                        if(isset($_GET["mitarbeiter"]) && $_GET["mitarbeiter"] == $row["id"]) {
                            echo "<option selected=\"selected\" value=".$row["id"].">".$row["first_name"]." ".$row["last_name"]."</option>";
                        } else {
                            echo "<option value=".$row["id"].">".$row["first_name"]." ".$row["last_name"]."</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="date">Tag</label>
                <?php
                if(isset($_GET["tag"])) {
                    echo "<input type=\"datetime\" class=\"form-control datepicker\" id=\"date\" name=\"date\" value=\"".$_GET["tag"]."\">";
                } else {
                    echo "<input type=\"datetime\" class=\"form-control datepicker\" id=\"date\" name=\"date\">";
                }
                ?>
            </div>
            <div class="form-group">
                <label for="job">Tätigkeit</label>
                <input type="text" class="form-control" id="job" placeholder="" name="job">
            </div>
            <div class="form-group">
                <label for="project">Projekt</label>
                <select multiple class="form-control" id="project" name="project">
                    <option selected="selected"></option>
                    <?php
                    $sql = "SELECT id, project FROM projects;";
                    $result = $conn->query($sql) or die($conn->error);
                    while($row = $result->fetch_assoc()) {
                        echo "<option value=\"".$row["id"]."\">".$row["project"]."</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="start">Von</label>
                <input type="text" class="form-control" id="start" placeholder="" name="start">
            </div>
            <div class="form-group">
                <label for="end">Bis</label>
                <input type="text" class="form-control" id="end" placeholder="" name="end">
            </div>
            <div class="form-group">
                <label for="difference">Stunden</label>
                <input type="text" class="form-control" id="difference" placeholder="" name="difference">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-default">Eintragen</button>
                <button type="reset" class="btn btn-default">Reset</button>
            </div>
        </form>
    </div>
    

    
    <div id="nachProjekt">
        <!--    <h2>Filter</h2>-->
        <form method="GET" action="index.php?page=time_recording">
            <div class="form-group">
                <input type="hidden" name="page" value="<?php echo $_GET["page"]; ?>">
                <input type="hidden" name="section" value="nachProjekt">
                <label for="project">Projekt</label>
                <select class="form-control" id="project" name="project">
                    <option></option>
                    <?php
                    $sql = "SELECT id, project FROM projects;";
                    $result = $conn->query($sql) or die($conn->error);
                    while($row = $result->fetch_assoc()) {
                        if(isset($_GET["project"]) && $_GET["project"] == $row["id"]) {
                            echo "<option selected=\"selected\" value=\"".$row["id"]."\">".$row["project"]."</option>";
                        } else {
                            echo "<option value=\"".$row["id"]."\">".$row["project"]."</option>";
                        }
                    }
                    ?>
                </select>
            </div>
    <!--        <div class="form-group">
                <label for="tag">Tag</label>
                <input type="datetime" class="form-control datepicker" id="tag" placeholder="" >
            </div>-->
            <div class="form-group">
                <div class="form-group">
                    <button type="submit" class="btn btn-default">Anzeigen</button>
                </div>
            </div>
        </form>

    <!--    <h2>Zeiten</h2>-->
    


        <table class="table table-bordered table-striped">
            <tr>
                <th>Aktion</th>
                <th>Tätigkeit</th>
                <th>Mitarbeiter</th>
                <th>Datum</th>
                <th>Von</th>
                <th>Bis</th>
                <th>Stunden</th>
            </tr>
            <?php

            if(isset($_GET["section"]) && $_GET["section"] == "nachProjekt" && isset($_GET["project"])) {
                $sql = "SELECT id, employee, job, project, date, start, end, difference FROM time_recording WHERE project = ".$_GET["project"]." ORDER BY date;";
                $result = $conn->query($sql) or die($conn->error);
                
                $summe = 0;
                
                while($row = $result->fetch_assoc()) {
                    $sql_e = "SELECT first_name, last_name FROM employees WHERE id = ".$row["employee"].";";
                    $result_e = $conn->query($sql_e) or die($conn->error);
                    $row_e = $result_e->fetch_assoc();
                    echo "
                        <tr>
                            <td style=\"width:30px;\"><a href=\"\"><span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\"></span></a></td>
                            <td>".$row["job"]."</td>
                            <td>".$row_e["first_name"]." ".$row_e["last_name"]."</td>
                            <td>".$row["date"]."</td>
                            <td>".$row["start"]."</td>
                            <td>".$row["end"]."</td>
                            <td>".$row["difference"]."</td>
                        </tr>";
                    $summe += $row["difference"];
                }
                
                echo "<tr>
                        <th>Ergebnis</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>Summe</th>
                        <th>".$summe."</th>
                    </tr>";
            } else {
                echo "<tr>
                        <td></td>
                        <td>Keine Auswahl getroffen.</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>";
                echo "<tr>
                        <th>Ergebnis</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>Summe</th>
                        <th></th>
                    </tr>
                </table>";                
            }

            ?>
            
        </table>

        <h2>Zeit einfügen</h2>

        <form name="nachProjektFormular" id="nachProjektFormular" method="POST" 
              action="<?php echo "index.php?page=time_recording&section=nachProjekt&project=".$_GET["project"] ?>">
            <input type="hidden" name="formular" value="nachProjektFormular">
            <label for="project">Projekt</label>
            <div class="form-group">
                <select class="form-control" id="project" name="project">
                    <option></option>
                    <?php
                    $sql = "SELECT id, project FROM projects;";
                    $result = $conn->query($sql) or die($conn->error);
                    while($row = $result->fetch_assoc()) {
                        if(isset($_GET["project"]) && $_GET["project"] == $row["id"]) {
                            echo "<option selected=\"selected\" value=\"".$row["id"]."\">".$row["project"]."</option>";
                        } else {
                            echo "<option value=\"".$row["id"]."\">".$row["project"]."</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="job">Tätigkeit</label>
                <input type="text" class="form-control" id="job" placeholder="" name="job" >
            </div>
            <div class="form-group">
                <label for="employee">Mitarbeiter</label>
                <select multiple class="form-control" id="employee" name="employee[]">
<!--                    <option selected="selected" ></option>-->
                    <?php
                    $sql = "SELECT id, first_name, last_name FROM employees;";
                    $result = $conn->query($sql) or die($conn->error);
                    while($row = $result->fetch_assoc()) {
                        echo "<option value=\"".$row["id"]."\">".$row["first_name"]." ".$row["last_name"]."</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="tag">Datum</label>
                <input type="datetime" class="form-control datepicker" id="date2" placeholder="" name="date">
            </div>
            <div class="form-group">
                <label for="start">Von</label>
                <input type="text" class="form-control" id="start2" placeholder="" name="start">
            </div>
            <div class="form-group">
                <label for="end">Bis</label>
                <input type="text" class="form-control" id="end2" placeholder="" name="end">
            </div>
            <div class="form-group">
                <label for="difference">Stunden</label>
                <input type="text" class="form-control" id="difference2" placeholder="" name="difference"">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-default">Eintragen</button>
                <button type="reset" class="btn btn-default">Reset</button>
            </div>
        </form>
    </div>
    
    
    

    

<!--    <script> 
    $(document).ready(function() {
        $('#nachProjektFormular').formValidation({
            // I am validating Bootstrap form
            framework: 'bootstrap',

            // Feedback icons
            icon: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },

            locale: 'de_DE',

            // List of fields and their validation rules
            fields: {
                // name="field"

                project: {
                    validators: {
                        notEmpty: {
                        }
                    }
                },
                'employee[]': {
                    validators: {
                        notEmpty: {
                        }
                    }
                },
                end: {
                    validators: {
                        notEmpty: {
                        }
                    }
                }
            }
        });
    });
    </script>-->

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
               function GetURLParameter(sParam)
               {
                       var sPageURL = window.location.search.substring(1);
                           var sURLVariables = sPageURL.split('&');
                               for (var i = 0; i < sURLVariables.length; i++) {
                                           var sParameterName = sURLVariables[i].split('=');
                                                   if (sParameterName[0] == sParam) {
                                                                   return sParameterName[1];
                                                   }
                               }
               }
        

        $(document).ready(
                
            function() {

                $("#nachMitarbeiter").hide();
                $("#nachProjekt").hide();

                if(GetURLParameter('section')=="nachMitarbeiter") {
                    $("#nachMitarbeiter").show();
                    $("#nachMitarbeiterSelectorLi").attr("class", "active");
                }

                if(GetURLParameter('section')=="nachProjekt") {
                    $("#nachProjekt").show();
                    $("#nachProjektSelectorLi").attr("class", "active");
                }

                $("#nachMitarbeiterSelector").click(function() {
                    $("#nachMitarbeiter").show();
                    $("#nachProjekt").hide();
                    $("#nachMitarbeiterSelectorLi").attr("class", "active");
                    $("#nachProjektSelectorLi").attr("class", "");
                });

                $("#nachProjektSelector").click(function() {
                    $("#nachProjekt").show();
                    $("#nachMitarbeiter").hide();
                    $("#nachProjektSelectorLi").attr("class", "active");
                    $("#nachMitarbeiterSelectorLi").attr("class", "");
                });

                $("#start").focusout(function() {
                    if(document.nachMitarbeiterFormular.start.value != "") {
                        $("#difference").attr("disabled", true);
                    }
                    if(document.nachMitarbeiterFormular.start.value == "" && document.nachMitarbeiterFormular.end.value == "") {
                        $("#difference").removeAttr("disabled");
                    }
                });

                $("#end").focusout(function() {
                    if(document.nachMitarbeiterFormular.end.value != "") {
                        $("#difference").attr("disabled", true);
                    }
                    if(document.nachMitarbeiterFormular.start.value == "" && document.nachMitarbeiterFormular.end.value == "") {
                        $("#difference").removeAttr("disabled");
                    }
                });

                $("#start2").focusout(function() {
                    if(document.nachProjektFormular.start2.value != "") {
                        $("#difference2").attr("disabled", true);
                    }
                    if(document.nachProjektFormular.start2.value == "" && document.nachProjektFormular.end2.value == "") {
                        $("#difference2").removeAttr("disabled");
                    }
                });

                $("#end2").focusout(function() {
                    if(document.nachProjektFormular.end2.value != "") {
                        $("#difference2").attr("disabled", true);
                    }
                    if(document.nachProjektFormular.start2.value == "" && document.nachProjektFormular.end2.value == "") {
                        $("#difference2").removeAttr("disabled");
                    }
                });
            }
        );
    </script>
    

<!--     Initialize the Multiselect plugin: -->
<!--    <script type="text/javascript">
        $(document).ready(function() {
            $('#employee').multiselect();
        });
    </script>-->
    
    
<?php
    
} else {
    die("Fehler bei der Authentifizierung.");
}

?>