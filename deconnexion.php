<?php
session_start();
session_destroy();
echo "Vous avez été deconnecté <a href=index.php><button>Revenir</button>";
?>