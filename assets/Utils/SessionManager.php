<?php

namespace LucasAlbuquerque\LoginSystem\Utils;

use LucasAlbuquerque\LoginSystem\Model\Session;
use LucasAlbuquerque\LoginSystem\Model\User;

class SessionManager
{
    public static function verifySessionState(): bool
    {
        if(!isset($_SESSION)){
            session_start();
        }
        if(isset($_COOKIE['sessions_userid']) && isset($_COOKIE['sessions_token']) && isset($_COOKIE['sessions_serial'])){
            
            $id = $_COOKIE['sessions_userid'];
            $token = $_COOKIE['sessions_token'];
            $serial = $_COOKIE['sessions_serial'];
            $newSession = new Session();
            $session = $newSession->findSession($id, $token, $serial);
            if ($session['sessions_id'] > 0) {
                if($session['sessions_userid'] == $_COOKIE['sessions_userid']
                && $session['sessions_token'] == $_COOKIE['sessions_token']
                && $session['sessions_serial'] == $_COOKIE['sessions_serial']) {
                        if($session['sessions_userid'] == $_SESSION['sessions_id']
                        && $session['sessions_token'] == $_SESSION['sessions_token']
                        && $session['sessions_serial'] == $_SESSION['sessions_serial']) {
                            return true;
                        } else{
                            self::createSession(
                                $_COOKIE['user_username'],
                                $id,
                                $token,
                                $serial
                            );
                            return true;
                        }
                } else {
                    return false;
                }
            } else {
                return false;
            }
            
        }
        return false;
    }

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

    public static function verifySessionInformation()
    {
        $newSession = new Session();
        $newUser = new User();
        $result = $newSession->getAll();
        $status = self::verifySessionState();
        if(!$result){
            return false;
        } else {
            $userId = $result['sessions_userid'];
            $user = $newUser->findUserSession($userId);
            $userAccess = $newUser->getUserAccess($userId);
        }
        return [$status, $user, $userAccess];
    }
}