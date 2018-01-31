<?php

namespace WorldText;


/*
 * Extend Exception, adding status and desc fields...
 */

use Exception;

class wtException extends Exception
{

    protected $status;
    protected $desc;
    protected $error;

    /**
     * wtException constructor.
     * @param $message
     * @param $json
     * @param int $code
     */
    public function __construct($message, $json, $code = 0)
    {
        try {
            $data = json_decode($json, true);
            $this->desc = $data['desc'];
            $this->error = $data['error'];
        } catch (Exception $ex) {

        }
        parent::__construct($message, $code);
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getDesc()
    {
        return $this->desc;
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

}
