<?php

namespace Edu\Cnm\CrowdVibe;
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;


/**
 * An event is user created, it is allow people to join you in any activity you are participating in.
 *
 * @author Luther Mckeiver <lmckeiver@cnm.edu>
 * @version 1.0.0
 **/
class Event implements \JsonSerializable {
	use ValidateDate;
	use ValidateUuid;
	/**
	 * id for this Event; this is the primary key
	 * @var Uuid $eventId
	 **/
	private $eventId;

	/**
	 * id of the profile that sent the event, this is foreign key
	 * @var Uuid $eventProfileId ;
	 **/
	private $eventProfileId;
	/**
	 * this is the actual text detail of the event
	 *
	 * @var string $eventDetail
	 **/
	private $eventDetail;
	/**
	 * this is the latitude of the event
	 *
	 * @var float $eventLat
	 **/
	private $eventLat;
	/**
	 * this is the longitude of the event
	 *
	 * @var float $eventLong
	 **/
	private $eventLong;
	/**
	 * this specifies whether the event will cost money or will be free
	 *
	 * @var float $eventPrice
	 **/
	private $eventPrice;
	/**
	 * this will specify when the event will begin
	 *
	 * @var \DateTime $eventStartTime
	 **/
	private $eventStartDateTime;
	/**
	 * this specifies the time the event will end.
	 *
	 * @var \DateTime $eventEndDateTime
	 **/
	private $eventEndDateTime;
	/**
	 * this specifies the limit of individuals that can attend an event
	 *
	 * @var int $eventAttendeeLimit
	 **/
	private $eventAttendeeLimit;
	/**
	 * this is an image for an event
	 *
	 * @var null $eventImage
	 */
	private $eventImage;
	/**
	 * this is the name of the event
	 *
	 * @var string $eventName
	 */
	private $eventName;


	/**
	 * Event constructor.
	 * @param Uuid|string $newEventId
	 * @param Uuid|string $newEventProfileId
	 * @param int $newEventAttendeeLimit
	 * @param string $newEventDetail
	 * @param \DateTime|string $newEventEndDateTime
	 * @param string|null $newEventImage
	 * @param float $newEventLat
	 * @param float $newEventLong
	 * @param string $newEventName
	 * @param float $newEventPrice
	 * @param \DateTime|string $newEventStartDateTime
	 */

