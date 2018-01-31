<?php

namespace WorldText;


/**
 * Class WorldTextSms
 * @package WorldText
 */
class WorldTextSms extends WorldText
{

    /**
     * WorldTextSms constructor.
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
     * @param $id World Text Account ID
     * @param $apiKey API Key
     * @return WorldTextSms
     */
    public static function CreateSmsInstance($id, $apiKey)
    {
        return new WorldTextSms($id, $apiKey);
    }

    /**
     * @param $dst
     * @param $txt
     * @param null $src
     * @param null $multipart
     * @return mixed
     * @throws wtException
     */
    public function send($dst, $txt, $src = NULL, $multipart = NULL)
    {
        $data = array(
            'dstaddr' => $dst,
            'txt' => $txt
        );

        // Unicode/UTF8 test
        if (WorldText::isUTF8($txt)) {
            $data = array_merge($data, array('enc' => "UnicodeBigUnmarked"));
        }

        if ($src !== NULL) {
            $data = array_merge($data, array('srcaddr' => $src));
        }

        if ($multipart) {
            $data = array_merge($data, array('multipart' => $multipart));
        }

        try {
            $returned = $this->callResource(self::PUT, '/sms/send', $data);
        } catch (wtException $ex) {
            throw $ex;
        }

        return ($returned['data']['message']);
    }

    /**
     * @param $msgID
     * @return array
     * @throws wtException
     */
    public function query($msgID)
    {
        $data = array(
            'msgid' => $msgID
        );

        return ($this->callResource(self::GET, '/sms/query', $data));
    }

    /**
     * @param $dst
     * @return array
     * @throws wtException
     */
    public function cost($dst)
    {
        $data = array(
            'dstaddr' => $dst
        );

        return ($this->callResource(self::GET, '/sms/cost', $data));
    }

}
