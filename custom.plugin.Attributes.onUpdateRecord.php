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

/**
 * move the map feature into an active layer using a icon set map to choose the layer id.
 *
 * @param int $id
 *            unique id for a map feature
 */
function unarchive_map_feature($id) {
    file_put_contents(__DIR__ . DS . '.custom.log', 'unarchive' . "\n\n", FILE_APPEND);
    
    $prefix = 'components/com_geolive/users_files/user_files_983/Uploads/'; // not used
    
    $iconMap = array(
        '[ImAgE]_JYp_[G]_rP7_SHq.png' => 1,
        'tEk_[G]_[ImAgE]_L7_xIy.png' => 3,
        '[G]_aAJ_hLP_RDn_[ImAgE].png' => 4,
        'ivy_[ImAgE]_lwU_[G]_VVF.png' => 2
    );
    
    Core::Get('Maps');
    $marker = MapController::LoadMapItem($id);
    $iconUrl = $marker->getIcon();
    
    $icon = substr($iconUrl, strrpos($iconUrl, '/') + 1);
    if (key_exists($icon, $iconMap)) {
        $marker->setLayerId($iconMap[$icon]);
        MapController::StoreMapFeature($marker);
    }
    
    file_put_contents(__DIR__ . DS . '.custom.log', $icon . "\n\n", FILE_APPEND);
}

// decide whether to archive or unarchive

if (! empty($eventArgs) && key_exists('sessionDate', $eventArgs)) {
    $date = strtotime($eventArgs->sessionDate);
    $limit = time() - (30 * 24 * 3600);
    if ($date > $limit) {
        unarchive_map_feature($eventArgs->mapitem);
    } else {
        archive_map_feature($eventArgs->mapitem);
    }
    
    file_put_contents(__DIR__ . DS . '.custom.log', 
        print_r(
            array(
                $eventArgs->sessionDate,
                'timestamp:' . $date,
                'limit:' . $limit
            ), true) . "\n\n", FILE_APPEND);
}