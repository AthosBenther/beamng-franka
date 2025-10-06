<?php

$oPath = '.\roadarch_out.json';
$ePath = '.\roadarch_out2.json';

$dPath = 'roadarch_out_diff.json';

$orig = file_get_contents($oPath);
$orig = json_decode($orig, true);

$edited = file_get_contents($ePath);
$edited = json_decode($edited, true);

$diff = $orig;
$diff['data']['roads'] = [];

foreach ($edited['data']['roads'] as $eRoad) {
    $oRoad = null;

    if (!in_array($eRoad['name'], array_column($orig['data']['roads'], 'name'))) {
        $diff['data']['roads'][] = $eRoad;
    } else {

        foreach ($orig['data']['roads'] as $oRoad) {
            if ($oRoad['name'] === $eRoad['name']) {
                if (count($oRoad['nodes']) !== count($eRoad['nodes'])) {
                    $diff['data']['roads'][] = $eRoad;
                    break;
                }

                for ($i = 0; $i < count($oRoad['nodes']); $i++) {
                    $oNode = $oRoad['nodes'][$i];
                    $eNode = $eRoad['nodes'][$i];

                    $oX = substr("" . $oNode['posX'], 0, 8);
                    $eX = substr("" . $eNode['posX'], 0, 8);

                    $oY = substr("" . $oNode['posY'], 0, 8);
                    $eY = substr("" . $eNode['posY'], 0, 8);

                    $oZ = substr("" . $oNode['posZ'], 0, 8);
                    $eZ = substr("" . $eNode['posZ'], 0, 8);

                    if ($oX !== $eX || $oY !== $eY || $oZ !== $eZ) {
                        $diff['data']['roads'][] = $eRoad;
                        break;
                    }
                }
            }
        }
    }
}

echo 'Found ' . count($diff['data']['roads']) . " out of " . count($orig['data']['roads']) . "\n";

file_put_contents($dPath, json_encode($diff, JSON_PRETTY_PRINT));