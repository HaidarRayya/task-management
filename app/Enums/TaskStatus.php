<?php

namespace App\Enums;

enum TaskStatus: string
{
    case PINDING  = 'pinding';
    case APPOINTED = 'appointed';
    case STARTED = 'started';
    case ENDED = 'ended';
    case FALIED = 'falied';
}