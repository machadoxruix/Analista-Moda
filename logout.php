<?php
session_start();
session_destroy();
header('Location: portada0.php');
exit;
?>