<?php
namespace WorldText;

/*
 * Extend Exception, adding status and desc fields...
 */

class wtException extends Exception {

	protected $status;
	protected $desc;
	protected $error;

	public function __construct($message, $json, $code = 0) {
		$status = "1";
		try {
			$data = json_decode($json, true);
			$this->desc = $data['desc'];
			$this->error = $data['error'];
		} catch (Exception $ex) {
			
		}
		parent::__construct($message, $code);
	}

	public function getStatus() {
		return $this->status;
	}

	public function getDesc() {
		return $this->desc;
	}

	public function getError() {
		return $this->error;
	}

}

?>
