<?php
    header("Content-Type: text/html; charset=utf-8");  
    
    // Funktionen laden
    include("functions.php");
    include("mysql.php");
?>
<!DOCTYPE html>
<html lang="de">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>TaskERP</title>

    <!-- Bootstrap -->
    <link href="frameworks/bootstrap-3.3.5-dist/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="frameworks/bootstrap-table-master/src/bootstrap-table.css">
    
    <!-- Formvalidator -->
    <link rel="stylesheet" href="frameworks/formvalidation/dist/css/formValidation.css">
    
    <!-- Eigene Styles -->
    <link href="style/style_display.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]--> 

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="frameworks/jquery-2.1.4.js"></script>
    
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="frameworks/bootstrap-3.3.5-dist/js/bootstrap.js"></script>
    
    <script src="frameworks/bootstrap-table-master/src/bootstrap-table.js"></script>
    <script src="frameworks/bootstrap-table-master/src/locale/bootstrap-table-de-DE.js"></script>
    
    <!-- FormValidation plugin and the class supports validating Bootstrap form -->
    <script src="frameworks/formvalidation/dist/js/formValidation.js"></script>
    <script src="frameworks/formvalidation/dist/js/framework/bootstrap.js"></script>    
    <script src="frameworks/formvalidation/dist/js/language/de_DE.js"></script>
    
    <link href="frameworks/jquery-ui-1.11.4/jquery-ui.css" rel="stylesheet">
    <script src="frameworks/jquery-ui-1.11.4/jquery-ui.js"></script>
    
    <!-- Bootstrap Multiselect -->
    
    <script type="text/javascript" src="frameworks/bootstrap-multiselect/bootstrap-multiselect.js"></script>
    <link rel="stylesheet" href="frameworks/bootstrap-multiselect/bootstrap-multiselect.css" type="text/css"/>
    
  </head>
  <body>
      
    <div class="container">
    	<h1>TaskERP - Pre-Alpha</h1>
        
        <!-- Navigation -->
    <ul class="nav nav-pills">
<?php
        // Reiter werden benutzerspezifisch nach Rechten und Login eingeblendet
        // Authentifizierung
        if(isset($_COOKIE["nickname"]) && isset($_COOKIE["password"]) && login($_COOKIE["nickname"], $_COOKIE["password"])) {
            
            echo "<li role=\"presentation\"><a href=\"index.php\">Dashboard</a></li>";

            if(permission($_COOKIE["nickname"], "documents")) {
                echo "<li role=\"presentation\"><a href=\"index.php?page=documents\">Dokumente</a></li>";
            }
            if(permission($_COOKIE["nickname"], "account_activity")) {
                echo "<li role=\"presentation\"><a href=\"index.php?page=account_activity\">Umsätze</a></li>";
            }
            if(permission($_COOKIE["nickname"], "upload")) {
                echo "<li role=\"presentation\"><a href=\"index.php?page=upload\">Upload</a></li>";
            }
            if(permission($_COOKIE["nickname"], "projects")) {
                echo "<li role=\"presentation\"><a href=\"index.php?page=projects\">Projekte</a></li>";
            }
            if(permission($_COOKIE["nickname"], "time_recording")) {
                echo "<li role=\"presentation\"><a href=\"index.php?page=time_recording\">Zeiterfassung</a></li>";
            }            
            if(permission($_COOKIE["nickname"], "material_tracking")) {
                echo "<li role=\"presentation\"><a href=\"index.php?page=material_tracking\">Materialerfassung</a></li>";
            }
            
            echo "<li role=\"presentation\"><a href=\"index.php?page=#\">Akten</a></li>";
            echo "<li role=\"presentation\"><a href=\"index.php?page=#\">Tasks</a></li>";
            echo "<li role=\"presentation\"><a href=\"index.php?page=logout\">Ausloggen</a></li>";
        }
?>
    </ul>
    <br />
		<?php
                // Ladefunktionalität		
			
                        // Laden der über GET mitgeteilten Seiten
			if(isset($_GET["page"])) {
                            switch($_GET["page"]) {
                                case "login":
                                    include("login.php");
                                    break;
                                case "logout":
                                    include("logout.php");
                                    break;
                                case "documents":
                                    include("documents.php");
                                    break;
                                case "upload":
                                    include("upload.php");
                                    break;
                                case "upload_documents":
                                    include("upload_documents.php");
                                    break;
                                case "upload_account_activity":
                                    include("upload_account_activity.php");
                                    break;
                                case "account_activity":
                                    include("account_activity.php");
                                    break;
                                case "select_acc_act":
                                    include("select.php");
                                    break;
                                case "select_doc":
                                    include("select.php");
                                    break;
                                case "link";
                                    include("link.php");
                                    break;
                                case "link_acc_act":
                                    include("account_activity.php");
                                    break;
                                case "link_doc":
                                    include("documents.php");
                                    break;
                                case "link_pro":
                                    include("projects.php");
                                    break;
                                case "projects":
                                    include("projects.php");
                                    break;
                                case "projects_add":
                                    include("projects.php");
                                    break;
                                case "projects_detail":
                                    include("projects_detail.php");
                                    break;
                                case "time_recording":
                                    include("time_recording.php");
                                    break;
                                case "material_tracking":
                                    include("material_tracking.php");
                                    break;
                                case "material_tracking_add":
                                    include("material_tracking.php");
                                    break;
                            }
			} else {
                                // Wird keine Seite gewählt, so landet man auf der Ursprungsseite Login.
                                // Diese übernimmt den Loginvorgang und zeigt den Login-Text.
				include("login.php");
			}
			
		?>
    </div>
  </body>
</html>