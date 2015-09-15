<?php

/**
 * moves the map feature in to an archive layer (which is known to be layer id, 6)
 * @param int $id unique id for a map feature
 */
function archive_map_feature($id) {
    file_put_contents(__DIR__ . DS . '.custom.log', 'archive' . "\n\n", FILE_APPEND);
    
    Core::Get('Maps');
    $marker = MapController::LoadMapItem($id);
    $marker->setLayerId(6);
    MapController::StoreMapFeature($marker);
}

archive_map_feature($eventArgs->mapitem);