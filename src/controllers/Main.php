<?php
namespace Controllers;

class Main {
	public function getMainPage($f3, $params) {
		// Check to make sure that the user has logged in.
		if(!isset($_SERVER['PHP_AUTH_USER'])) {
			header('WWW-Authenticate: Basic realm="My Realm"');
			header('HTTP/1.0 401 Unauthorized');
			echo 'Login Required';
			exit;
		} else {
			$DB = $f3->get('db');
			if(empty($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER'] !== 'snake14' || empty($_SERVER['PHP_AUTH_PW']) || !$DB->isLocalPassValid($_SERVER['PHP_AUTH_PW'])) {
				echo 'Login Failed';
				exit;
			}
		}

		// Load the main page data.
		$DB = $f3->get('db');
		$result = $DB->getAllRecords();
		$DB->disconnect();

		$most_recent_time = $result['records'][0]['dt'];
		$date1 = new \DateTime();
		$date2 = new \DateTime($most_recent_time);
		$diff = $date1->diff($date2);
		$diff_days = $diff->format('%d days');
		$diff_result = ($diff_days != '0 days' ? $diff_days.', ' : '').$diff->format('%H hours').', '.
			$diff->format('%i minutes').' since the last record';

		$records = [];
		if(!empty($result['success']) && !empty($result['records']) && is_array($result['records'])) {
			$records = $result['records'];
		}
		$f3->set('records', $records);
		$f3->set('mostRecentRecord', $diff_result);

		echo \Template::instance()->render('main.htm');
	}

	public function getEditPage($f3, $params) {
		// Update the tracking record in the database.
		$DB = $f3->get('db');
		$id = $params['id'];
		$result = $DB->getRecordById($id);
		$DB->disconnect();
		$dateTime = date('m/d/y h:i a', strtotime($result['records']['dt']));
		$f3->set('id', $id);
		$f3->set('dateTime', $dateTime);

		echo \Template::instance()->render('edit.htm');
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