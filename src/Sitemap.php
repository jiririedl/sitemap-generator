<?php
namespace SitemapGenerator;
use \RuntimeException;
/**
 * Sitemap generator
 *
 * generate URL sets (sitemaps) and  sitemap index into selected directory
 *
 * @author Jiri Riedl <riedl@dcommunity.org>
 * @package SitemapGenerator
 */
class Sitemap
{
    /**
     * Sitemap path
     * @var Model\Path|NULL
     */
    protected $_path = NULL;
    /**
     * Url set generator
     * @var UrlSetGenerator|NULL
     */
    protected $_urlSetGenerator = NULL;
    /**
     * Sitemap index file name
     * @var string
     */
    protected $_indexFileName = 'sitemap_index.xml';
    /**
     * Sitemap file base name
     * @var string
     */
    protected $_sitemapFileBaseName = 'sitemap';

    /**
     * Sets sitemap path
     *
     * @param string $realPath real path (on server f.e. /var/www/example.com/)
     * @param string $pathURL path url (accessible by crawlers f.e. http://www.example.com/)
     */
    public function setPath($realPath, $pathURL)
    {
        $object = new Model\Path($realPath,$pathURL);
        $this->setPathObject($object);
    }
    /**
     * Sets sitemap index file name
     * @param string $fileName
     */
    public function setIndexFileName($fileName = 'sitemap_index.xml')
    {
        $this->_indexFileName = $fileName;
    }
    /**
     * Sets sitemap file base name
     *
     * Sitemap file name is builded: $baseFileName + [ordinary_number] + [".gz"] + ".xml"
     * ordinary number is used only if more than one sitemap is generated,
     * ".gz" is used if sitemap file is gzipped
     *
     * @param string $baseFileName
     */
    public function setSitemapFileBaseName($baseFileName = 'sitemap')
    {
        $this->_sitemapFileBaseName = $baseFileName;
    }
    /**
     * Sets path by model object
     *
     * @param Model\Path $path
     */
    public function setPathObject(Model\Path $path)
    {
        $this->_path = $path;
    }
    /**
     * Returns path
     *
     * @return Model\Path
     * @throws RuntimeException
     */
    protected function _getPath()
    {
        if(is_null($this->_path))
            throw new RuntimeException("There is no path set - use setPath() first!");

        return $this->_path;
    }
    /**
     * Returns URL set generator instance
     *
     * @return UrlSetGenerator
     */
    protected function _getURLSetGenerator()
    {
        if(is_null($this->_urlSetGenerator))
            $this->_urlSetGenerator = new UrlSetGenerator();
        return $this->_urlSetGenerator;
    }
    /**
     * Returns sitemap filename
     *
     * @param number|null $orderNumber
     * @return string
     */
    protected function _getFileName($orderNumber = NULL)
    {
        return $this->_sitemapFileBaseName.(is_null($orderNumber)?'':$orderNumber).'.xml';
    }
}