	public function __construct($newEventId, $newEventProfileId, ?int $newEventAttendeeLimit, string $newEventDetail, $newEventEndDateTime, ?string $newEventImage, float $newEventLat, float $newEventLong, string $newEventName, float $newEventPrice, $newEventStartDateTime) {
		try {
			$this->setEventId($newEventId);
			$this->setEventProfileId($newEventProfileId);
			$this->setEventAttendeeLimit($newEventAttendeeLimit);
			$this->setEventDetail($newEventDetail);
			$this->setEventEndDateTime($newEventEndDateTime);
			$this->setEventImage($newEventImage);
			$this->setEventLat($newEventLat);
			$this->setEventLong($newEventLong);
			$this->setEventName($newEventName);
			$this->setEventPrice($newEventPrice);
			$this->setEventStartDateTime($newEventStartDateTime);
		} //determine what exception type was thrown
		catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw (new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for event id
	 *
	 * @return Uuid value of event id
	 **/
	public function getEventId(): Uuid {
		return ($this->eventId);
	}


	/**
	 * mutator method for event id
	 *
	 * @param Uuid|string $newEventId value of event id
	 * @throws \RangeException if $nevEventId is not positive
	 * @throws \TypeError if $newEventId is not a
	 **/
	public function setEventId($newEventId): void {
		try {
			$Uuid = self::validateUuid($newEventId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		// convert and store event id
		$this->eventId = $Uuid;
	}


	/**
	 * accessor method for event profile id
	 *
	 * @return uuid value of event profile id
	 **/
	public function getEventProfileId(): uuid {
		return ($this->eventProfileId);
	}

	/**
	 * mutator method for event profile id
	 *
	 * @param uuid $newEventProfileId new value of event profile id
	 * @throws \RangeException if $newProfileId is not positive
	 * @throws \TypeError if $newProfileId is not an integer
	 **/
	public function setEventProfileId($newEventProfileId): void {
		try {
			$uuid = self::validateUuid($newEventProfileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		// convert and store event id
		$this->eventProfileId = $uuid;

	}

	/**
	 * accessor method for event detail
	 *
	 * @return string value of event detail
	 **/
	public function getEventDetail(): string {
		return ($this->eventDetail);
	}

	/**
	 * mutator method for event detail
	 *
	 * @param string $newEventDetail new value of event detail
	 * @throws \InvalidArgumentException if $newEventDetail is not a string or insecure
	 * @throws \RangeException if $newEventDetail is > 500
	 * @throws \TypeError if $newEventDetail is not a string
	 **/
	public function setEventDetail(string $newEventDetail): void {
		// verify the event detail is secure
		$newEventDetail = trim($newEventDetail);
		$newEventDetail = filter_var($newEventDetail, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newEventDetail) === true) {
			throw (new \InvalidArgumentException("event detail is empty or insecure"));
		}
		//verify the event detail will fit in the database
		if(strlen($newEventDetail) > 500) {
			throw (new \RangeException("event content is too long"));
		}
		//store the event detail
		$this->eventDetail = $newEventDetail;
	}

	/**
	 * accessor method for event name
	 *
	 * @return string value of event name
	 **/
	public function getEventName(): string {
		return ($this->eventName);
	}

	/**
	 * mutator method for event name
	 *
	 * @param string $newEventName new value of event name
	 * @throws \InvalidArgumentException if $newEventName is not a string or insecure
	 * @throws \RangeException if $newEventName is > 64 characters
	 * @throw \TypeError if $newEventName is not a string
	 **/
	public function setEventName(string $newEventName): void {
		//verify the event name is secure
		$newEventName = trim($newEventName);
		$newEventName = filter_var($newEventName, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newEventName) === true) {
			throw (new \InvalidArgumentException("event name is empty or insecure"));
		}
		//verify the event name will fit in the database
		if(strlen($newEventName) > 64) {
			throw (new \RangeException("event name is too long"));
		}

		// store the event name
		$this->eventName = $newEventName;
	}

	/**
	 * accessor method for event image
	 *
	 * @return string value of event image
	 **/
	public function getEventImage() : ?string {
		return ($this->eventImage);
	}

	/**
	 * mutator method for event image
	 *
	 * @param string $newEventImage new value of event image
	 * @throws \InvalidArgumentException if $newEventImage is not not a string or insecure
	 * @throws \RangeException if $newEventImage is > 64 characters
	 * @throw \TypeError if $newEventImage is not a string
	 **/
	public function setEventImage(?string $newEventImage): void {
		if($newEventImage === null) {
	$this->eventImage = null;
	return;
	}
		// verify the image is insecure
		$newEventImage = trim($newEventImage);
		$newEventImage = filter_var($newEventImage, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		// verify the event image will fit in the database
		if(strlen($newEventImage) > 255) {
			throw (new \RangeException("event image is too long"));
		}
		//store the event image
		$this->eventImage = $newEventImage;
	}

	/**
	 * accessor method for event price
	 *
	 * @return float of event price
	 **/
	public function getEventPrice() {
		return ($this->eventPrice);
	}

	/**
	 * mutator method for event price
	 *
	 * @param float $newEventPrice new value of event price
	 * @throws \InvalidArgumentException if $newEventPrice is not a float or insecure
	 * @throws \RangeException if $newEventPrice is >
	 * @throw |TypeError if $newEventPrice is not a float
	 **/
	public function setEventPrice(float $newEventPrice): void {
		// verify the price is insecure
		$newEventPrice = trim($newEventPrice);
		$newEventPrice = filter_var($newEventPrice, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newEventPrice) === true) {
			throw (new \InvalidArgumentException("event price is empty or insecure"));
		}
		// verify the event price will fit in the database
		if(strlen($newEventPrice) <= 7) {
		} else {
			throw (new \RangeException("event price is too much"));
		}
		// store this event price
		$this->eventPrice = $newEventPrice;
	}

	/**
	 * accessor method for event Latitude
	 *
	 * @return float value of event Latitude
	 **/
	public function getEventLat(): float {
		return ($this->eventLat);
	}

	/**
	 * mutator method for event Latitude
	 *
	 * @param float $newEventLat new value event Latitude
	 * @throws \InvalidArgumentException if $newEventLat is not a valid latitude or insecure
	 * @throws \RangeException if $newEventLat is > 12 characters
	 * @throws \TypeError if $newEventLat is not a float
	 **/
	public function setEventLat(float $newEventLat): void {
		// verify the float will fit in the database
		if(($newEventLat > 90) || ($newEventLat < -90)) {
			throw (new \RangeException("latitude is too big of a number"));
		}
		// store the latitude for event
		$this->eventLat = $newEventLat;
	}

	/**
	 * accessor method for eventLong
	 *
	 * @return float value for event Longitude
	 **/
	public function getEventLong(): float {
		return ($this->eventLong);
	}

	/**
	 * mutator method for event Longitude
	 *
	 * @param float $newEventLong new value event Longitude
	 * @throws \InvalidArgumentException if $newEventLong is not a valid longitude or insecure
	 * @throws \RangeException if $newEventLong is > 12 characters
	 * @throws \TypeError if $newEventLong is not a float
	 **/
	public function setEventLong(float $newEventLong): void {
		// verify the float will fit in the database
		if(($newEventLong > 180) || ($newEventLong < -180)) {
			throw(new \RangeException("longitude is too large of a number"));
		}
		//store the event longitude
		$this->eventLong = $newEventLong;

	}

	/**
	 * accessor method for eventStartDateTime
	 *
	 * @return \DateTime
	 */
	public function getEventStartDateTime(): string {
		return ($this->eventStartDateTime);
	}

	/**
	 * mutator method for eventStartDateTime
	 *
	 * @param string $newEventStartDateTime
	 * @throws |DateTime|string $newStartDateTime comment date as a DateTime object or string (or null to load the current time)
	 * @throws |InvalidArgumentException if $newEventStartDateTime is not a valid
	 * @throws \RangeException if $newEventStartDateTime is a date that does not exist
	 */

	public function setEventStartDateTime($newEventStartDateTime): void {
		// store the date/time using the Validate Trait
		try {
			$newEventStartDateTime = self::validateDateTime($newEventStartDateTime);
		} catch(\InvalidArgumentException | \RangeException $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		$this->eventStartDateTime = $newEventStartDateTime;
	}

	/**
	 * accessor method to eventEndDateTime
	 *
	 * @return string
	 **/
	public function getEventEndDateTime(): string {
		return ($this->eventEndDateTime);
	}

	/**
	 * mutator method for eventEndDateTime
	 *
	 * @param string $newEventEndDateTime
	 * @throws |DateTime|string $newEventDateTime comment date as a DateTime object or string (or null to load the current time)
	 * @throws |InvalidArgumentException if $newEventEndDateTime is not a valid
	 * @throws \RangeException if $newEventEndDateTime is a date that does not exist
	 **/

	public function setEventEndDateTime($newEventEndDateTime): void {
		// store the date/time using the Validate Trait
		try {
			$newEventEndDateTime = self::validateDateTime($newEventEndDateTime);
		} catch(\InvalidArgumentException | \RangeException $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		$this->eventEndDateTime = $newEventEndDateTime;
	}

	/**
	 * accessor method for eventAttendeeLimit
	 *
	 * @return int
	 **/
	public function getEventAttendeeLimit(): ?int {
		return ($this->eventAttendeeLimit);
	}

	/**
	 * mutator method for eventAttendeeLimit
	 *
	 * @param null|int $newEventAttendeeLimit new value of event Attendee
	 * @throws \RangeException if $newEventAttendeeLimit is not positive
	 **/
	public function setEventAttendeeLimit(?int $newEventAttendeeLimit): void {
		// verify the attendance is less than <500

		if(empty($newEventAttendeeLimit) === true) {
			$newEventAttendeeLimit= null;
		}
		//verify the event attendance will fit in the database
		if($newEventAttendeeLimit > 500) {
			throw (new \RangeException("event attendance is too large"));
		}
		$this->eventAttendeeLimit = $newEventAttendeeLimit;

	}

	/**
	 * Insert this Event into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo): void {

		//formatted date
		$formattedStartDate = $this->eventStartDateTime->format("Y-m-d H:i:s.u");
		$formattedEndDate = $this->eventEndDateTime->format("Y-m-d H:i:s.u");
		//create query template
		$query = "INSERT INTO event(eventId, eventProfileId, eventAttendeeLimit, eventDetail, eventEndDateTime, eventImage, eventLat, eventLong, eventName, eventPrice, eventStartDateTime) VALUES (:eventId, :eventProfileId, :eventAttendeeLimit, :eventDetail, :eventEndDateTime, :eventImage, :eventLat, :eventLong, :eventName, :eventPrice, :eventStartDateTime)";
		$statement = $pdo->prepare($query);
		$parameters = ["eventId" => $this->eventId->getBytes(), "eventProfileId" => $this->eventProfileId->getBytes(), "eventAttendeeLimit" => $this->eventAttendeeLimit, "eventDetail" => $this->eventDetail,"eventEndDateTime" =>$formattedEndDate, "eventImage" => $this->eventImage, "eventLat" => $this->eventLat, "eventLong" => $this->eventLong, "eventName" => $this->eventName, "eventPrice" => $this->eventPrice, "eventStartDateTime" =>$formattedStartDate];

		$statement->execute($parameters);
	}

	/**
	 * Delete this Event from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function delete(\PDO $pdo): void {
		// create query template
		$query = "DELETE FROM event WHERE eventId = :eventId";
		$statement = $pdo->prepare($query);
		// bind the member variables to the place holders in the template
		$parameters = ["eventId" => $this->eventId->getBytes()];
		$statement->execute($parameters);

	}

	/**
	 * updates this Event from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 **/
	public function update(\PDO $pdo): void {
		// create query template
		$query = "UPDATE event SET eventProfileId= :eventProfileId, eventAttendeeLimit= :eventAttendeeLimit,eventDetail= :eventDetail, eventEndDateTime= :eventEndDateTime, eventImage= :eventImage, eventLat= :eventLat, eventLong= :eventLong, eventName= :eventName, eventPrice= :eventPrice, eventStartDateTime= :eventStartDateTime WHERE eventId = :eventId";
		$statement = $pdo->prepare($query);
		$sun = $this->eventStartDateTime->format("Y-m-d H:i:s.u");
		$night = $this->eventEndDateTime->format("Y-m-d H:i:s.u");

		// bind the member variables to the place holders in the template
		$parameters = ["eventId"=>$this->eventId->getBytes(), "eventProfileId"=>$this->eventProfileId->getBytes(), "eventAttendeeLimit" => $this->eventAttendeeLimit,"eventDetail" => $this->eventDetail,"eventEndDateTime" =>$night, "eventImage" => $this->eventImage, "eventLat" => $this->eventLat, "eventLong" => $this->eventLong, "eventName" => $this->eventName, "eventPrice" => $this->eventPrice, "eventStartDateTime" =>$sun];
		$statement->execute($parameters);
	}



	/**
	 * gets an array of tweets based on its date
	 * (this is an optional get by method and has only been added for when specific edge cases arise in capstone projects)
	 *
	 * @param \PDO $pdo connection object
	 * @param string $sunriseEventStartDate beginning date to search for
	 * @param string $sunsetEventStartDate ending date to search for
	 * @return \SplFixedArray of events found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 * @throws \InvalidArgumentException if either sun dates are in the wrong format
	 */
	public static function getEventByEventStartDateTime(\PDO $pdo, string $eventSunriseStartDateTime, string $eventSunsetStartDateTime): \SplFixedArray {
		//enforce both dates are present
		if((empty ($eventSunriseStartDateTime) === true) || (empty($eventSunsetStartDateTime) === true)) {
			throw (new \InvalidArgumentException("dates are empty of insecure"));
		}

		//ensure both dates are in the correct format and are secure
		try {
			$eventSunriseStartDateTime = self::validateDateTime($eventSunriseStartDateTime);
			$eventSunsetStartDateTime = self::validateDateTime($eventSunsetStartDateTime);

		} catch(\InvalidArgumentException | \RangeException $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
	}

	//create query template
		$query = "SELECT eventId, eventProfileId, eventAttendeeLimit,  eventDetail,eventEndDateTime, eventImage, eventLat, eventLong, eventName, eventPrice, eventStartDateTime FROM event WHERE eventStartDateTime >= :sunriseEventDate AND eventStartDateTime <= :sunsetEventDate";
		$statement = $pdo->prepare($query);

		//format the dates so that mySQL can use them
		$formattedEventSunriseStartDateTime = $eventSunriseStartDateTime->format("Y-m-d H:i:s.u");
		$formattedEventSunsetStartDateTime = $eventSunsetStartDateTime->format("Y-m-d H:i:s.u");

		$parameters = ["sunriseEventDate" => $formattedEventSunriseStartDateTime, "sunsetEventDate" => $formattedEventSunsetStartDateTime];
		$statement->execute($parameters);

		//build an array of events
		$events = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);

		while(($row = $statement->fetch()) !== false) {
		try {

			$event = new Event($row["eventId"], $row["eventProfileId"], $row["eventAttendeeLimit"], $row ["eventDetail"], $row["eventEndDateTime"], $row ["eventImage"], $row["eventLat"], $row["eventLong"], $row["eventName"], $row["eventPrice"], $row["eventStartDateTime"]);
			$events[$events->key()] = $event;
			$events->next();

		} catch(\Exception $exception) {
			throw (new \PDOException($exception->getMessage(),0,$exception));
		}
		}
		return($events);
	}

	/**
	 * gets the Event by event id
	 *
	 * @param \PDO $pdo $pdo PDO Connection object
	 * @param Uuid|string $eventId event id to search for
	 * @return Event|null Event found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getEventByEventId(\PDO $pdo, $eventId): ?Event {
		//sanitize the event id before searching
		try {
			$eventId = self::validateUuid($eventId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw (new \PDOException($exception->getMessage(), 0, $exception));
		}
		// create query template
		$query = "SELECT eventId, eventProfileId, eventAttendeeLimit, eventDetail,eventEndDateTime,eventImage, eventLat, eventLong, eventName, eventPrice, eventStartDateTime FROM event WHERE eventId =:eventId";
		$statement = $pdo->prepare($query);
		// bind the event id to the placeholder in the template
		$parameters = ["eventId" => $eventId->getBytes()];
		$statement->execute($parameters);
		// grab the event from mySQL
		try {
			$event = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$event = new Event($row["eventId"], $row["eventProfileId"], $row["eventAttendeeLimit"], $row["eventDetail"], $row["eventEndDateTime"], $row["eventImage"], $row["eventLat"], $row["eventLong"], $row["eventName"], $row["eventPrice"], $row["eventStartDateTime"]);
			}
		} catch(\Exception $exception) {
			// if the row could not be converted, rethrow it
			throw (new \PDOException($exception->getMessage(), 0, $exception));
		}
		return ($event);

	}

	/**
	 * gets the Event by event profile id
	 *
	 * @param \PDO $pdo $pdo PDO Connection object
	 * @param Uuid|string $eventProfileId event id to search for
	 * @return ?\SplFixedArray
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getEventByEventProfileId(\PDO $pdo, $eventProfileId): \SplFixedArray {
		try {
			$eventProfileId = self::validateUuid($eventProfileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw (new \PDOException($exception->getMessage(), 0, $exception));
		}

		//create query template
		$query = "SELECT eventId, eventProfileId, eventAttendeeLimit,eventDetail ,eventEndDateTime, eventImage, eventLat, eventLong, eventName, eventPrice, eventStartDateTime FROM event WHERE eventProfileId = :eventProfileId";
		$statement = $pdo->prepare($query);
		//bind the event profile id to the placeholder in the template
		$parameters = ["eventProfileId" => $eventProfileId->getBytes()];
		$statement->execute($parameters);
		// build an array of events
		$events = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$event = new Event($row["eventId"], $row["eventProfileId"], $row["eventAttendeeLimit"], $row["eventDetail"], $row["eventEndDateTime"], $row["eventImage"], $row["eventLat"], $row["eventLong"], $row["eventName"], $row["eventPrice"], $row["eventStartDateTime"]);
				$events[$events->key()] = $event;
				$events->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw (new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($events);
	}


	/**
	 * gets the Event by event name
	 *
	 * @param \PDO $pdo $pdo PDO Connection object
	 * @param string $eventName event id to search for
	 * @return \SplFixedArray of all events found or null
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getEventByEventName(\PDO $pdo, string $eventName): \SplFixedArray {
		// sanitize the event name before searching
		$eventName = trim($eventName);
		$eventName = filter_var($eventName, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($eventName) === true) {
			throw (new \PDOException("not a valid event name"));
		}

		//create query template
		$query = "SELECT eventId, eventProfileId, eventAttendeeLimit, eventDetail, eventEndDateTime, eventImage, eventLat, eventLong, eventName, eventPrice, eventStartDateTime FROM event WHERE eventName LIKE :eventName";
		$statement = $pdo->prepare($query);
		// bind the event name to the placeholder in the template
		$parameters = ["eventName" => $eventName];
		$statement->execute($parameters);
		//build an array of events
		$events = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);

		while(($row = $statement->fetch()) !== false) {
			try {
				$event = new Event($row["eventId"], $row["eventProfileId"], $row ["eventAttendeeLimit"], $row["eventDetail"], $row["eventEndDateTime"], $row["eventImage"], $row["eventLat"], $row["eventLong"], $row["eventName"], $row["eventPrice"], $row["eventStartDateTime"]);
				$events[$events->key()] = $event;
				$events->next();

			} catch(\Exception $exception) {
				//if the row couldn't be converted, rethrow it
				throw (new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($events);
	}

	/**
	 * gets all Events
	 *
	 * @param \PDO $pdo PDO connection object
	 * @return \SplFixedArray SplFixedArray of Events found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getAllEvents(\PDO $pdo) : \SplFixedArray {
		// create query template
		$query = "SELECT eventId, eventProfileId, eventAttendeeLimit, eventDetail, eventEndDateTime, eventImage, eventLat, eventLong, eventName, eventPrice, eventStartDateTime FROM event";
		$statement = $pdo->prepare($query);
		$statement->execute();

		// build an array of events
		$events = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch())!== false) {
			try {
				$event = new Event($row ["eventId"], $row ["eventProfileId"], $row ["eventAttendeeLimit"], $row["eventDetail"], $row ["eventEndDateTime"], $row ["eventImage"], $row ["eventLat"], $row ["eventLong"], $row ["eventName"], $row ["eventPrice"], $row ["eventStartDateTime"]);
				$events[$events->key()] = $event;
				$events->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw (new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($events);
	}





	public function jsonSerialize() {
		$fields = get_object_vars($this);
		$fields["eventId"] = $this->eventId;
		$fields["eventProfileId"] = $this->eventProfileId;

		//format the date so that the front end can consume it
		$fields["eventStartDateTime"] = round(floatval($this->eventStartDateTime->format("U.u")) * 1000);


		//format the date so that the front end can consume it
		$fields["eventEndDateTime"] = round(floatval($this->eventEndDateTime->format("U.u")) * 1000);

		return ($fields);
	}

}
























