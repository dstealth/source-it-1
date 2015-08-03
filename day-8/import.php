<?php

require_once 'bootstrap.php';

if (!isset($conf['habr_url'])) {
    exit('Habr base url was not found in the configuration.');
}
if (!isset($conf['hubs_storage_path'])) {
    exit('Hubs storage path was not found in the configuration.');
}

$hubs = getHubs($conf['habr_url']);
saveHubs($conf['hubs_storage_path'], $hubs);

header('Content-Type: text/html;charset=utf-8');
printf('%d hubs where imported successfully.', count($hubs));