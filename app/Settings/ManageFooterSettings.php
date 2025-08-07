<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class ManageFooterSettings extends Settings
{

    public static function group(): string
    {
        return 'default';
    }
}