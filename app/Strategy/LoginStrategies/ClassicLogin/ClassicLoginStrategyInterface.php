<?php


namespace App\Strategy\LoginStrategies\ClassicLogin;


interface ClassicLoginStrategyInterface
{
    public function checkInput($request);
    public function loginAttempt($request);
}
