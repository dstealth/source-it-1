<?php
/**
 * Здесь хранятся функции связанные с хранением данных.
 */

/**
 * Save hubs to storage file.
 *
 * @param string $storagePath Storage file path.
 * @param array  $hubs        Hubs.
 * @return bool
 */
function saveHubs($storagePath, $hubs)
{
    if (is_writable($storagePath)) {
        return (bool) file_put_contents($storagePath, json_encode($hubs));
    }
    return false;
}

/**
 * Read hubs from storage file.
 *
 * @param string $storagePath Storage file path.
 * @return array
 */
function readHubs($storagePath)
{
    if (is_readable($storagePath)) {
        $hubsJson = file_get_contents($storagePath);
        if ($hubsJson) {
            return json_decode($hubsJson, true);
        }
    }
    return [];
}