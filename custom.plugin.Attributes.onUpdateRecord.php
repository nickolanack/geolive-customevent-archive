<?php

// decide whether to archive or unarchive
if (! empty($eventArgs)) {
    
    Core::LoadPlugin('Attributes');
    Core::LoadPlugin('Maps');
    
    $marker = MapController::LoadMapItem($eventArgs->mapitem);
    $tableMetadata = AttributesTable::GetMetadata('markerAttributes');
    $values = AttributesRecord::GetFields($marker->getId(), $marker->getType(), 'sessionDate', $tableMetadata);
    

    $sessionDate=$values['sessionDate'];

    $date = strtotime($sessionDate);
    $limit = time() - (30 * 24 * 3600);


    // Unarchive items without data, or within date range

    if (empty($sessionDate) || $date > $limit) {
        file_put_contents(__DIR__ . DS . '.custom.log', 
            'detect revive (' . $marker->getId() . ': ' . $sessionDate . ')' . "\n\n", FILE_APPEND);
        Core::Emit('custom.revive', $eventArgs);
    } else {
        
        file_put_contents(__DIR__ . DS . '.custom.log', 
            'detect expire (' . $marker->getId() . ': ' . $sessionDate . ')' . "\n\n", FILE_APPEND);
        
        Core::Emit('custom.expire', $eventArgs);
    }
}