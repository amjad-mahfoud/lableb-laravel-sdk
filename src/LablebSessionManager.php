<?php

namespace Amjad\Lableb;

class LablebSessionManager
{
    const SESSION_KEY = "lableb_session_id";
    const SESSION_TIMEOUT = 2592000;

    /**
     * Generates a random hash and stores it as a cookie then returns it
     * 
     * @return String
     */
    public function getSessionID()
    {
        $sessionID = !empty($_COOKIE[self::SESSION_KEY]) ? $_COOKIE[self::SESSION_KEY] : null;

        if (empty($sessionID)) {
            $sessionID = $userID = md5(uniqid(rand(), true));
            if (function_exists("setcookie") && getenv("PHP_ENV") !== "test") {
                setcookie(
                    self::SESSION_KEY,
                    $sessionID,
                    time() + self::SESSION_TIMEOUT
                );
            }
        }
        return $sessionID;
    }
}
