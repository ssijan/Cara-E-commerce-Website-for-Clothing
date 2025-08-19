
<?php
session_start();
session_unset();  
session_destroy();  
header("Location: home.php?message=" . urlencode("👋 You have been logged out successfully."));
exit();
?>
