<?php
/**
 * Bot Access Diagnostic Tool
 * This script tests the HTTP status code of a specific URL on the same server
 * using different User-Agents and simulating a clean session.
 */

header('Content-Type: text/plain');

$urls_to_test = [
    'Product URL' => "https://technoplus.io/produit/infinix-hot-60/",
    'Product URL (no SSL)' => "http://technoplus.io/produit/infinix-hot-60/",
    'Home Page' => "https://technoplus.io/",
    'Robots.txt' => "https://technoplus.io/robots.txt",
    'Simple PHP Test' => "https://technoplus.io/test_200.php",
    'Direct PHP File' => "https://technoplus.io/index.php"
];

$user_agents = [
    'Googlebot' => 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
    'Normal Browser' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36',
];

echo "Comprehensive Diagnostic Access\n";
echo str_repeat("=", 50) . "\n\n";

foreach ($urls_to_test as $url_label => $target_url) {
    echo "### TESTING URL: $url_label ($target_url)\n";
    
    foreach ($user_agents as $ua_label => $ua) {
        $ch = curl_init($target_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $ua);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); // Don't follow to see the first response
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $redirect_url = curl_getinfo($ch, CURLINFO_REDIRECT_URL);
        $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        
        echo "   - UA: $ua_label -> Status: $http_code ($content_type)";
        if ($redirect_url) echo " | Redirect to: $redirect_url";
        echo "\n";
        
        curl_close($ch);
    }
    echo "\n";
}

echo "END OF DIAGNOSTIC\n";
?>
