<?php
	namespace Utils;

	class Db {
		protected $localPass;
		protected $host;
		protected $username;
		protected $password;
		protected $dbname;
		protected $port;
		protected $conn;

		public function __construct(array $config) {
			$notFound = [];
			foreach([ 'localPass', 'host', 'username', 'password', 'dbName' ] as $field) {
				if(empty($config[$field])) {
					$notFound[] = $field;
				}
			}

			if(count($notFound)) {
				throw new \Exception('The following required fields were not provided in the config file: ' . join(', ', $notFound) . '.');
			}

			$this->localPass = $config['localPass'];
			$this->host = $config['host'];
			$this->username = $config['username'];
			$this->password = $config['password'];
			$this->port = $config['port'] ?? null;
			$this->dbname = $config['dbName'];
		}

		public function isLocalPassValid(string $pass) : bool {
			return password_verify($pass, $this->localPass);
		}

		public function connect() : array {
			$this->conn = new \mysqli($this->host, $this->username, $this->password, $this->dbname, $this->port);
			return [ 'success' => !!$this->conn->connect_error, 'error' => $this->conn->connect_error ];
		}

		public function disconnect() {
			$this->conn->close();
		}

		public function executeQuery(string $sql) : array {
			return [ 'success' => $this->conn->query($sql), 'insertId' => $this->conn->insert_id, 'error' => $this->conn->error ];
		}

		public function createRecord(array $params = []) : array {
			$stmt = $this->conn->prepare("INSERT INTO records (dt) VALUES (NOW())");
			if(!$stmt)
				return [ 'success' => false, 'error' => $this->conn->error ];
			$result = $stmt->execute();
			$stmt->close();
			return [ 'success' => $result, 'insertId' => $this->conn->insert_id, 'error' => $this->conn->error ];
		}

		public function getAllRecords() {
			$stmt = $this->conn->prepare("SELECT * FROM records ORDER BY id DESC;");
			$success = $stmt->execute();
			if($success) {
				$result = $stmt->get_result();
				$rows = [];
				while($row = $result->fetch_assoc()) {
					$rows[] = $row;
				}
			}
			$stmt->close();

			return [ 'success' => $success, 'records' => $rows ];
		}
		
		public function getRecordById(int $id) {
			$stmt = $this->conn->prepare("SELECT * FROM records WHERE id = ? ORDER BY id DESC;");
			if(!$stmt)
				return [ 'success' => false, 'error' => $this->conn->error ];
			$stmt->bind_param("i", $id);
			$success = $stmt->execute();
			if($success) {
				$result = $stmt->get_result();
				$row = $result->fetch_assoc();
			}
			$stmt->close();

			return [ 'success' => $success, 'records' => $row ];
		}
		
		public function updateRecord(array $params) : array {
			$dtString = date('Y-m-d H:i:s', strtotime($params['dt']));
			if(!$dtString)
				return [ 'success' => false, 'error' => 'Invalid date' ];

			$stmt = $this->conn->prepare("UPDATE records
				SET dt = ?
				WHERE id = ?;");
			if(!$stmt)
				return [ 'success' => false, 'error' => $this->conn->error ];
			$stmt->bind_param("si", $dtString, $params['id']);
			$result = $stmt->execute();
			$stmt->close();
			return [ 'success' => $result, 'error' => $this->conn->error ];
		}

		public function deleteRecord(int $recordId) : array {
			$stmt = $this->conn->prepare("DELETE FROM records WHERE id = ?;");
			$stmt->bind_param('i', $recordId);
			$result = $stmt->execute();
			$stmt->close();
			return [ 'success' => $result, 'error' => $this->conn->error ];
		}
	}