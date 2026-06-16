<?php
header('Content-Type: text/plain');
if (function_exists('opcache_reset')) {
    if (opcache_reset()) {
        echo "OPcache cleared successfully!\n";
    } else {
        echo "Failed to clear OPcache.\n";
    }
} else {
    echo "OPcache is not enabled or function is disabled.\n";
}
?>
