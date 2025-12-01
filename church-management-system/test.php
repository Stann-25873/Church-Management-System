<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    require_once __DIR__ . '/vendor/autoload.php';
    
    echo "Loading models..." . PHP_EOL;
    
    $user = new \Models\User();
    echo "✓ User model loaded" . PHP_EOL;
    
    $event = new \Models\Event();
    echo "✓ Event model loaded" . PHP_EOL;
    
    $offering = new \Models\Offering();
    echo "✓ Offering model loaded" . PHP_EOL;
    
    $sermon = new \Models\Sermon();
    echo "✓ Sermon model loaded" . PHP_EOL;
    
    echo "Testing User methods..." . PHP_EOL;
    echo "countByRole('admin'): " . $user->countByRole('admin') . PHP_EOL;
    echo "getAll() count: " . count($user->getAll()) . PHP_EOL;
    echo "getRecentMembers(5) count: " . count($user->getRecentMembers(5)) . PHP_EOL;
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . PHP_EOL;
    echo $e->getTraceAsString() . PHP_EOL;
}
?>
