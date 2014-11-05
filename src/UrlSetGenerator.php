<?php
namespace SitemapGenerator;
/**
 * URL set generator
 *
 * generate URL sets (sitemaps) from given URL records
 *
 * @author Jiri Riedl <riedl@dcommunity.org>
 * @package SitemapGenerator
 */
class UrlSetGenerator
{
    /**
     * Max count multiplier
     * if count of records reaches MAX_URLRECORD_COUNT * MAX_URLRECORD_COUNT_MULTIPLIER new url set is opened
     */
    CONST MAX_URLRECORD_COUNT_MULTIPLIER = 0.9;
    /**
     * Max URLset size multiplier
     * if size of URI records reaches MAX_URLSET_SIZE_KB * MAX_URLSET_SIZE_MULTIPLIER new url set is opened
     */
    CONST MAX_URLSET_SIZE_MULTIPLIER = 0.9;
    /**
     * Maximal URLs in one URL set
     * @see http://www.sitemaps.org/protocol.html
     */
    CONST MAX_URLRECORD_COUNT = 50000;
    /**
     * Maximal sitemap size in kilobytes
     * @see http://www.sitemaps.org/protocol.html
     */
    CONST MAX_URLSET_SIZE_KB = 50000;
    /**
     * URL records
     * @var Model\URLRecord[]
     */
    protected $_url = array();
    /**
     * Adds an url record
     * @param Model\URLRecord $url
     */
    public function addURL(Model\URLRecord $url)
    {
        $this->_url[] = $url;
    }
    /**
     * Builds sitemaps
     *
     * returns array of generated urlsets (each represents one sitemap file)
     * @return array
     */
    public function getSitemaps()
    {
        $urlSets = array();
        $maxFileSize = (self::MAX_URLSET_SIZE_KB  * self::MAX_URLSET_SIZE_MULTIPLIER * 1024);
        $maxRecordsCount = self::MAX_URLRECORD_COUNT * self::MAX_URLRECORD_COUNT_MULTIPLIER;

        $actualSetFileSize = 0;
        $actualURLSet = 0;
        $setsCount = 0;
        foreach($this->_url as $url)
        {
            $xml = $this->_urlToXML($url);
            $urlSets[$actualURLSet][] = $xml;

            $actualSetFileSize += strlen($xml);
            $setsCount++;

            if($actualSetFileSize >= $maxFileSize || $setsCount>= $maxRecordsCount)
            {
                $actualSetFileSize = 0;
                $setsCount = 0;
                $actualURLSet++;
            }
        }
        $fullURLSets = array();
        foreach($urlSets as $urlSet)
            $fullURLSets[] = $this->_mergeURLRecordCodesToURLSet($urlSet);

        return $fullURLSets;
    }
    /**
     * Merges given record codes into url set structure
     *
     * returns full xml structure of url set in string
     *
     * @param array $urlRecordCodes
     * @return string
     */
    protected function _mergeURLRecordCodesToURLSet(array $urlRecordCodes)
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml.= ' <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

        foreach($urlRecordCodes as $record)
            $xml.= "\n".$record;

        $xml.="\n".' </urlset>';
        return $xml;
    }
    /**
     * Converts URL record to XML element
     *
     * @param Model\URLRecord $url
     * @return string
     */
    protected function _urlToXML(Model\URLRecord $url)
    {
        $xmlString = '        <url>
            <loc>'.htmlspecialchars($url->getLocation()).'</loc>';

        $xmlString.= $this->_getXMLTag('lastmod',$url->getLastModification());
        $xmlString.= $this->_getXMLTag('changefreq',$url->getChangeFrequency());
        $xmlString.= $this->_getXMLTag('priority',$url->getPriority());

        $xmlString.="\n".'        </url>';
        return $xmlString;
    }
    /**
     * Returns XML tag in plain text
     *
     * NULL values are ignored
     *
     * @param string $tagName
     * @param string|NULL $value
     * @return string
     */
    protected function _getXMLTag($tagName, $value)
    {
        if(is_null($value))
            return '';
        return '
        <'.$tagName.'>'.$value.'</'.$tagName.'>';
    }
}
