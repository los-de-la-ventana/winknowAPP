<?php
session_start();
session_destroy();
echo "<script>alert('Sesión cerrada'); window.location.href='login.html';</script>";
?>