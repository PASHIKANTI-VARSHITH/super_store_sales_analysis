<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['username'] === 'admin' && $_POST['password'] === 'admin123') {
        header("Location: adminhome.php");
        exit;
    } else {
        echo "<script>alert('Incorrect incharge ID or password');</script>";
    }
}
?>
