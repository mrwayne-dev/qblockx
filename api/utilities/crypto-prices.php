<?php
/**
 * Project: crestvalebank
 * Proxy: api/utilities/crypto-prices.php
 *
 * Server-side proxy for CoinCap v2 API.
 * Fetches asset prices and caches for 60 seconds to avoid rate limits.
 * Called by main.js instead of fetching CoinCap directly (avoids CORS).
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Cache for 60 seconds
$cacheFile = sys_get_temp_dir() . '/crestvale_crypto_cache.json';
$cacheTtl  = 60;

if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheTtl) {
    echo file_get_contents($cacheFile);
    exit;
}

$ids = 'bitcoin,ethereum,binance-coin,usd-coin,solana,xrp,tether';
$url = 'https://api.coincap.io/v2/assets?ids=' . $ids;

$ctx = stream_context_create([
    'http' => [
        'method'  => 'GET',
        'header'  => "Accept: application/json\r\nUser-Agent: CrestValeBank/1.0\r\n",
        'timeout' => 5,
    ],
    'ssl' => [
        'verify_peer'      => true,
        'verify_peer_name' => true,
    ],
]);

$response = @file_get_contents($url, false, $ctx);

if ($response === false) {
    // Return cached data if available even if expired, otherwise empty
    if (file_exists($cacheFile)) {
        echo file_get_contents($cacheFile);
    } else {
        echo json_encode(['data' => []]);
    }
    exit;
}

// Validate JSON
$decoded = json_decode($response, true);
if (!$decoded || !isset($decoded['data'])) {
    echo json_encode(['data' => []]);
    exit;
}

// Cache and return
file_put_contents($cacheFile, $response);
echo $response;
