<?php

if(isset($_COOKIE["nickname"]) && isset($_COOKIE["password"]) && login($_COOKIE["nickname"], $_COOKIE["password"]) && permission($_COOKIE["nickname"], "upload")) {

?>

<h1>Upload von Schriftverkehr</h1>
<form class="form-horizontal" id="documentForm" method="POST" action="index.php?page=upload_documents" enctype="multipart/form-data">
  <div class="form-group">
    <label for="datei" class="col-sm-2 control-label">Datei</label>
    <div class="col-sm-10">
      <input type="file" id="datei" name="datei">
    </div>
  </div>
  <div class="form-group">
    <label for="kontakt" class="col-sm-2 control-label">Kontakt</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="kontakt" placeholder="" name="kontakt">
    </div>
  </div>
  <div class="form-group">
    <label for="betreff" class="col-sm-2 control-label">Betreff</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="betreff" placeholder="" name="betreff">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2 control-label">Art</label>
    <div class="col-sm-10">
      <label>
        <input type="radio" name="art" value="Schreiben">
        Schreiben
      </label>
      <label>
        <input type="radio" name="art" value="Rechnung">
        Rechnung
      </label>
      <label>
        <input type="radio" name="art" value="Mahnung">
        Mahnung
      </label>
      <label>
        <input type="radio" name="art" value="Vollstreckungsankündigung">
        Vollstreckungsankündigung
      </label>
      <label>
        <input type="radio" name="art" value="Gutschrift">
        Gutschrift
      </label>
      <label>
        <input type="radio" name="art" value="Lieferschein">
        Lieferschein
      </label>
      <label>
        <input type="radio" name="art" value="Einkaufsbeleg">
        Einkaufsbeleg
      </label>
      <label>
        <input type="radio" name="art" value="Angebot">
        Angebot
      </label>
    </div>
  </div>
  <div class="form-group">
    <label for="erinnerung" class="col-sm-2 control-label">Erinnerung</label>
    <div class="col-sm-10">
      <input type="text" class="form-control datepicker" id="erinnerung" placeholder="" name="erinnerung">
    </div>
  </div>
  <div class="form-group">
    <label for="erledigt" class="col-sm-2 control-label">Erledigt</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="erledigt" placeholder="" name="erledigt">
    </div>
  </div>
  <div class="form-group">
    <label for="datum" class="col-sm-2 control-label">Datum</label>
    <div class="col-sm-10">
      <input type="datetime" class="form-control datepicker" id="datum" placeholder="" name="datum">
    </div>
  </div>
  <div class="form-group">
    <label for="betrag" class="col-sm-2 control-label">Betrag</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="betrag" placeholder="" name="betrag">
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default">Upload</button>
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
$(document).ready(function() {
    $('#documentForm').formValidation({
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
            datei: {
                validators: {
                    notEmpty: {
                    }
                }
            },
            kontakt: {
                validators: {
                    notEmpty: {
                    }
                }
            },
            betreff: {
                validators: {
                    notEmpty: {
                    }
                }
            },
            art: {
                validators: {
                    notEmpty: {
                    }
                }
            }
        }
    });
});
</script>
    
<h1>Upload von Umsätzen</h1>
<form class="form-horizontal" id="accountActivityForm" method="POST" action="index.php?page=upload_account_activity" enctype="multipart/form-data">
    <div class="form-group">
        <label for="datei" class="col-sm-2 control-label">Datei</label>
        <div class="col-sm-10">
          <input type="file" id="datei" name="datei">
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-default">Upload</button>
          <button type="reset" class="btn btn-default">Reset</button>
        </div>
    </div>
</form>

<script> 
$(document).ready(function() {
    $('#accountActivityForm').formValidation({
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
            datei: {
                validators: {
                    notEmpty: {
                    }
                }
            }
        }
    });
});
</script>

<?php
	
} else {
	die("Fehler bei der Authentifizierung.");
}

?>