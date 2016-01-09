<?php

setcookie("nickname", "", time() - 3600);
setcookie("password", "", time() - 3600);

echo "Sie sind ausgeloggt.<br />";
echo "Zurück zum <a href=\"index.php?page=login\">Login</a>";

?>