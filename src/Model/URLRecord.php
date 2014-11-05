<?php
namespace SitemapGenerator\Model;
use \UnexpectedValueException;
/**
 * Sitemap URL record
 *
 * @author Jiri Riedl <riedl@dcommunity.org>
 * @package SitemapGenerator
 */
class URLRecord
{
    CONST CHANGE_FREQUENCY_ALWAYS = 'always';
    CONST CHANGE_FREQUENCY_HOURLY = 'hourly';
    CONST CHANGE_FREQUENCY_DAILY = 'daily';
    CONST CHANGE_FREQUENCY_WEEKLY = 'weekly';
    CONST CHANGE_FREQUENCY_MONTHLY = 'monthly';
    CONST CHANGE_FREQUENCY_YEARLY = 'yearly';
    CONST CHANGE_FREQUENCY_NEVER = 'never';
    /**
     * Page full URL
     * @var string
     */
    protected $_location = NULL;
    /**
     * Date of last modification of the file in  W3C Datetime
     * @var string|NULL
     */
    protected $_lastModification = NULL;
    /**
     * Change frequency - CHANGE_FREQUENCY_*
     * @var string|NULL
     */
    protected $_changeFrequency = NULL;
    /**
     * Page priority 0.0 - 1.0
     * @var float|NULL
     */
    protected $_priority = NULL;
    /**
     * Static cache for change frequency constants names
     * @var array
     */
    static protected $_changeFrequencyConstants = array();

    /**
     * Sitemap URL record
     *
     * @param string $location URL of the page. This URL must begin with the protocol (such as http) and end with a trailing slash, if your web server requires it. This value must be less than 2,048 characters. !! DO NOT ESCAPE URL !! it will be escaped automaticaly
     * @param string|NULL $lastModification The date of last modification of the file. This date should be in W3C Datetime format. This format allows you to omit the time portion, if desired, and use YYYY-MM-DD.
     * @param string|NULL $changeFrequency self::CHANGE_FREQUENCY_* How frequently the page is likely to change. This value provides general information to search engines and may not correlate exactly to how often they crawl the page
     * @param float|NULL $priority The priority of this URL relative to other URLs on your site. Valid values range from 0.0 to 1.0. This value does not affect how your pages are compared to pages on other sites—it only lets the search engines know which pages you deem most important for the crawlers.
     */
    public function __construct($location, $lastModification = NULL, $changeFrequency = NULL, $priority = NULL)
    {
        $this->setLocation($location);
        $this->setLastModification($lastModification);
        $this->setChangeFrequency($changeFrequency);
        $this->setPriority($priority);
    }
    /**
     * Sets location
     * URL of the page. This URL must begin with the protocol (such as http) and end with a trailing slash, if your web server requires it. This value must be less than 2,048 characters.
     *
     * !! DO NOT ESCAPE URL !! it will be escaped automaticaly
     *
     * @param string $location
     * @throws UnexpectedValueException
     */
    public function setLocation($location)
    {
        if(empty($location))
            throw new UnexpectedValueException('Location can\'t be empty. You have to set full URL address here, but you sets ['.var_export($location,true).']');

        $this->_location = $location;
    }
    /**
     * Sets last modification
     *
     * The date of last modification of the file. This date should be in W3C Datetime format. This format allows you to omit the time portion, if desired, and use YYYY-MM-DD.
     * If date is in other format, will be automaticaly reformatted.
     * @link http://www.w3.org/TR/NOTE-datetime
     *
     * @param string|NULL $lastModification W3C Datetime format YYYY-MM-DD
     */
    public function setLastModification($lastModification)
    {
        if(is_null($lastModification))
        {
            $this->_lastModification = $lastModification;
        }
        else
        {
            if(preg_match('/^([1-2][0-9]{3})-(0[1-9]|1[0-2])-([0-2][1-9]|3[0-1])$/', $lastModification))
                $this->_lastModification = $lastModification;
            else
                $this->_lastModification = date("Y-m-d",strtotime($lastModification));
        }

    }
    /**
     * Sets change frequency
     *
     * How frequently the page is likely to change. This value provides general information to search engines and may not correlate exactly to how often they crawl the page
     *
     * @param string|NULL $changeFrequency self::CHANGE_FREQUENCY_*
     * @throws UnexpectedValueException
     */
    public function setChangeFrequency($changeFrequency)
    {
        if(!is_null($changeFrequency) && !in_array($changeFrequency,self::$_changeFrequencyConstants))
            throw new UnexpectedValueException('Change frequency value have to be one of constants self::CHANGE_FREQUENCY_* or NULL');

        $this->_changeFrequency = $changeFrequency;
    }
    /**
     * Sets url priority
     *
     * The priority of this URL relative to other URLs on your site. Valid values range from 0.0 to 1.0. This value does not affect how your pages are compared to pages on other sites—it only lets the search engines know which pages you deem most important for the crawlers.
     * The default priority of a page is 0.5.
     * @param float|NULL $priority
     * @throws UnexpectedValueException
     */
    public function setPriority($priority)
    {
        if(is_null($priority))
            $this->_priority = $priority;
        else
        {
            if(!is_numeric($priority))
                throw new UnexpectedValueException('Priority value have to be number in interval 0.0-1.0 or NULL');

            $doubledPriority = doubleval($priority);
            if($doubledPriority < 0.0 || $doubledPriority > 1.0)
                throw new UnexpectedValueException('Priority value have to be number in interval 0.0-1.0 or NULL number out of interval given ['.$priority.']');

            $this->_priority = $doubledPriority;
        }
    }
    /**
     * Returns location
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->_location;
    }
    /**
     * Returns last modification
     * Data are returned in format: YYYY-MM-DD (W3C Datetime)
     *
     * @return NULL|string
     */
    public function getLastModification()
    {
        return $this->_lastModification;
    }
    /**
     * Returns change frequency
     *
     * frequency is returned
     * @return NULL|string
     */
    public function getChangeFrequency()
    {
       return $this->_changeFrequency;
    }
    /**
     * Returns priority
     *
     * priority is returned in interval 0.0-1.0
     * @return float|NULL
     */
    public function getPriority()
    {
        return $this->_priority;
    }
    /**
     * Initialize static cache
     *
     * cache frequency constants
     */
    static function init()
    {
        $reflection = new \ReflectionClass(\get_class());
        $changeFrequencyConstants = array();
        foreach($reflection->getConstants() as $constantName => $constantValue)
        {
            if(substr($constantName, 0, strlen('CHANGE_FREQUENCY_')) == 'CHANGE_FREQUENCY_')
                $changeFrequencyConstants[$constantName] = $constantValue;
        }
        self::$_changeFrequencyConstants = $changeFrequencyConstants;
    }
}
URLRecord::init();