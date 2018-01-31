<?php

namespace WorldText;

use Exception;


/**
 * Class WorldTextGroup
 * @package WorldText
 */
class WorldTextGroup extends WorldText
{

    private $groupID;
    private $numbers;
    private $numberCount = NULL; // NULL not called, 0 empty, # number of rows.
    private $retries = 0;  // Instant fail on error

    /**
     * WorldTextGroup constructor.
     * @param $id World Text Account ID
     * @param $apiKey API Key
     * @param $grpName Group - created or loaded on construction.
     * @param null $srcAddr
     * @param null $pin
     * @throws Exception
     * @throws wtException
     */
    public function __construct($id, $apiKey, $grpName, $srcAddr = NULL, $pin = NULL)
    {

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

    /**
     * @param $id
     * @param $apiKey
     * @param $grpName
     * @param $srcAddr
     * @param $pin
     * @return WorldTextGroup
     * @throws Exception
     * @throws wtException
     */
    static public function CreateNewGroupInstance($id, $apiKey, $grpName, $srcAddr, $pin)
    {
        return new WorldTextGroup($id, $apiKey, $grpName, $srcAddr, $pin);
    }

    /**
     * @param $id
     * @param $apiKey
     * @param $grpName
     * @return WorldTextGroup
     * @throws Exception
     * @throws wtException
     */
    static public function CreateExistingGroupInstance($id, $apiKey, $grpName)
    {
        return new WorldTextGroup($id, $apiKey, $grpName, NULL, NULL);
    }

    // The following Group Methods are Only meaningful after
    // Construction with no group name parameter or After a destroy call

    /**
     * @param $grpName
     * @param $srcAddr
     * @param $pin
     * @return mixed
     * @throws Exception
     * @throws wtException
     */
    public function create($grpName, $srcAddr, $pin)
    {

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

    /**
     * Return an ID from name of Group, or NULL if not exist...
     * @param $searchGrp
     * @return null
     * @throws wtException
     */
    public function find($searchGrp)
    {
        $ret = $this->callResource(self::GET, '/group/list');

        for ($i = 0; $i < count($ret['data']['group']); $i++) {
            if ($searchGrp == $ret['data']['group'][$i]['name']) {
                return $ret['data']['group'][$i]['id'];
            }
        }
        return NULL;
    }

    /**
     * Kill the active group on the World Text servers...
     * All group related object data is cleared after successful call:
     * @throws wtException
     */
    public function destroy()
    {

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

    /**
     * @param $name
     * @param $number
     * @return array
     * @throws wtException
     */
    public function entry($name, $number)
    {
        $data = array(
            'grpid' => $this->groupID,
            'name' => $name,
            'dstaddr' => $number
        );

        return $this->callResource(self::PUT, '/group/entry', $data);
    }

    /**
     * @param $list
     * @return array
     * @throws wtException
     */
    public function entries($list)
    {
        $data = array(
            'members' => $list
        );

        return $this->callResource(self::PUT, '/group/entries', $data);
    }

    //

    /**
     * Return the array of names/numbers in this group.
     * If we don't already have the list, populate it.
     * @return null on error or array of names/numbers
     * @throws wtException
     */
    public function details()
    {

        if (isset($this->numberCount)) {
            // Already got group contents, so just return the list...
            return ($this->numbers);
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

    /**
     * Empties the group of names and numbers
     * @return array
     * @throws wtException
     */
    public function deleteContents()
    {
        return ($this->callResource(self::DELETE, '/group/contents'));
    }

    /**
     * Returns cost in credits to send this group
     * @return array
     * @throws wtException
     */
    public function cost()
    {
        return ($this->callResource(self::GET, '/group/cost', array('grpid' => $this->groupID)));
    }

    /**
     * Send a message to group
     * @param $msg
     * @param null $src
     * @param null $multipart
     * @return array
     * @throws wtException
     */
    public function send($msg, $src = NULL, $multipart = NULL)
    {
        $data = array(
            'grpid' => $this->groupID,
            'txt' => $msg
        );

        if ($src !== NULL) {
            $data = array_merge($data, array('srcaddr' => $src));
        }

        if ($multipart) {
            $data = array_merge($data, array('multipart' => $multipart));
        }

        // Unicode/UTF8 test
        if (WorldText::isUTF8($msg)) {
            $data = array_merge($data, array('enc' => "UnicodeBigUnmarked"));
        }

        return $this->callResource(self::PUT, '/group/send', $data);
    }

}
