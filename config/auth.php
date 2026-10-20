<?php

session_start();
function admin_required()
{
    if (!isset($_SESSION['admin_id'])) {
        header("Location: login.php");
        exit;
    }
}

?>