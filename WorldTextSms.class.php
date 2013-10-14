<?php

class WorldTextSms extends WorldText {

	// @param $id       World Text Account ID
	// @param $apiKey   secret API Key
	//

    public function __construct($id, $apiKey) {
		$this->id = $id;
		$this->apiKey = $apiKey;
		parent::__construct($id, $apiKey);
	}

	// Static Factory Method...

	public static function CreateSmsInstance($id, $apiKey) {
		return new WorldTextSms($id, $apiKey);
	}

	// SMS Methods...


	public function send($dst, $txt, $src = NULL, $multipart = NULL) {
		$data = array(
			'dstaddr' => $dst,
			'txt' => $txt
		);

		// Uincode/UTF8 test
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

		return ( $returned['data']['message'] );
	}

	public function query($msgID) {
		$data = array(
			'msgid' => $msgID
		);

		return($this->callResource(self::GET, '/sms/query', $data));
	}

	public function cost($dst) {
		$data = array(
			'dstaddr' => $dst
		);

		return($this->callResource(self::GET, '/sms/cost', $data) );
	}

}
