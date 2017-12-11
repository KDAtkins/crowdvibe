
<?php
require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once(dirname(__DIR__, 3) . "/php/lib/uuid.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
use Edu\Cnm\CrowdVibe\{
	Images,
	// we only use the profile and event class for testing purposes
	Profile, Event
};

/**
 * API for the Images
 *
 * @author Matthew David	<mdavid3636@gmail.com>
 * @version 1.0
 **/

//verify the session, start if not active
if(session_start() !== PHP_SESSION_ACTIVE) {
	session_start();
}

//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try {
		// grab the mySQL conection
		$pdo = connectToEncryptedMySQL("/ect/apache2/capstone-mysql/crowdvibe.ini");

		//determine which HTTP method was used
		$method = array_key_exists("HTTP_X_METHOD", $_SERVER) ?$_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	$config = readConfig("/etc/apache2/capstone-mysql/crowdvibe.ini");
	$cloudinary = json_decode($config["cloudinary"]);
	\Cloudinary::config(["cloud_name" => $cloudinary->cloudName, "api_key" => $cloudinary->apiKey, "api_secret" => $cloudinary->apiSecret]);
	// handle the POST request
	if($method === "POST") {
		// set XSRF token
		setXsrfCookie();
		// verify user is logged into their profile before uploading an image
		if(empty($_SESSION["profile"]) === true) {
			throw (new \InvalidArgumentException("you are not allowed to access this profile", 403));
		}
		// assigning variable to the user profile, add image extension
		$tempUserFileName = $_FILES["image"]["tmp_name"];
		// upload image to cloudinary and get public id
		$cloudinaryResult = \Cloudinary\Uploader::upload($tempUserFileName, array("width" => 500, "crop" => "scale"));

        $eventId = filter_input(INPUT_POST, "eventId", FILTER_VALIDATE_INT);

		// after sending the image to Cloudinary, create a new image
        if($eventId !== null) {
            $event = Event::getEventByEventId($pdo, $eventId);
        }
        if($eventId === null) {
            $profile = Profile::getProfileByProfileId($pdo, $_SESSION["profile"]->getProfileId());
            $profile->setProfileImage($cloudinaryResult["secure_url"]);
            $profile->update($pdo);
        }

        $reply->message = "Image uploaded Ok";
	}
} catch(Exception $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
}
header("Content-Type: application/json");
// encode and return reply to front end caller
echo json_encode($reply);

