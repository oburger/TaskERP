<?php
    function login($nickname, $password) {
//        $conn = new mysqli("localhost","root","","taskerp");
//
//        if($conn->connect_error) {
//                die("Connection failed");
//        }
        
        include "mysql.php";

        $sql = "SELECT password FROM users WHERE nickname = '".$nickname."';";

        if(!$conn->query($sql)) {
                throw new Exception($conn->error);
        }

        $result = $conn->query($sql);

        if($result->num_rows == 1) {	
                $row = $result->fetch_assoc();
                $passwort_datenbank = $row['password'];

                if($password == $passwort_datenbank) {
                        return 1;
                } else {
                        return 0;
                }
        } else {
                return 0;
        }

        $conn->close();
    }

    function permission($nickname, $section) {
//        $conn = new mysqli("localhost","root","","taskerp");
//
//        if($conn->connect_error) {
//                die("Connection failed");
//        }
        
        include "mysql.php";

        $sql = "SELECT section FROM permissions WHERE user = '".$nickname."';";

        if(!$conn->query($sql)) {
                throw new Exception($conn->error);
        }

        $result = $conn->query($sql);

        if($result->num_rows >= 1) {	
                while($row = $result->fetch_assoc()) {
                    $section_datenbank = $row['section'];
                    if($section_datenbank == $section) {
                        return 1;
                    }
                }

                if($section == $section_datenbank) {
                        return 1;
                } else {
                        return 0;
                }
        } else {
                return 0;
        }

        $conn->close();
    }
    
    // Funktion zur Hinterlegung der Hintergrundfarbe f�r Ums�tze
    function checkAccountActivity($activity) {
        switch($activity) {
            case "FOLGELASTSCHRIFT":
                return "warning";
            case "EINZUGSSCHECK";
                return "warning";
            case "DAUERAUFTRAG";
                return "warning";
            case "ERSTLASTSCHRIFT":
                return "warning";
            case "ONLINE-UEBERWEISUNG":
                return "warning";
            case "GELDAUTOMAT":
                return "warning";
            case "EIGENE KREDITKARTENABRECHN.":
                return "warning";
            case "LASTSCHRIFT":
                return "warning";
            case "EINZUG RATE/ANNUITAET":
                return "warning";
            case "SONSTIGER EINZUG":
                return "warning";
            case "KARTENZAHLUNG":
                return "warning";
            case "EINZELUEBERWEISUNG":
                return "warning";
            case "ABSCHLUSS":
                return "warning";
            case "BAR KASSE":
                return "warning";
            case "SONST.LASTSCHRIFT":
                return "warning";
            case "ENTGELTABSCHLUSS":
                return "warning";
            case "PROVISION":
                return "warning";
            case "EINMAL LASTSCHRIFT";
                return "warning";
            
            case "GUTSCHRIFT";
                return "success";
            case "LS WIEDERGUTSCHRIFT":
                return "success";
            case "RUECKUEBERWEISUNG":
                return "success";
        }
        return "";
    }

?>