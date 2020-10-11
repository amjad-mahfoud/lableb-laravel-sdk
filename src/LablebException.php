<?php

namespace Amjad\Lableb;

class LablebException extends \Exception
{
    public function __construct($status = 500, $message = '')
    {
        if (empty($message)) {
            $message = $this->getStatusMsg($status);
        }

        parent::__construct($message);
        $this->status = $status;
    }

    /**
     * A getter for $this->status
     * 
     * @return Number
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Passed an http status number, it returns the propper message for it
     * 
     * @param status - http status number
     * 
     * @return String
     */
    function getStatusMsg($status)
    {
        switch ($status) {
            case 400:
                return 'You have sent bad content';
            case 401:
                return 'You have used incorrect or expired token, please make sure that env variable LABLEB_TOKEN is set';
            case 404:
                return 'Page not found';
            case 502:
                return 'Bad gateway';
            case 503:
                return 'Service not available';
            default:
                return 'Internal server error';
        }
    }
}
