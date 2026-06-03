<?php
$dir = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('modules/Ecommerce/'));
foreach ($dir as $file) {
    if (is_file($file) && pathinfo($file, PATHINFO_EXTENSION) == 'php') {
        $content = file_get_contents($file);
        if (strpos($content, "session('company_id')") !== false) {
            $content = str_replace("session('company_id')", "company_id()", $content);
            file_put_contents($file, $content);
        }
    }
}
echo "Done";
