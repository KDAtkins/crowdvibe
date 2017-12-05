<?php
require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/php/lib/uuid.php";
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

use Edu\Cnm\Crowdvibe\{
	Rating,
	// we only use the profile, event, and event attendance class for testing purposes
	Profile, Event, EventAttendance
};

/**
 * api for Rating class
 *
 * @author {} <mcdav3636@gmail.com>
 **/

//verify the session, start if not active
if(session_start() !==PHP_SESSION_ACTIVE) {
	session_start();
}

//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try {
		//grab the mySQL connection
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/ddctwitter.ini");

	//determine which HTTP method was used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];


	if($method === "POST") {

	// enforce the user has a XSRF token
	verifyXsrf();

	//  Retrieves the JSON package that the front end sent, and stores it in $requestContent. Here we are using file_get_contents("php://input") to get the request from the front end. file_get_contents() is a PHP function that reads a file into a string. The argument for the function, here, is "php://input". This is a read only stream that allows raw data to be read from the front end request which is, in this case, a JSON package.
	$requestContent = file_get_contents("php://input");

	// This Line Then decodes the JSON package and stores that result in $requestObject
	$requestObject = json_decode($requestContent);


	//make sure rating score is available (required field)
	if(empty($requestObject->ratingScore) === true) {
		throw(new \InvalidArgumentException ("No score for Rating.", 405));
	}

	//  make sure rating ratee profile id is available
	if(empty($requestObject->ratingRateeProfileId) === true) {
		throw(new \InvalidArgumentException ("No Profile ID.", 405));
	}

	// make sure rating event attendance id is available
	if(empty($requestObject->ratingEventAttendanceId) === true) {
		throw (new \InvalidArgumentException("No Event Attendance Id.", 405));
	}

	//perform the actual post


		// enforce the user is signed in
		if(empty($_SESSION["profile"]) === true) {
			throw(new \InvalidArgumentException("you must be logged in to make a rating", 403));
		}

		validateJwtHeader();

	// validate user attended event
		$eventAttendance =

		// create new rating and insert into the database
		$rating = new Rating(generateUuidV4(), $requestObject->ratingEventAttendanceId, $requestObject->ratingRateeProfileId, $_SESSION["profile"]->getProfileId, $requestObject->ratingScore);
		$rating->insert($pdo);

		// update reply
		$reply->message = "Rating was submitted successfully.";

}


// update the $reply->status $reply->message
} catch(\Exception | \TypeError $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
}

header("Content-type: application/json");
if($reply->data === null) {
	unset($reply->data);
}

// encode and return reply to front end caller
echo json_encode($reply);

// finally - JSON encodes the $reply object and sends it back to the front end.

