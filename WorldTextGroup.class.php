<?php

class WorldTextGroup extends WorldText {

	private $groupID;
	private $numbers;
	private $numberCount = NULL; // NULL not called, 0 empty, # number of rows.
	private $retries = 0;  // Instant fail on error
	private $apiKey = '';
	private $id = '';

// **
// @param $id       World Text Account ID
// @param $apiKey   secret API Key
// @param $grpName  Group - created or loaded on construction.

	public function __construct($id, $apiKey, $grpName, $srcAddr = NULL, $pin = NULL) {

		parent::__construct($id, $apiKey);

		if ($srcAddr) {
			// Creating...
			$this->groupID = $this->create($grpName, $srcAddr, $pin); //$grp = newGroup
		} else {

			$grp = $this->find($grpName);

			if (!$grp) {
				throw new Exception('No pre-existing group');
			} else {
				$this->groupID = $grp;

				// Populate the group with its numbers...
				$this->details();
			}
		}
	}

	// Static Factory Methods...

	static public function CreateNewGroupInstance($id, $apiKey, $grpName, $srcAddr, $pin) {
		return new WorldTextGroup($id, $apiKey, $grpName, $srcAddr, $pin);
	}

	static public function CreateExistingGroupInstance($id, $apiKey, $grpName) {
		return new WorldTextGroup($id, $apiKey, $grpName, NULL, NULL);
	}

	// Group Methods ...
	// Only meaningful after
	// Construction with no group name parameter
	// or
	// After a destroy call

	public function create($grpName, $srcAddr, $pin) {

		if ($this->groupID) {
			// Bad dog, no biscuit...
			throw new Exception('Already have an active group - object must have no group set');
		}

		$data = array(
			'name' => $grpName,
			'srcaddr' => $srcAddr,
			'pin' => $pin
		);

		$ret = $this->callResource(self::PUT, '/group/create', $data);

		return $ret['data']['groupid'];
	}

	// Return an ID from name of Group, or NULL if not exist...

	public function find($searchGrp) {
		$ret = $this->callResource(self::GET, '/group/list');

		for ($i = 0; $i < count($ret['data']['group']); $i++) {
			if ($searchGrp == $ret['data']['group'][$i]['name']) {
				return $ret['data']['group'][$i]['id'];
			}
		}
		return NULL;
	}

	// Kill the group on the World Text servers...
	//
    // All group related object data is cleared after successful call:
	// 
	// Create new group and populate it, or
	// Create a new object
	// 
	// ...to continue.

	public function destroy() {

		$data = array(
			'grpid' => $this->groupID
		);

		$ret = $this->callResource(self::DELETE, '/group/destroy', $data);

		// Best check it worked first :)
		// Make sure people can't continue treating the group as active...
		unset($this->numbers);
		unset($this->groupID);
		$this->numberCount = NULL;
	}

	public function entry($name, $number) {
		$data = array(
			'grpid' => $this->groupID,
			'name' => $name,
			'dstaddr' => $number
		);

		return $this->callResource(self::PUT, '/group/entry', $data);
	}

	public function entries($list) {
		$data = array(
			'members' => $list
		);

		return $this->callResource(self::PUT, '/group/entries', $data);
	}

	// Return the array of names/numbers in this group...
	//
    // If we don't already have the list, populate it.

	public function details() {

		if (isset($this->numberCount)) {
			// Already got group contents, so just return the list...
			return( $this->numbers );
		}

		$data = array(
			'grpid' => $this->groupID
		);

		$ret = $this->callResource(self::GET, '/group/details', $data);
		if ($ret['http_code'] == 200) {
			// HTTP OK...
			$this->numberCount = count($ret['data']['entry']);

			// OK, but empty...
			if (!$this->numberCount)
				return NULL;

			foreach ($ret['data']['entry'] as $val) {
				$this->numbers[$val['number']] = $val['name'];
			}

			return $this->numbers;
		} else {
			// HTTP fail...
			$this->lastError = $ret;
			return NULL;
		}
	}

	// Empties the group of names and numbers...

	public function deleteContents() {
		return( $this->callResource(self::DELETE, '/group/contents') );
	}

	// Returns cost in credits to send this group...

	public function cost() {
		return( $this->callResource(self::GET, '/group/cost', array('grpid' => $this->groupID)));
	}

	// Send a single message to group ... 

	public function send($msg, $src = NULL) {
		$data = array(
			'grpid' => $this->groupID,
			'txt' => $msg
		);
		if ($src !== NULL) {
			$data = array_merge($data, array('srcaddr' => $src));
		}

		// Unicode/UTF8 test
		if (WorldText::isUTF8($msg)) {
			$data = array_merge($data, array('enc' => "UnicodeBigUnmarked"));
		}

		return $this->callResource(self::PUT, '/group/send', $data);
	}

}
