<?php
function getFileListWithTimestamps($dir) {
    $fileList = [];

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($iterator as $fileInfo) {
        if ($fileInfo->isFile()) {
            $fileList[$fileInfo->getRealPath()] = $fileInfo->getMTime();
        }
    }

    return $fileList;
}

function compareFileLists($oldFiles, $newFiles) {
    $newFilesList = array_diff_key($newFiles, $oldFiles);
    $deletedFilesList = array_diff_key($oldFiles, $newFiles);
    $editedFilesList = [];

    foreach ($newFiles as $file => $timestamp) {
        if (isset($oldFiles[$file]) && $oldFiles[$file] != $timestamp) {
            $editedFilesList[] = $file;
        }
    }

    return [
        'newFiles' => $newFilesList,
        'deletedFiles' => $deletedFilesList,
        'editedFiles' => $editedFilesList
    ];
}

$directory = __DIR__;
$currentFiles = getFileListWithTimestamps($directory);

// Path to store the file snapshot
$snapshotFilePath = 'file_snapshot.json';

$response = [
    'newFiles' => null,
    'deletedFiles' => null,
    'editedFiles' => null
];

header('Content-Type: application/json');

// Check if the snapshot file exists and read the previous snapshot
if (file_exists($snapshotFilePath)) {
    $oldFiles = json_decode(file_get_contents($snapshotFilePath), true);

    // Compare the old and current file lists
    $changes = compareFileLists($oldFiles, $currentFiles);

    $response = [
        'newFiles' => array_keys($changes['newFiles']),
        'deletedFiles' => array_keys($changes['deletedFiles']),
        'editedFiles' => $changes['editedFiles']
    ];
}

// Save the current file snapshot to the file
file_put_contents($snapshotFilePath, json_encode($currentFiles));

echo json_encode($response);

?>
