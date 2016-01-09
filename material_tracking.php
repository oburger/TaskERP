<?php

// Authentifizierung
if(isset($_COOKIE["nickname"]) && isset($_COOKIE["password"]) && login($_COOKIE["nickname"], $_COOKIE["password"]) && permission($_COOKIE["nickname"], "material_tracking")) {
    
    // Insert formular to database
    
    if(isset($_GET["page"]) && $_GET["page"] == "material_tracking_add") {
        $sql = "INSERT INTO material_tracking (project, date, supplier, material, quantity, unit) VALUES ("
                . "'".$_POST["project"]."', '".preg_replace('#^(\d{2})\.(\d{2})\.(\d{4})$#', '\3-\2-\1', $_POST["date"])."','".$_POST["supplier"]."','".$_POST["material"]."','".$_POST["quantity"]."','".$_POST["unit"]."');";
        
        if($conn->query($sql)) {
            echo "<div class=\"alert alert-success\" role=\"alert\"><strong><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span> Erfolgreich:</strong> Material eingetragen!</div>";
        } else {
            $conn->error;
        }
    }
    
?>

    <form action="index.php" method="GET">
        <input type="hidden" name="page" value="material_tracking">
        
        <div class="form-group">
            <label for="project_selector">Projekt</label>
            <select class="form-control" id="project_selector" name="project">
                <option></option>
                <?php
                $sql = "SELECT id, project FROM projects;";
                $result = $conn->query($sql) or die($conn->error);
                while($row = $result->fetch_assoc()) {
                    if(isset($_GET["project"]) && $_GET["project"] == $row["id"]) {
                        echo "<option value=\"".$row["id"]."\" selected=\"selected\">".$row["project"]."</option>";
                    } else {
                        echo "<option value=\"".$row["id"]."\">".$row["project"]."</option>";
                    }
                }
                ?>
            </select>
        </div>
        
        <div class="form-group">
            <div class="form-group">
                <button type="submit" class="btn btn-default">Anzeigen</button>
            </div>
        </div>
    </form>

    <br />
    
    <!-- Selector for formular -->

    <div class="btn-group" role="group">
        <button id="hinzufügen" type="button" class="btn btn-default">Material hinzufügen</button>
        <button id="ausblenden" type="button" class="btn btn-default fadeIn">Formular reduzieren</button>
    </div>

    <br /><br />
    
    <!-- Formular -->

    <form class="form-horizontal fadeIn" id="documentForm" method="POST" action="index.php?page=material_tracking_add" enctype="multipart/form-data">
        
        <div class="form-group">
          <label for="project" class="col-sm-1 control-label">Projekt</label>
          <div class="col-sm-5">
            <select multiple class="form-control" id="project" name="project">
                <?php
                $sql = "SELECT id, project FROM projects;";
                $result = $conn->query($sql) or die($conn->error);
                if($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        if(isset($_GET["project"]) && $_GET["project"] == $row["id"]) {
                        echo "<option value=\"".$row["id"]."\" selected=\"selected\">".$row["project"]."</option>";
                    } else {
                        echo "<option value=\"".$row["id"]."\">".$row["project"]."</option>";
                    }
                    }
                } else {
                    echo "Kein Eintrag verfügbar";
                }
                ?>
              </select>
          </div>
        </div>
        
        <div class="form-group">
          <label for="date" class="col-sm-1 control-label">Tag</label>
          <div class="col-sm-5">
            <input type="datetime" class="form-control datepicker" id="date" placeholder="" name="date">
          </div>
        </div>
        
        <div class="form-group">
          <label for="supplier" class="col-sm-1 control-label">Lieferant</label>
          <div class="col-sm-5">
            <select multiple class="form-control" id="supplier" name="supplier">
                <?php
                $sql = "SELECT id, firm FROM contacts_firm WHERE freigabe = 1 AND relation = 'supplier';";
                $result = $conn->query($sql) or die($conn->error);
                if($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<option value=\"".$row["id"]."\">".$row["firm"]."</option>";
                    }
                } else {
                    echo "<p>Kein Eintrag verfügbar</p>";
                }
                ?>
              </select>
          </div>
        </div>
        
        <div class="form-group">
          <label for="material" class="col-sm-1 control-label">Material</label>
          <div class="col-sm-5">
            <input type="text" class="form-control" id="material" placeholder="" name="material">
          </div>
        </div>
        
        <div class="form-group">
          <label for="quantity" class="col-sm-1 control-label">Menge</label>
          <div class="col-sm-5">
            <input type="text" class="form-control" id="quantity" placeholder="" name="quantity">
          </div>
        </div>
        
        <div class="form-group">
          <label for="unit" class="col-sm-1 control-label">Einheit</label>
          <div class="col-sm-5">
            <select multiple class="form-control" id="unit" name="unit">
                <option>Stk</option>
                <option>Lfm</option>
                <option>m^2</option>
                <option>m^3</option>
                <option>kg</option>
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
    
    <!-- Scripts for the formular -->

    <script>
    $(".fadeIn").hide();
    $("#hinzufügen").click(function() {
      $(".fadeIn").fadeIn("slow");
    });
    $("#ausblenden").click(function() {
      $(".fadeIn").fadeOut("slow");
    });
    </script>
    
    <script> 
    $(document).ready(function() {
        $('#documentForm')
        .formValidation({
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
                project: {
                    validators: {
                        notEmpty: {
                        }
                    }
                },
                date: {
                    validators: {
                        notEmpty: {
                        },
                        date: {
                            format: 'DD.MM.YYYY',

                        }
                    }
                },
                supplier: {
                    validators: {
                        notEmpty: {
                        }
                    }
                },
                material: {
                    validators: {
                        notEmpty: {
                        }
                    }
                },
                quantity: {
                    validators: {
                        notEmpty: {
                        },
                        numeric: {
                        }
                    }
                },
                unit: {
                    validators: {
                        notEmpty: {
                        }
                    }
                }
            }
        })
        .find('[name="date"]')
            .datepicker({
                // Anpassung auf deutsches Datumsformat
                dateFormat: 'dd.mm.yy',
                monthNames: ['Januar','Februar','März','April','Mai','Juni','Juli','August','September','Oktober','November','Dezember'],
                dayNames: ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag','Samstag'],
                dayNamesMin: ['So', 'Mo', 'Die', 'Mi', 'Do', 'Fre', 'Sa'],
                firstDay: 1,
                onSelect: function(date, inst) {
                    // Revalidate the field when choosing it from the datepicker
                    $('#documentForm').formValidation('revalidateField', 'date');
                }
            });

    });
    </script>
    
    <!-- Content table -->
    
    <table class="table">
        <tr>
            <th>Aktion</th>
            <th>Tag</th>
            <th>Lieferant</th>
            <th>Material</th>
            <th>Menge</th>
            <th>Einheit</th>
        </tr>
    
<?php
    if(isset($_GET["project"]) && $_GET["project"] != "") {
        $sql = "SELECT id, project, date, supplier, material, quantity, unit FROM material_tracking WHERE project =".$_GET["project"]." ORDER BY date;";
        $result = $conn->query($sql) or die($conn->error);
        if($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $sql2 = "SELECT firm FROM contacts_firm WHERE id = ".$row["supplier"].";";
                $result2 = $conn->query($sql2);
                $row2 = $result2->fetch_assoc();
                echo "        
                    <tr>
                        <td></td>
                        <td>".$row["date"]."</td>
                        <td>".$row2["firm"]."</td>
                        <td>".$row["material"]."</td>
                        <td>".$row["quantity"]."</td>
                        <td>".$row["unit"]."</td>
                    </tr>";                
            }
        }  else {
            echo "        
                <tr>
                    <td></td>
                    <td>Kein Eintrag verfügbar.</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>";
        }
    } else {
        echo "        
            <tr>
                <td></td>
                <td>Kein Eintrag verfügbar.</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>";
    }
?>
    
    </table>
<?php
    
} else {
    die("Fehler bei der Authentifizierung.");
}

?>