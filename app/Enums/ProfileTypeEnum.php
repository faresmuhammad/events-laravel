<?php

namespace App\Enums;

enum ProfileTypeEnum: string
{
    case Regular = 'regular';
    case SystemAdmin = 'system_admin';
    case Organization = 'organization';
}
