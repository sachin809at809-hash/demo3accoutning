<?php
$files = glob('modules/Ecommerce/Http/Controllers/*.php');
foreach($files as $file) {
    if(basename($file) != 'BaseController.php') {
        $content = file_get_contents($file);
        $content = str_replace('use App\Abstracts\Http\Controller;', 'use Modules\Ecommerce\Http\Controllers\BaseController as Controller;', $content);
        file_put_contents($file, $content);
    }
}

$files2 = glob('modules/OmniChat/Http/Controllers/*.php');
foreach($files2 as $file) {
    if(basename($file) != 'BaseController.php') {
        $content = file_get_contents($file);
        $content = str_replace('use App\Abstracts\Http\Controller;', 'use Modules\OmniChat\Http\Controllers\BaseController as Controller;', $content);
        file_put_contents($file, $content);
    }
}
echo "Done";
