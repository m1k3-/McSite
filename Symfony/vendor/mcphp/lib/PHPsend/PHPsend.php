<?php

//error_reporting(0);

class PHPresponse
{
	var $msg;
	var $from;
	
	function isFrom($who)
	{
		if ($who==$from)
			return true;
		else
			return false;
	}
}

function recv($socket)
{
	$r=socket_read($socket, 256, PHP_NORMAL_READ);
	return substr($r,0,-1);
}

class PHPsend
{
	var $socket=null;
	
	function PHPconnect($adress, $password, $port=11223)
	{
		$this->socket = socket_create(AF_INET, SOCK_STREAM, 0);
		
		socket_set_block($this->socket);
		
		$result = socket_connect($this->socket, $adress, $port);
		
		if ($this->socket==null)
				return 1;
		
		socket_write($this->socket, md5($password)."\n", strlen(md5($password))+2); //auth
		$result=recv($this->socket);
		
		/*echo 'RESULT: '.$result."\n";
		echo md5($result)."\n";*/
		
		if ($result!="PHPpass0")
			return 2;
		else
			return 0;
	}
	
	function PHPcommand($command)
	{
		socket_write($this->socket, $command."\n",strlen($command)+2);
		$result=recv($this->socket);
		
		if ($result!="PHPcmd0")
			return 1;
		return 0;
	}
	
	function PHPdisconnect()
	{
		socket_write($this->socket, "PHPdisconnect\n",15);
		$result=recv($this->socket);
		
		if ($result!="PHPdisconnect0")
			return 1;
		return 0;
	}
	
	function PHPrecv()
	{
		$result=recv($this->socket);
		$resp=explode(':',$result,2);
		$r=new PHPresponse();
		$r->from=$resp[0];
		$r->msg=$resp[1];
		return $r;
	}
	
		function PHPrecvMsg()
	{
		$result=recv($this->socket);
		$resp=explode(':',$result,2);
		return $resp[1];
	}
}

?>
