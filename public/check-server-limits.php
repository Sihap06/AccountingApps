<?php
// Check server limits and potential issues

echo "<h2>Server Upload Limits Checker</h2>";

// Check PHP settings
echo "<h3>PHP Configuration:</h3>";
$phpSettings = [
    'file_uploads' => ini_get('file_uploads'),
    'upload_max_filesize' => ini_get('upload_max_filesize'),
    'post_max_size' => ini_get('post_max_size'),
    'max_execution_time' => ini_get('max_execution_time'),
    'max_input_time' => ini_get('max_input_time'),
    'memory_limit' => ini_get('memory_limit'),
    'max_file_uploads' => ini_get('max_file_uploads'),
    'upload_tmp_dir' => ini_get('upload_tmp_dir') ?: sys_get_temp_dir(),
];

echo "<table border='1' cellpadding='5'>";
foreach ($phpSettings as $key => $value) {
    echo "<tr><td>$key</td><td><strong>$value</strong></td></tr>";
}
echo "</table>";

// Check server software
echo "<h3>Server Information:</h3>";
echo "<p>Server Software: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "</p>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>SAPI: " . php_sapi_name() . "</p>";

// Check if running behind proxy or CDN
echo "<h3>Request Headers (might indicate proxy/CDN limits):</h3>";
echo "<pre>";
foreach (getallheaders() as $name => $value) {
    if (stripos($name, 'cloudflare') !== false || stripos($name, 'cf-') !== false) {
        echo "$name: $value\n";
    }
}
echo "</pre>";

// Check for ModSecurity
echo "<h3>Security Modules:</h3>";
if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    echo "<p>Apache Modules: ";
    if (in_array('mod_security', $modules) || in_array('mod_security2', $modules)) {
        echo "<span style='color: red;'>ModSecurity detected - may limit uploads!</span>";
    } else {
        echo "ModSecurity not detected";
    }
    echo "</p>";
}

// Check actual limits by testing
echo "<h3>Actual Upload Test:</h3>";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h4>POST Data:</h4>";
    echo "<p>Content-Length Header: " . ($_SERVER['CONTENT_LENGTH'] ?? 'Not set') . " bytes</p>";
    echo "<p>POST data received: " . (empty($_POST) ? 'No' : 'Yes') . "</p>";
    echo "<p>FILES data received: " . (empty($_FILES) ? 'No' : 'Yes') . "</p>";
    
    if (!empty($_FILES['test_file'])) {
        $file = $_FILES['test_file'];
        echo "<h4>File Upload Result:</h4>";
        echo "<pre>";
        print_r($file);
        echo "</pre>";
        
        if ($file['error'] === UPLOAD_ERR_OK) {
            echo "<p style='color: green;'>✓ File uploaded successfully!</p>";
            echo "<p>File size: " . number_format($file['size']) . " bytes (" . round($file['size'] / 1024 / 1024, 2) . " MB)</p>";
        } else {
            $errors = [
                UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize in php.ini',
                UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE in HTML form',
                UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
                UPLOAD_ERR_NO_FILE => 'No file was uploaded',
                UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
                UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                UPLOAD_ERR_EXTENSION => 'File upload stopped by extension',
            ];
            echo "<p style='color: red;'>✗ Error: " . ($errors[$file['error']] ?? 'Unknown error code: ' . $file['error']) . "</p>";
        }
    } else {
        echo "<p style='color: orange;'>No file data received. This might indicate:</p>";
        echo "<ul>";
        echo "<li>Web server rejected the request before reaching PHP</li>";
        echo "<li>Proxy/CDN limit exceeded (e.g., Cloudflare free plan has 100MB limit)</li>";
        echo "<li>ModSecurity or WAF blocking the upload</li>";
        echo "<li>Web server body size limit (LimitRequestBody in Apache, client_max_body_size in Nginx)</li>";
        echo "</ul>";
    }
}

// Laravel specific checks
echo "<h3>Laravel Configuration:</h3>";
if (file_exists('../bootstrap/app.php')) {
    echo "<p style='color: green;'>✓ Laravel installation detected</p>";
    
    // Check if Livewire temp upload directory is writable
    $livewireTempPath = '../storage/app/livewire-tmp';
    if (is_dir($livewireTempPath)) {
        echo "<p>Livewire temp directory: " . (is_writable($livewireTempPath) ? '✓ Writable' : '✗ Not writable') . "</p>";
    }
}

?>

<h3>Test Different File Sizes:</h3>
<form method="POST" enctype="multipart/form-data">
    <p>
        <label>Select a file to test:</label><br>
        <input type="file" name="test_file" accept="image/*" />
    </p>
    <p>
        <input type="submit" value="Test Upload" />
    </p>
</form>

<div style="margin-top: 20px; padding: 10px; background-color: #fffbeb; border: 1px solid #fbbf24;">
    <strong>Common Issues:</strong>
    <ul>
        <li><strong>1MB limit usually indicates:</strong> Web server configuration (not PHP)</li>
        <li><strong>Apache:</strong> Check LimitRequestBody directive</li>
        <li><strong>Nginx:</strong> Check client_max_body_size directive</li>
        <li><strong>Cloudflare:</strong> Free plan limits uploads to 100MB</li>
        <li><strong>Shared Hosting:</strong> Often has strict limits that can't be changed</li>
    </ul>
</div>

<p style="margin-top: 20px;"><strong>⚠️ Delete this file after testing!</strong></p>