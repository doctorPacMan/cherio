<?php
namespace WebSocket\Application;
class EchoApplication extends Application
{
    private $_clients = array();
	public function onConnect($client)
    {
		$cid = $client->getClientId();
		$sok = $client->getClientSocket();
        $this->_clients[$cid] = $client;
        
        $data = "Client join count:".count($this->_clients)." #".$cid."";
		$client->log($data);

		//$encodedData = $this->_encodeData('join', $cid);
		//$client->send($encodedData);

		$msdata = $this->messageData('info',$data);
		$this->sendAll($msdata);
    }
    public function onDisconnect($client)
    {
        $cid = $client->getClientId();		
		unset($this->_clients[$cid]);     

        $data = "Client drop count:".count($this->_clients)." #".$cid."";
		$client->log($data);
		
		$msdata = $this->messageData('info',$data);
		$this->sendAll($msdata);
    }
    public function onData($data, $client)
    {		
		if($data==='auth') $this->action_Auth($data, $client);
		else $this->action_Echo($data, $client);
    }
	private function sendAll($encodedData)
	{		
		foreach($this->_clients as $client) $client->send($encodedData);
	}
	private function messageData($action='info', $data='{}')
	{		
		if(is_string($data)) $data = '"'.$data.'"';
		$ed = $this->_encodeData($action, $data);
		return $ed;
	}
	private function action_Echo($data, $client)
	{		
		//$msdata = $this->messageData('echo',['message'=>$data]);
		$msdata = $this->messageData('echo',$data);
		$client->send($msdata);
	}
	private function action_Auth($data, $client)
	{		
		
		$kukis = $client->getClientCookies();
		$token = empty($kukis['token']) ? NULL : $kukis['token'];
		
		$response = [
			'cid' => $client->getClientId(),
			'token' => $token,
			'success' => FALSE
		];
		$msdata = $this->messageData('auth',$response);
		$client->send($msdata);
	}
/*
    private $_clients = array();
	private $_serverClients = array();
	private $_serverInfo = array();
	private $_serverClientCount = 0;


	public function onConnect($client)
    {
		$id = $client->getClientId();
        $this->_clients[$id] = $client;
		$this->_sendServerinfo($client);
    }

    public function onDisconnect($client)
    {
        $id = $client->getClientId();		
		unset($this->_clients[$id]);     
    }

	public function setServerInfo($serverInfo)
	{
		if(is_array($serverInfo))
		{
			$this->_serverInfo = $serverInfo;
			return true;
		}
		return false;
	}


	public function clientConnected($ip, $port)
	{
		$this->_serverClients[$port] = $ip;
		$this->_serverClientCount++;
		$this->statusMsg('Client connected: ' .$ip.':'.$port);
		$data = array(
			'ip' => $ip,
			'port' => $port,
			'clientCount' => $this->_serverClientCount,
		);
		$encodedData = $this->_encodeData('clientConnected', $data);
		$this->_sendAll($encodedData);
	}
	
	public function clientDisconnected($ip, $port)
	{
		if(!isset($this->_serverClients[$port]))
		{
			return false;
		}
		unset($this->_serverClients[$port]);
		$this->_serverClientCount--;
		$this->statusMsg('Client disconnected: ' .$ip.':'.$port);
		$data = array(			
			'port' => $port,
			'clientCount' => $this->_serverClientCount,
		);
		$encodedData = $this->_encodeData('clientDisconnected', $data);
		$this->_sendAll($encodedData);
	}
	
	public function clientActivity($port)
	{
		$encodedData = $this->_encodeData('clientActivity', $port);
		$this->_sendAll($encodedData);
	}

	public function statusMsg($text, $type = 'info')
	{		
		$data = array(
			'type' => $type,
			'text' => '['. strftime('%m-%d %H:%M', time()) . '] ' . $text,
		);
		$encodedData = $this->_encodeData('statusMsg', $data);		
		$this->_sendAll($encodedData);
	}
	
	private function _sendServerinfo($client)
	{
		if(count($this->_clients) < 1)
		{
			return false;
		}
		$currentServerInfo = $this->_serverInfo;
		$currentServerInfo['clientCount'] = count($this->_serverClients);
		$currentServerInfo['clients'] = $this->_serverClients;
		$encodedData = $this->_encodeData('serverInfo', $currentServerInfo);
		$client->send($encodedData);
	}
	
	private function _sendAll($encodedData)
	{		
		if(count($this->_clients) < 1)
		{
			return false;
		}
		foreach($this->_clients as $sendto)
		{
            $sendto->send($encodedData);
        }
	}
*/
}