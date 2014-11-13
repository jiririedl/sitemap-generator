<?php
namespace SitemapGenerator\Model;
use \UnexpectedValueException;
/**
 * Sitemap
 *
 * @author Jiri Riedl <riedl@dcommunity.org>
 * @package SitemapGenerator
 */
class Sitemap
{
    /**
     * Sitemap full URL
     * @var string
     */
    protected $_location = NULL;
    /**
     * Date of last modification of the file in  W3C Datetime
     * @var string|NULL
     */
    protected $_lastModification = NULL;

    /**
     * Sitemap
     *
     * @param string $location URL of the page. This URL must begin with the protocol (such as http) and end with a trailing slash, if your web server requires it. This value must be less than 2,048 characters. !! DO NOT ESCAPE URL !! it will be escaped automaticaly
     * @param string|NULL $lastModification The date of last modification of the file. This date should be in W3C Datetime format. This format allows you to omit the time portion, if desired, and use YYYY-MM-DD.
     */
    public function __construct($location, $lastModification = NULL)
    {
        $this->setLocation($location);
        $this->setLastModification($lastModification);
    }
    /**
     * Sets location
     *
     * Identifies the location of the Sitemap.
     * This location can be a Sitemap, an Atom file, RSS file or a simple text file.
     *
     * @param string $location
     * @throws UnexpectedValueException
     */
    public function setLocation($location)
    {
        if(empty($location))
            throw new UnexpectedValueException('Sitemap location can\'t be empty. You have to set full URL address here, but you sets ['.var_export($location,true).']');

        $this->_location = $location;
    }
    /**
     * Sets last modification
     *
     * Identifies the time that the corresponding Sitemap file was modified. It does not correspond to the time that any of the pages listed in that Sitemap were changed.
     * The value for the lastmod tag should be in W3C Datetime format.
     * By providing the last modification timestamp, you enable search engine crawlers to retrieve only a subset of the Sitemaps in the index i.e. a crawler may only retrieve Sitemaps that were modified since a certain date. This incremental Sitemap fetching mechanism allows for the rapid discovery of new URLs on very large sites.
     *
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
}