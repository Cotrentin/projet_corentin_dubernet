<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
ob_start();
?>








<?php $content = ob_get_clean(); ?>
<?php require('../TP_9/model/pdo.php'); ?>