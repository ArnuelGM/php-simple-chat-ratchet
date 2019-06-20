<?php
	namespace MyApp;
	use Ratchet\MessageComponentInterface;
	use Ratchet\ConnectionInterface;
	class Chat implements MessageComponentInterface {
		protected $clientes;
		public function __construct(){
			$this->clientes = new \SplObjectStorage;
		}
	    public function onOpen(ConnectionInterface $conn) {
	    	$this->clientes->attach($conn);
	    	echo "New connection! ({$conn->resourceId})\n";
	    }
	    public function onMessage(ConnectionInterface $from, $msg) {
	    	foreach ($this->clientes as $cliente) {
	    		$cliente->send($msg);
	    	}
	    }
	    public function onClose(ConnectionInterface $conn) {
	    	$this->clientes->detach($conn);
	    	echo "Connection {$conn->resourceId} has disconnected\n";
	    }
	    public function onError(ConnectionInterface $conn, \Exception $e) {
	    	echo "An error has occurred: {$e->getMessage()}\n";
	    	$conn->close();
	    }
	}