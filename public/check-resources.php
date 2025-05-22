<?php
header('Content-Type: application/json');

// Helper to convert bytes to MB
function bytesToMB($bytes) {
    return round($bytes / 1024 / 1024, 2);
}

// Helper to convert kB to MB
function kbToMB($kb) {
    return round($kb / 1024, 2);
}

// Get disk space info in MB
$diskTotal = disk_total_space("/");
$diskFree = disk_free_space("/");
$diskUsed = $diskTotal - $diskFree;
$diskUsagePercent = round(($diskUsed / $diskTotal) * 100, 2);

// Get CPU load
$load = sys_getloadavg();

// Get uptime
$uptime = @file_get_contents("/proc/uptime");
$uptime = $uptime ? explode(" ", $uptime)[0] : 0;
$uptimeFormatted = gmdate("H:i:s", (int)$uptime);

// Memory usage (Linux only)
$memInfo = @file_get_contents("/proc/meminfo");
$memTotal = $memAvailable = 0;
if ($memInfo) {
    preg_match('/MemTotal:\s+(\d+)/', $memInfo, $matches);
    $memTotal = isset($matches[1]) ? (int)$matches[1] : 0;

    preg_match('/MemAvailable:\s+(\d+)/', $memInfo, $matches);
    $memAvailable = isset($matches[1]) ? (int)$matches[1] : 0;

    $memUsed = $memTotal - $memAvailable;
    $memUsagePercent = round(($memUsed / $memTotal) * 100, 2);
} else {
    $memUsagePercent = null;
}

// PHP version
$phpVersion = phpversion();

// OS info
$os = php_uname();

// Result array
$data = [
    "disk" => [
        "total_MB" => bytesToMB($diskTotal),
        "used_MB" => bytesToMB($diskUsed),
        "free_MB" => bytesToMB($diskFree),
        "usage_percent" => $diskUsagePercent
    ],
    "cpu" => [
        "load_1min" => $load[0],
        "load_5min" => $load[1],
        "load_15min" => $load[2]
    ],
    "uptime_seconds" => (int)$uptime,
    "uptime_formatted" => $uptimeFormatted,
    "memory" => [
        "total_MB" => kbToMB($memTotal),
        "available_MB" => kbToMB($memAvailable),
        "usage_percent" => $memUsagePercent
    ],
    "php_version" => $phpVersion,
    "os" => $os
];

// Output JSON
echo json_encode($data, JSON_PRETTY_PRINT);
