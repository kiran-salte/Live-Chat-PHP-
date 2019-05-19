<?php
session_start();
session_destroy();
unset($_COOKIE['id']);
unset($_COOKIE['name']);
header('Location: ../index.php');