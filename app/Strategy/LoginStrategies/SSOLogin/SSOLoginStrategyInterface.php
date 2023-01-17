<?php


namespace App\Strategy\LoginStrategies\SSOLogin;


interface SSOLoginStrategyInterface
{
    public function checkInput($request);
    public function SendCode($request);
    public function loginAttempt($request);
}
