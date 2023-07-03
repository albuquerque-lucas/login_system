<?php

namespace LucasAlbuquerque\LoginSystem\Utils;

class CookieManager
{
  public static function createCoockie($userName, $userId, $token, $serial): void
  {
    $expirationTime = time() + (30 * 24 * 60 * 60);
    setcookie('sessions_userid', $userId, time() + $expirationTime, "/");
    setcookie('sessions_username', $userName, time() + $expirationTime, "/");
    setcookie('sessions_token', $token, time() + $expirationTime, "/");
    setcookie('sessions_serial', $serial, time() + $expirationTime, "/");
  }

  public static function deleteCookies(): void
  {
    setcookie('sessions_userid', '', time() - 1, "/");
    setcookie('sessions_username', '', time() - 1, "/");
    setcookie('sessions_token', '', time() - 1, "/");
    setcookie('sessions_serial', '', time() - 1, "/");
    header('Location: /home');
  }
}