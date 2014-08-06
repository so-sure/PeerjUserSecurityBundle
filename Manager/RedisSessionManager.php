<?php

/*
 * This file is part of the Peerj UserSecurityBundle
 *
 * (c) Peerj <https://peerj.com/>
 *
 * Available on github <http://www.github.com/Peerj/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Peerj\UserSecurityBundle\Manager;

use Peerj\UserSecurityBundle\Manager\SessionManagerInterface;


/**
 *
 * @author Patrick McAndrew <patrick@urg.name>
 * @version 1.0
 */
class RedisSessionManager implements SessionManagerInterface
{
    private $redis;

	/**
	 *
	 * @access public
	 * @param $
	 */
    public function __construct($redis)
    {
        $this->redis = $redis;
    }
    
    /**
     *
     * @access public
     * @package string $type
     * @param string $ipAddress
	 * @param string $timeLimit
     * @return array
     */
	public function findAllByIpAddressAndLoginAttemptDate($type, $ipAddress, $timeLimit)
	{
        $keyIp = sprintf("PeerJUserSecurityBundle::%s::ip::%s", $type, $ipAddress);
		$now = new \DateTime();
		/*
        $records = $this->redis->zrange($keyIp, $timeLimit->getTimestamp(), $now->getTimestamp());
		throw new \Exception($timeLimit->getTimestamp() . ' ' . $now->getTimestamp());
		$items = array();
		foreach($records as $record) {
			throw new \Exception('record');
			$items[$record->score] = $record->data;
		}
		*/
		$count = $this->redis->zcount($keyIp, $timeLimit->getTimestamp(), $now->getTimestamp());
                if ($count > 0) {
		  $items = array_fill(0, $count, null);
                } else {
                  $items = array();
                }

           return $items; 
	}
	
    /**
     *
     * @access public
     * @package string $type
     * @param string $ipAddress
     * @param string $username
     * @param \Datetime $timeLimit
     * @return self
     */
    public function newRecord($type, $ipAddress, $username, $timeLimit)
    {
        $keyIp = sprintf("PeerJUserSecurityBundle::%s::ip::%s", $type, $ipAddress);
        $keyUsername = sprintf("PeerJUserSecurityBundle::%s::username::%s", $type, $username);
        
        $now = new \DateTime();
        
        $this->redis->zadd($keyIp, $now->getTimestamp(), serialize(array($username, $now->getTimestamp())));
        $this->redis->expireat($keyIp, $timeLimit->getTimestamp());

        return $this;
    }
	
	public function getAllByType($type, $timeLimit)
	{
        $now = new \DateTime();
		$data = array();
        $keyIp = sprintf("PeerJUserSecurityBundle::%s::ip::%s", $type, "*");
		foreach($this->redis->keys($keyIp) as $key) {
			$total = $this->redis->zcount($key, '-inf', $now->getTimestamp());
			$current = $this->redis->zcount($key, $timeLimit->getTimestamp(), $now->getTimestamp());
            $ttl = $this->redis->ttl($key);
	        $keyData = $this->redis->zrevrangebyscore($key, 'inf', '-inf');

	        $startingTime = 'Unknown';
			if ($keyData) {
				$startingItem = unserialize($keyData[count($keyData) - 1]);
				$startingTime = \DateTime::createFromFormat('U', $startingItem[1])->format('c');
			}

	        $endingTime = 'Unknown';
			if ($keyData) {
				$endingItem = unserialize($keyData[0]);
				$endingTime = \DateTime::createFromFormat('U', $endingItem[1])->format('c');
			}
			$data[$key] = array('total' => $total, 'current' => $current, 'starting' => $startingTime, 'ending' => $endingTime, 'ttl' => $ttl);
		}
		
		return $data;
		
	}
	
	public function clear($type, $ipAddress)
	{
        $keyIp = sprintf("PeerJUserSecurityBundle::%s::ip::%s", $type, $ipAddress);
		$this->redis->del($keyIp);
	}
}
