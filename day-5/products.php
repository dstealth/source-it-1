<?php

function getMapping() {
    return [
        1 => 'sku',
        3 => 'product_name',
        4 => 'is_enabled',
        5 => 'is_in_stock',
        6 => 'qty',
        7 => 'price',
        10 => 'color_id'
    ];
}

function readCsvFile($fileName) {
    $lines = file(__DIR__ . '/' . $fileName);
    return $lines;
}

function parseData($lines) {

    array_shift($lines);

    $products = [];
    foreach ($lines as $line) {
        $columns = explode(';', $line);
        $product = mapProduct($columns, getMapping());
        $products[] = $product;
    }

    return $products; 
}

function mapProduct($columns, $mapping) {
    $product = [];
    foreach ($columns as $key => $value) {
        if (isset($mapping[$key + 1])) {
            $attribute = $mapping[$key + 1];
            $product[$attribute] = $value;
        }
    }

    return $product;
}

function writeToStorage($products) {
    
    // извращение :)
    $filesContents = array_map(function($product) {
        $contents = '';
        foreach ($product as $key => $value) {
            $contents .= sprintf("%s:%s\n", $key, $value);
        }
        return $contents;
    }, $products);

    var_dump($filesContents);
 }

$lines = readCsvFile('products.csv');
$products = parseData($lines);

writeToStorage($products);


