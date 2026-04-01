<?php
require 'vendor/autoload.php';

use PhpOffice\PhpWord\TemplateProcessor;

// Decode JSON config
$json = file_get_contents('php://input');
$request = json_decode($json, true);

$config = $request['config'] ?? null;

if (!$config || !isset($config['template'], $config['fileName'], $config['data'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid config format.']);
    exit;
}

$templatePath = "../" . $config['template'];
$outputFile = $config['fileName'];
$data = $config['data'];

// Validate template path
if (!file_exists($templatePath)) {
    http_response_code(404);
    echo json_encode(['error' => 'Template not found.']);
    exit;
}

// Directory where temp files are stored
$dir = 'temp/';

// Clear temp directory to avoid clutter
if (is_dir($dir)) {
    $files = glob($dir . '*'); // get all file names in temp folder
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file); // delete file
        }
    }
} else {
    mkdir($dir, 0777, true);
}

// Process template
$template = new TemplateProcessor($templatePath);

foreach ($data as $key => $value) {
    $template->setValue($key, $value);
}

// Save final file
$uniqueName = uniqid() . '.docx';
$finalPath = $dir . $uniqueName;
$template->saveAs($finalPath);

// Return the file path as JSON
echo json_encode(['fileUrl' => "LumaFramework/" . $finalPath]);
exit;
?>
