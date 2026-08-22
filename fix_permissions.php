<?php
function fixPermissions($dir) {
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    
    foreach ($iterator as $item) {
        if ($item->isDir()) {
            chmod($item->getPathname(), 0755); // Set folders to 755
        } else {
            chmod($item->getPathname(), 0644); // Set files to 644
        }
    }
}

fixPermissions(__DIR__);
echo "<h1>All file and folder permissions have been fixed!</h1>";
?>
