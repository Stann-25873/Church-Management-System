<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: /');
    exit;
}
// Redirect to dashboard since home.php is now just a redirect
header('Location: /dashboard');
exit;
?>
