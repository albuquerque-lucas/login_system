<?php

namespace LucasAlbuquerque\LoginSystem\Utils;

class SessionManager
{
  public static function createSession($userName, $userId, $token, $serial)
  {
      if (!isset($_SESSION)) {
          session_start();
      }

      $_SESSION['sessions_username'] = $userName;
      $_SESSION['sessions_id'] = $userId;
      $_SESSION['sessions_token'] = $token;
      $_SESSION['sessions_serial'] = $serial;
  }
}