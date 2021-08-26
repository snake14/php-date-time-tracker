<?php
namespace Controllers;

class Main {
	public function getLoginPage($f3, $params) {
		$isLocalNetwork = ($_SERVER['SERVER_NAME'] === '192.168.1.100') ? 'true' : 'false';

		if($isLocalNetwork === 'true') {
			if(!isset($_SERVER['PHP_AUTH_USER'])) {
				header('WWW-Authenticate: Basic realm="My Realm"');
				header('HTTP/1.0 401 Unauthorized');
				echo 'Login Required';
				exit;
			} else {
				$db = $f3->get('db');
				if(empty($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER'] !== 'snake14' || empty($_SERVER['PHP_AUTH_PW']) || !$db->isLocalPassValid($_SERVER['PHP_AUTH_PW'])) {
					echo 'Login Failed';
					exit;
				}
			}
		}
		// Load the login page.
		include __DIR__ . '/../../views/login.php';
	}

	public function getMainPage($f3, $params) {
		// Load the main page.
		$DB = $f3->get('db');
		$result = $DB->getAllRecords();
		$DB->disconnect();
		
		$most_recent_date_string = '';
		$most_recent_time_string = '';

		$most_recent_time = $result['records'][0]['dt'];
		$date1 = new \DateTime();
		$date2 = new \DateTime($most_recent_time);
		$most_recent_date_string = $date2->format('m/d/y');
		$most_recent_time_string = $date2->format('h:i a');
		$diff = $date1->diff($date2);
		$diff_days = $diff->format('%d days');
		$diff_result = ($diff_days != '0 days' ? $diff_days.', ' : '').$diff->format('%H hours').', '.
			$diff->format('%i minutes').' since the last record';

		$isLocalNetwork = ($_SERVER['SERVER_NAME'] === '192.168.1.100') ? 'true' : 'false';
		$hasHttpAuth = (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) ? 'true' : 'false';

		include __DIR__ . '/../../views/main.php';
	}

	public function getEditPage($f3, $params) {
		// Update the tracking record in the database.
		$DB = $f3->get('db');
		$id = $params['id'];
		$result = $DB->getRecordById($id);
		$DB->disconnect();
		$date_time = date('m/d/y h:i a', strtotime($result['records']['dt']));
		include __DIR__ . '/../../views/edit.php';
	}

	public function update($f3, $params) {
		// Update the tracking record in the database.
		$DB = $f3->get('db');
		$result = $DB->updateRecord([ 'id' => $params['id'], 'dt' => $_POST['date_time'] ]);
		$DB->disconnect();
		echo json_encode($result);
	}

	public function delete($f3, $params) {
		// Update the tracking record in the database.
		$DB = $f3->get('db');
		$result = $DB->deleteRecord($params['id']);
		$DB->disconnect();
		echo json_encode($result);
	}

	public function create($f3, $params) {
		// Update the tracking record in the database.
		$DB = $f3->get('db');
		$result = $DB->createRecord();
		$DB->disconnect();
		echo json_encode($result);
	}
}
?>