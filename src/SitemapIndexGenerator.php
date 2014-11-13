<?php
namespace SitemapGenerator;
use \RuntimeException;
/**
 * URL set generator
 *
 * generate URL sets (sitemaps) from given URL records
 *
 * @author Jiri Riedl <riedl@dcommunity.org>
 * @package SitemapGenerator
 */
class SitemapIndexGenerator
{
    /**
     * @var Model\Sitemap[]
     */
    protected $_sitemaps = array();

    /**
     * Adds sitemap
     *
     * @param Model\Sitemap $sitemap
     */
    public function addSitemap(Model\Sitemap $sitemap)
    {
        $this->_sitemaps[] = $sitemap;
    }
    /**
     * Builds sitemap index
     *
     * @throws RuntimeException
     * @return string
     */
    public function getXML()
    {
        if(count($this->_getSitemaps()) <1 )
            throw new RuntimeException('There is no sitemap added. Use addSitemap() first!');

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n".'<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

        foreach($this->_getSitemaps() as $sitemap)
            $xml.= $this->_getSitemapElement($sitemap);

        $xml = "\n".'</sitemapindex>';

        return $xml;
    }
    /**
     * Returns sitemaps
     *
     * @return Model\Sitemap[]
     */
    protected function _getSitemaps()
    {
        return $this->_sitemaps;
    }
    /**
     * Builds xml element "sitemap"
     *
     * @param Model\Sitemap $sitemap
     * @return string
     */
    protected function _getSitemapElement(Model\Sitemap $sitemap)
    {
        $xml = "    <sitemap> \n";
        $xml .= "       <loc>".$sitemap->getLocation()."</loc> \n";
        if(!is_null($sitemap->getLastModification()))
            $xml .= "       <lastmod>".$sitemap->getLastModification()."</lastmod> \n";
        $xml .= "    </sitemap> \n";
        return $xml;
    }
}
