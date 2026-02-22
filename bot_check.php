<?php
/**
 * Bot Access Diagnostic Tool
 * This script tests the HTTP status code of a specific URL on the same server
 * using different User-Agents and simulating a clean session.
 */

header('Content-Type: text/plain');

$target_url = "https://technoplus.io/produit/infinix-hot-60/";
$user_agents = [
    'Normal Browser' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36',
    'Googlebot' => 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
    'Schema Validator' => 'Mozilla/5.0 (compatible; Schema.org Validator; +http://validator.schema.org)',
    'Empty/Bot' => 'Wget/1.21.1',
];

echo "Diagnostic Access for: $target_url\n";
echo str_repeat("=", 50) . "\n\n";

foreach ($user_agents as $label => $ua) {
    echo "Testing User-Agent: $label\n";
    echo "UA String: $ua\n";
    
    $ch = curl_init($target_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, $ua);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true); // We only want headers
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $redirect_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    
    if (curl_errno($ch)) {
        echo "CURL Error: " . curl_error($ch) . "\n";
    } else {
        echo "HTTP Status Code: $http_code\n";
        if ($redirect_url != $target_url) {
            echo "Final URL after redirects: $redirect_url\n";
        }
        
        // Print first line of headers
        $header_lines = explode("\n", $response);
        echo "Header: " . trim($header_lines[0]) . "\n";
    }
    
    curl_close($ch);
    echo "\n" . str_repeat("-", 30) . "\n\n";
}

echo "END OF DIAGNOSTIC\n";
?>
