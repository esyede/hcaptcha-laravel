<?php

namespace Esyede\Laravel\HCaptcha\Facades;

use Illuminate\Support\Facades\Facade;

class HCaptcha extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'hcaptcha';
    }
}
