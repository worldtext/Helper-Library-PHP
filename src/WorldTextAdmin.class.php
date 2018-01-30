<?php
namespace WorldText;

class WorldTextAdmin extends WorldText {

	// @param $id       World Text Account ID
// @param $apiKey   secret API Key
	//

	private function __construct($id, $apiKey) {
		$this->id = $id;
		$this->apiKey = $apiKey;
		parent::__construct($id, $apiKey);
	}

	// Static Factory Method...

	public static function CreateAdminInstance($id, $apiKey) {
		return new WorldTextAdmin($id, $apiKey);
	}

	// Admin Methods...
	public function ping() {
		return $this->callResource(self::GET, '/admin/ping');
	}

	public function host() {
		return $this->callResource(self::GET, '/admin/host');
	}

	public function credits() {
		return $this->callResource(self::GET, '/admin/credits');
	}

}
