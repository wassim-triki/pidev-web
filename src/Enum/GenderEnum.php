<?php

namespace App\Enum;

enum GenderEnum: string
{
    case MALE = 'Male';
    case FEMALE = 'Female';
    case PREFER_NOT_TO_SAY = 'PREFER_NOT_TO_SAY';
}
