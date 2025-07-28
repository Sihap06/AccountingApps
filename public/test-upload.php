<?php
// Test Upload Configuration

echo "<h2>PHP Upload Configuration Test</h2>";

// Check basic settings
$settings = [
    'file_uploads' => ini_get('file_uploads'),
    'upload_max_filesize' => ini_get('upload_max_filesize'),
    'post_max_size' => ini_get('post_max_size'),
    'max_execution_time' => ini_get('max_execution_time'),
    'max_input_time' => ini_get('max_input_time'),
    'memory_limit' => ini_get('memory_limit'),
    'max_file_uploads' => ini_get('max_file_uploads'),
];

echo "<h3>Current Settings:</h3>";
echo "<table border='1' cellpadding='5'>";
foreach ($settings as $key => $value) {
    echo "<tr><td>$key</td><td><strong>$value</strong></td></tr>";
}
echo "</table>";

// Convert to bytes for comparison
function convertToBytes($value) {
    $value = trim($value);
    $last = strtolower($value[strlen($value)-1]);
    $value = (int)$value;
    switch($last) {
        case 'g': $value *= 1024;
        case 'm': $value *= 1024;
        case 'k': $value *= 1024;
    }
    return $value;
}

$uploadMax = convertToBytes(ini_get('upload_max_filesize'));
$postMax = convertToBytes(ini_get('post_max_size'));

echo "<h3>Analysis:</h3>";
echo "<ul>";
echo "<li>Max upload size in bytes: " . number_format($uploadMax) . " (" . round($uploadMax / 1024 / 1024, 2) . " MB)</li>";
echo "<li>Max POST size in bytes: " . number_format($postMax) . " (" . round($postMax / 1024 / 1024, 2) . " MB)</li>";
echo "</ul>";

// Test file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test_file'])) {
    echo "<h3>Upload Test Result:</h3>";
    echo "<pre>";
    print_r($_FILES['test_file']);
    echo "</pre>";
    
    if ($_FILES['test_file']['error'] > 0) {
        $errors = [
            UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize',
            UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE in form',
            UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION => 'File upload stopped by extension',
        ];
        echo "<p style='color: red;'>Error: " . ($errors[$_FILES['test_file']['error']] ?? 'Unknown error') . "</p>";
    } else {
        echo "<p style='color: green;'>File uploaded successfully!</p>";
        echo "<p>File size: " . number_format($_FILES['test_file']['size']) . " bytes (" . round($_FILES['test_file']['size'] / 1024 / 1024, 2) . " MB)</p>";
    }
}

// Check temp directory
$tempDir = ini_get('upload_tmp_dir') ?: sys_get_temp_dir();
echo "<h3>Temp Directory:</h3>";
echo "<p>Path: $tempDir</p>";
echo "<p>Writable: " . (is_writable($tempDir) ? 'Yes' : 'No') . "</p>";

?>

<h3>Test File Upload:</h3>
<form method="POST" enctype="multipart/form-data">
    <input type="file" name="test_file" />
    <input type="submit" value="Test Upload" />
</form>

<p style="margin-top: 20px;"><strong>Note: Delete this file after testing!</strong></p>