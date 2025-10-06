<?php

$ppRoadsPath = 'ArchRoads.json';

$ppRoads = file_get_contents($ppRoadsPath);
$ppRoads = json_decode($ppRoads, true);

$archInPath = 'roadarch.json';

$archIn = file_get_contents($archInPath);
$archIn = json_decode($archIn, true);


$road = $archIn['data']['roads'][0];

$archOutPath = 'roadarch_out.json';
$archOut = $archIn;

for ($i = 0; $i < count($ppRoads); $i++) {
    $ppRoad = $ppRoads[$i];

    $newRoad = $road;
    $newRoad['displayName'] = fixName($ppRoad['DisplayName']);
    $newRoad['name'] = $ppRoad['Name'];

    $newNode = $newRoad['nodes'][0];

    $newRoad['nodes'] = [];

    foreach ($ppRoad['Nodes'] as $ppNode) {
        $node = $newNode;
        $node['posX'] = $ppNode['PosX'] - 1024;
        $node['posY'] = $ppNode['PosY'] - 1024;
        $node['posZ'] = $ppNode['PosZ'];
        $newRoad['nodes'][] = $node;
    }

    $archOut['data']['roads'][] = $newRoad;
}

file_put_contents($archOutPath, json_encode($archOut, JSON_PRETTY_PRINT));


function fixName($name)
{

    $replace = [
        '\u00e3' => 'ã'
    ];

    foreach ($replace as $key => $val) {
        $name = str_replace($key, $val, $name);
    }

    return $name;
}