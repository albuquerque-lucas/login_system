<?php

namespace LucasAlbuquerque\LoginSystem\Utils;

class CookieManager
{
  public static function createCoockie($userName, $userId, $token, $serial): void
  {
          setcookie('sessions_userid', $userId, time() + 3600, "/");
          setcookie('sessions_username', $userName, time() + 3600, "/");
          setcookie('sessions_token', $token, time() + 3600, "/");
          setcookie('sessions_serial', $serial, time() + 3600, "/");
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