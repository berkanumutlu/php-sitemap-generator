<?php namespace App\Library;

/**
 * @category   class
 * @package    Sitemap
 * @author     Berkan Ümütlü (github.com/berkanumutlu)
 * @copyright  © 2023 Berkan Ümütlü
 * @version    1.0.0
 * @see        https://www.sitemaps.org/protocol.html
 * @see        https://developers.google.com/search/docs/crawling-indexing/sitemaps/overview
 */
class Sitemap
{
    /**
     * @var string
     */
    private $domain = 'example.com';
    /**
     * http or https
     * @var bool
     */
    private $http_secure = false;
    /**
     * @var string
     */
    private $file_path = 'sitemap/';
    /**
     * @var string
     */
    private $file_name = 'sitemap';
    /**
     * @var string
     */
    private $file_ext = '.xml';
    /**
     * @var string
     */
    private $header = '<?xml version="1.0" encoding="UTF-8"?>';
    /**
     * Encapsulates the file and references the current protocol standard.
     *
     * @var string
     */
    private $urlset_header = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';
    /**
     * @var string
     */
    private $urlset_body = '';
    /**
     * @var string
     */
    private $urlset_footer = '</urlset>';

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->check_domain_http();
    }

    /**
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param  string  $domain
     */
    public function setDomain($domain)
    {
        $this->domain = $this->check_domain_http($domain);
    }

    /**
     * @return bool
     */
    public function isHttpSecure()
    {
        return $this->http_secure;
    }

    /**
     * @param  bool  $http_secure
     */
    public function setHttpSecure($http_secure)
    {
        $this->http_secure = $http_secure;
    }

    /**
     * @return string
     */
    public function getFilePath()
    {
        return $this->file_path;
    }

    /**
     * @param  string  $file_path
     */
    public function setFilePath($file_path)
    {
        if (mb_substr($file_path, -1) !== '/') {
            $file_path .= '/';
        }
        $this->file_path = $file_path;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->file_name;
    }

    /**
     * @param  string  $file_name
     */
    public function setFileName($file_name)
    {
        $this->file_name = $file_name;
    }

    /**
     * @return string
     */
    public function getFileExt()
    {
        return $this->file_ext;
    }

    /**
     * @param  string  $file_ext
     */
    public function setFileExt($file_ext)
    {
        $this->file_ext = $file_ext;
    }

    /**
     * @return string
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @param  string  $header
     */
    public function setHeader($header)
    {
        $this->header = $header;
    }

    /**
     * @return string
     */
    public function getUrlsetHeader()
    {
        return $this->urlset_header;
    }

    /**
     * @param  string  $urlset_header
     */
    public function setUrlsetHeader($urlset_header)
    {
        $this->urlset_header = $urlset_header;
    }

    /**
     * @return string
     */
    public function getUrlsetBody()
    {
        return $this->urlset_body;
    }

    /**
     * @param  string  $urlset_body
     */
    public function setUrlsetBody($urlset_body)
    {
        $this->urlset_body = $urlset_body;
    }

    /**
     * @return string
     */
    public function getUrlsetFooter()
    {
        return $this->urlset_footer;
    }

    /**
     * @param  string  $urlset_footer
     */
    public function setUrlsetFooter($urlset_footer)
    {
        $this->urlset_footer = $urlset_footer;
    }

    /**
     * @param $domain
     * @return mixed|string|null
     */
    private function check_domain_http($domain = null)
    {
        if (empty($domain)) {
            $domain = $this->getDomain();
        }
        if (strpos($domain, 'http') === false) {
            $http_secure = $this->isHttpSecure();
            $http = $http_secure ? 'https://' : 'http://';
            $domain = $http.$domain;
            $this->setDomain($domain);
        }
        return $domain;
    }
}