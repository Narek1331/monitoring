<?php

$directory_to_watch = __DIR__;
const TOKEN = 'cWun1cMtYPIJlUHtd2b7pf899bwLBVgug2RmML2P';
function getFileChecksum($filePath) {
    return md5_file($filePath);
}

function logChange($changeType, $filePath, &$logData) {
    // global $log_file;

    $change = array(
        'date' => date('Y-m-d H:i:s'),
        'change_type' => $changeType,
        'file' => $filePath
    );

    $logData[] = $change;

    $ch = curl_init("https://iqm-tools.ru/webhook/task/" . TOKEN);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($change));
    $response = curl_exec($ch);
    curl_close($ch);

    // file_put_contents($log_file, json_encode($logData, JSON_PRETTY_PRINT));
}

function monitorChanges() {
    global $directory_to_watch, $log_file;

    $fileChecksums = array();
    $dirStatus = array();
    $logData = array();

    if (file_exists($log_file)) {
        $contents = file_get_contents($log_file);
        $logData = json_decode($contents, true);
        if (!is_array($logData)) {
            $logData = array();
        }
    }

    while (true) {
        $currentFiles = array();
        $currentDirs = array();

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory_to_watch, RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($files as $file) {
            $filePath = $file->getRealPath();

            if ($file->isDir()) {
                $currentDirs[$filePath] = true;

                if (!isset($dirStatus[$filePath])) {
                    logChange('created', $filePath, $logData);
                }
            } elseif ($file->isFile()) {
                $checksum = getFileChecksum($filePath);
                $currentFiles[$filePath] = $checksum;

                if (!isset($fileChecksums[$filePath])) {
                    logChange('created', $filePath, $logData);
                } elseif ($fileChecksums[$filePath] !== $checksum) {
                    logChange('modified', $filePath, $logData);
                }
            }
        }

        foreach ($fileChecksums as $filePath => $checksum) {
            if (!isset($currentFiles[$filePath])) {
                logChange('deleted', $filePath, $logData);
            }
        }

        foreach ($dirStatus as $dirPath => $status) {
            if (!isset($currentDirs[$dirPath])) {
                logChange('deleted', $dirPath, $logData);
            }
        }

        $fileChecksums = $currentFiles;
        $dirStatus = $currentDirs;

        sleep(10);
    }
}

monitorChanges();

?>
