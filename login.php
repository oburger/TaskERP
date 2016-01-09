<?php

if(isset($_COOKIE["nickname"]) && isset($_COOKIE["password"])) {
	$nickname = htmlspecialchars($_COOKIE["nickname"]);
	$password = htmlspecialchars($_COOKIE["password"]);
	
//	$conn = new mysqli("localhost","root","","taskerp");
//
//	if($conn->connect_error) {
//		die("Connection failed");
//	}
	
	$sql = "SELECT password FROM users WHERE nickname = '".$nickname."';";
	
	if(!$conn->query($sql)) {
		throw new Exception($conn->error);
	}
	
	$result = $conn->query($sql);
	
	if($result->num_rows == 1) {	
		while($row = $result->fetch_assoc()) {
			$passwort_datenbank = $row['password'];
		}
		
		if($password == $passwort_datenbank) {
			echo "Authentifiziert über COOKIE.";
		} else {
			echo "Benutzername und Passwort sind nicht korrekt. Bitte versuchen Sie es erneut.<br />";
			echo "<a href=\"index.php\">Zurück</a>";
		}
	} else {
		echo "Benutzername und Passwort sind nicht korrekt. Bitte versuchen Sie es erneut.<br />";
		echo "<a href=\"index.php\">Zurück</a>";
	}
	
	$conn->close();
	
} else {

	if(isset($_POST["nickname"]) && isset($_POST["password"])) {
		
		$nickname = htmlspecialchars($_POST["nickname"]);
		$password = htmlspecialchars($_POST["password"]);
		
		$conn = new mysqli("localhost","root","","taskerp");
	
		if($conn->connect_error) {
			die("Connection failed");
		}
		
		$sql = "SELECT password FROM users WHERE nickname = '".$nickname."';";
		
		if(!$conn->query($sql)) {
			throw new Exception($conn->error);
		}
		
		$result = $conn->query($sql);
		
		if($result->num_rows == 1) {	
			while($row = $result->fetch_assoc()) {
				$passwort_datenbank = $row['password'];
			}
			
			if(hash('sha512', $password) == $passwort_datenbank) {
				setcookie("nickname",$nickname,time()+60*60*24);
				setcookie("password",$passwort_datenbank,time()+60*60*24);
				
				echo "Herzlich willkommen ".$nickname.". Bitte folge dem Link: <a href=\"index.php\">Start</a>";
                                echo "<script language=\"javascript\" type=\"text/javascript\">
                                        <!--
                                        window.setTimeout('window.location = \"index.php\"',2000);
                                        // –>
                                    </script>";
			} else {
				echo "Benutzername und Passwort sind nicht korrekt. Bitte versuchen Sie es erneut.<br />";
				echo "<a href=\"index.php\">Zurück</a>";
			}
		} else {
			echo "Benutzername und Passwort sind nicht korrekt. Bitte versuchen Sie es erneut.<br />";
			echo "<a href=\"index.php\">Zurück</a>";
		}
		
		$conn->close();
	
	} else { 
	
	?>
	
		<form class="form-inline" method="post" action="<?php $PHP_SELF; ?>">
		  <div class="form-group">
			<label class="sr-only" for="exampleInputEmail3">Email address</label>
			<input name="nickname" type="name" class="form-control" id="exampleInputEmail3" placeholder="Benutername">
		  </div>
		  <div class="form-group">
			<label class="sr-only" for="exampleInputPassword3">Password</label>
			<input name="password" type="password" class="form-control" id="exampleInputPassword3" placeholder="">
		  </div>
		  <button type="submit" class="btn btn-default">Sign in</button>
		</form>
	
	<?php
	
	}

}

?>