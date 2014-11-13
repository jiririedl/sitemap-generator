<?php
namespace SitemapGenerator\Model;
use \RuntimeException;
/**
 * Path
 *
 * @author Jiri Riedl <riedl@dcommunity.org>
 * @package SitemapGenerator
 */
class Path
{
    /**
     * Real path (path on server file system)
     * @var string
     */
    protected $_realPath = NULL;
    /**
     * Path URL (accessable bz google robots)
     * @var string|NULL
     */
    protected $_pathURL = NULL;
    /**
     * Path
     * @param string $realPath
     * @param string $pathURL
     */
    public function __construct($realPath, $pathURL)
    {
        $this->setRealPath($realPath);
        $this->setPathURL($pathURL);
    }
    /**
     * Sets path URL
     * @param string $pathURL
     */
    public function setPathURL($pathURL)
    {
        $this->_pathURL = $pathURL;
    }
    /**
     * Returns path URL
     *
     * @throws RuntimeException
     * @return string
     */
    protected function _getPathURL()
    {
        if(is_null($this->_pathURL))
            throw new RuntimeException('There is no PathURL set - use setURLPath() first !');

        return $this->_pathURL;
    }
    /**
     * Sets real path
     *
     * @param string $realPath
     */
    public function setRealPath($realPath)
    {
        $this->_realPath = $realPath;
    }
    /**
     * Returns realpath
     *
     * @throws RuntimeException
     * @return string
     */
    protected function _getRealPath()
    {
        if(is_null($this->_realPath))
            throw new RuntimeException('There is no RealPath set - use setRealPath() first !');

        return $this->_realPath;
    }

}