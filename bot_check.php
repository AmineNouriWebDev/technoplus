<?php
/**
 * Bot Access Diagnostic Tool - V3
 * Analyzes Forbidden 403 responses by capturing body and headers.
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

echo "Comprehensive Diagnostic Access - Detailed Headers/Body\n";
echo str_repeat("=", 50) . "\n\n";

foreach ($urls_to_test as $url_label => $target_url) {
    echo "### TESTING URL: $url_label ($target_url)\n";
    
    foreach ($user_agents as $ua_label => $ua) {
        $ch = curl_init($target_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $ua);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, false); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        
        echo "   - UA: $ua_label -> Status: $http_code ($content_type)\n";
        
        if ($response === false) {
            echo "     CURL Error: " . curl_error($ch) . "\n";
        } else {
            $headers = substr($response, 0, $header_size);
            $body = substr($response, $header_size);
            
            // Look for Server header or Security headers
            if (preg_match('/Server: (.*)/i', $headers, $match)) {
                echo "     Server: " . trim($match[1]) . "\n";
            }
            
            echo "     Body Snippet: " . substr(trim(strip_tags($body)), 0, 150) . "...\n";
            
            // Check for specific security signatures
            if (stripos($body, 'ModSecurity') !== false) echo "     [!] ModSecurity detected in body!\n";
            if (stripos($body, 'Imunify') !== false) echo "     [!] Imunify360 detected in body!\n";
            if (stripos($body, 'Firewall') !== false) echo "     [!] Firewall mention detected!\n";
            if (stripos($body, 'LiteSpeed') !== false) echo "     [!] LiteSpeed mention detected!\n";
        }
        
        curl_close($ch);
    }
    echo "\n" . str_repeat("-", 30) . "\n\n";
}

echo "END OF DIAGNOSTIC\n";
?>
