<?php
session_start();
if (isset($_SESSION['username'])) {
    // Hapus session
    session_unset();
    session_destroy();
} elseif (isset($_SESSION['nip'])) {
    session_unset();
    session_destroy();
}

header("Location: index.php");
exit();
