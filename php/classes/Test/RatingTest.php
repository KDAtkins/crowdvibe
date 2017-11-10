<?php
namespace Edu\Cnm\CrowdVibe\Test;

use Edu\Cnm\CrowdVibe\{
    EventAttendance, Profile, Event, Rating
};

// grab the class under scrutiny
require_once(dirname(__DIR__) . "/autoload.php");

// grab the uuid generator
require_once(dirname(__DIR__, 2) . "/lib/uuid.php");

/**
 * Full PHPUnit test for the Rating class
 *
 * This is a complete PHPUnit test of the Rating class. It is complete because *ALL* mySQL/PDO enabled methods
 * are tested for both invalid and valid inputs.
 *
 * @see Rating
 * @author Matthew David <mcdav3636@gmail.com>
 **/

class RatingTest extends crowdvibeTest {

	/**
	 * Profile that created the Rating; this is for foreign key relation
	 * @var Profile $rater
	 **/
	protected $rater;


	/**
	 * Profile that receives the Rating; this is for foreign key relation
	 * @var Profile $ratee
	 **/
	protected $ratee;

    /**
     * Event that receives the Rating; this is a foreign key relation
     *@var Event $event
     **/
    protected $event;

    /**
     * EventAttendance that connects the Rating; this is a foreign key relation
     *@var EventAttendance $eventAttendance
     **/
    protected $eventAttendance;

	/**
	 * score of the Rating
	 * @var int $VALID_RATINGS_SCORE
	 **/
	protected $VALID_RATING_SCORE = 3;



    /**
     * create dependent objects before running each test
     **/
    public final function setUp() : void {
        //run the default setUp() method first
        parent::setUp();
        $password = "abc123";
        $validActivationToken = bin2hex(random_bytes(16));
        $eventEndDateTime = new \DateTime();
        $eventEndDateTime->sub(new \DateInterval("p5h"));
        $eventStartDateTime = new \DateTime();
        $eventStartDateTime->add(new \ DateInterval("p5h"));
        $SALT = bin2hex(random_bytes(32));
        $HASH = hash_pbkdf2("sha512", $password, $SALT, 262144);
        ;
        //create and insert a Rater to own the test Rating
        $this->rater = new Profile(generateUuidV4(), null, "i'm hugry", "breez@hometime.com", "Cheech", $HASH, null, "Maren", $SALT, "@sohigh");
        $this->rater->insert($this->getPDO());

        //create and insert a Ratee to own the test Rating
        $this->ratee = new Profile(generateUuidV4(),null, "I like eggs", "getsome@me.com", "tommy", $HASH, null, "chong", $SALT,"@smoke");
        $this->ratee->insert($this->getPDO());

        //create and insert a Event to own the test Rating
        $this->event = new Event(generateUuidV4(), $this->rater->getProfileId(), $eventEndDateTime, "fun fun fun", null, "35.084319", "-106.619781", "chris' 10th bithday", null, $eventStartDateTime);
        $this->event->insert($this->getPDO());

        //create and insert event attendance to be able to rate
        $this->eventAttendance = new eventAttendance(generateUuidV4(), $this->event->getEventId(),$this->rater->getProfileId(), "2", "15");


    }

    /**
     * test inserting a valid Rating and verify that the actual mySQL data matches
     **/
    public function testInsertValidRating() : void {
        //count number of rows and save for later
        $numRows = $this->getConnection()->getRowCoung("rating");

        //create a new Rating and insert into mySQL
        $rating = new Rating(generateUuidV4(),$this->eventAttendance->getEventAttendanceId(), $this->ratee->getProfileId(), $this->rater->getProfileId(),70);
        $rating->insert($this->getPDO());

        // grab the data from mySQL and enforce the fields match our expectations
        $pdoRating= Rating::getRatingByRatingId($this->getPDO(), $this->rating->getRatingId());
        $this->assertEquals($numRows + 1,$this->getConnection()->getRowCount("rating"));
        $this->assertEquals($pdoRating->getRatingId(),$ratingId);
        $this->assertEquals($pdoRating->getRatingEventAttendanceId(, $this->eventAttendance->getEventAttendanceId()))

    }
}