<?php

// decide whether to archive or unarchive
if (! empty($eventArgs) && key_exists('sessionDate', $eventArgs)) {
    $date = strtotime($eventArgs->sessionDate);
    $limit = time() - (30 * 24 * 3600);
    if ($date > $limit) {
        Core::Emit('custom.revive', $eventArgs);
    } else {
        Core::Emit('custom.expire', $eventArgs);
    }
    
    file_put_contents(__DIR__ . DS . '.custom.log', 
        print_r(
            array(
                $eventArgs->sessionDate,
                'timestamp:' . $date,
                'limit:' . $limit
            ), true) . "\n\n", FILE_APPEND);
}