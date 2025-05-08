<?php

$directory_to_watch = __DIR__;
const TOKEN = 'kwEgnMlpp2jPzPsYfESOfCnfvhpPBEgX5rd5zC1V';

function getFileChecksum($filePath) {
    return md5_file($filePath);
}

function logChange($changeType, $filePath) {
    $change = [
        'date' => date('Y-m-d H:i:s'),
        'change_type' => $changeType,
        'file' => $filePath
    ];

    $ch = curl_init("http://monitoring.loc/webhook/task/" . TOKEN);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($change));
    curl_exec($ch);
    curl_close($ch);
}

function getInitialState($directory) {
    $fileChecksums = [];
    $dirStatus = [];

    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS)
    );

    foreach ($files as $file) {
        $path = $file->getRealPath();

        if ($file->isDir()) {
            $dirStatus[$path] = true;
        } elseif ($file->isFile()) {
            $fileChecksums[$path] = getFileChecksum($path);
        }
    }

    return [$fileChecksums, $dirStatus];
}

function monitorChanges() {
    global $directory_to_watch;

    // Получаем и запоминаем начальное состояние
    [$fileChecksums, $dirStatus] = getInitialState($directory_to_watch);

    while (true) {
        $currentFiles = [];
        $currentDirs = [];

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory_to_watch, RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($files as $file) {
            $path = $file->getRealPath();

            if ($file->isDir()) {
                $currentDirs[$path] = true;
                if (!isset($dirStatus[$path])) {
                    logChange('created', $path);
                }
            } elseif ($file->isFile()) {
                $checksum = getFileChecksum($path);
                $currentFiles[$path] = $checksum;

                if (!isset($fileChecksums[$path])) {
                    logChange('created', $path);
                } elseif ($fileChecksums[$path] !== $checksum) {
                    logChange('modified', $path);
                }
            }
        }

        foreach ($fileChecksums as $path => $oldChecksum) {
            if (!isset($currentFiles[$path])) {
                logChange('deleted', $path);
            }
        }

        foreach ($dirStatus as $path => $_) {
            if (!isset($currentDirs[$path])) {
                logChange('deleted', $path);
            }
        }

        $fileChecksums = $currentFiles;
        $dirStatus = $currentDirs;

        sleep(1);
    }
}

monitorChanges();

?>
