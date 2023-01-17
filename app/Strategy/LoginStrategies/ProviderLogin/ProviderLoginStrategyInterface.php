<?php


namespace App\Strategy\LoginStrategies\ProviderLogin;


interface ProviderLoginStrategyInterface
{
    public function redirect($request);
    public function callback($request);
}
