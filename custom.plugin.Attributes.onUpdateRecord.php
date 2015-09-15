<?php

// decide whether to archive or unarchive
if (! empty($eventArgs)) {
    
    Core::LoadPlugin('Attributes');
    Core::LoadPlugin('Maps');
    
    $marker = MapController::LoadMapItem($eventArgs->mapitem);
    $tableMetadata = AttributesTable::GetMetadata('markerAttributes');
    $values = AttributesRecord::GetFields($marker->getId(), $marker->getType(), 'sessionDate', $tableMetadata);
    
    $date = strtotime($values['sessionDate']);
    $limit = time() - (30 * 24 * 3600);
    if ($date > $limit) {
        file_put_contents(__DIR__ . DS . '.custom.log', 
            'detect revive (' . $marker->getId() . ': ' . $values['sessionDate'] . ')' . "\n\n", FILE_APPEND);
        Core::Emit('custom.revive', $eventArgs);
    } else {
        
        file_put_contents(__DIR__ . DS . '.custom.log', 
            'detect expire (' . $marker->getId() . ': ' . $values['sessionDate'] . ')' . "\n\n", FILE_APPEND);
        
        Core::Emit('custom.expire', $eventArgs);
    }
}