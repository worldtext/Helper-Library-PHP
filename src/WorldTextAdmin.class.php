<?php

namespace WorldText;


/**
 * Class WorldTextAdmin
 * @package WorldText
 */
class WorldTextAdmin extends WorldText
{

    /**
     * WorldTextAdmin constructor.
     * @param $id World Text Account ID
     * @param $apiKey API Key
     */
    public function __construct($id, $apiKey)
    {
        $this->id = $id;
        $this->apiKey = $apiKey;
        parent::__construct($id, $apiKey);
    }

    /**
     * Static Factory Method.
     * @param $id
     * @param $apiKey
     * @return WorldTextAdmin
     */
    public static function CreateAdminInstance($id, $apiKey)
    {
        return new WorldTextAdmin($id, $apiKey);
    }

    /**
     * @return array
     * @throws wtException
     */
    public function ping()
    {
        return $this->callResource(self::GET, '/admin/ping');
    }

    /**
     * @return array
     * @throws wtException
     */
    public function host()
    {
        return $this->callResource(self::GET, '/admin/host');
    }

    /**
     * @return array
     * @throws wtException
     */
    public function credits()
    {
        return $this->callResource(self::GET, '/admin/credits');
    }

}
