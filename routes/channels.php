<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('public-queue-channel', function () {
    return true;
});
