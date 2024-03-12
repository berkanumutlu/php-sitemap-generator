<?php namespace App\Library;

/**
 * @category   class
 * @package    SitemapGenerator
 * @author     Berkan Ümütlü (github.com/berkanumutlu)
 * @copyright  © 2023 Berkan Ümütlü
 * @version    1.0.0
 */
class SitemapGenerator
{
    /**
     * @var Sitemap
     */
    private $sitemap;
    /**
     * @var Response
     */
    private $response;
    /**
     * @var array
     */
    private $url_list = array();
    /**
     *[
     * 'loc'      => 'url',
     * 'lastmod'  => 'date',
     * 'priority' => 0.5
     * ]
     * @var array
     */
    private $url = array();
    /**
     * The date of last modification of the page. This date should be in W3C Datetime format.
     * This format allows you to omit the time portion, if desired, and use YYYY-MM-DD.
     *
     * Note that the date must be set to the date the linked page was last modified, not when the sitemap is generated.
     *
     * Note also that this tag is separate from the If-Modified-Since (304) header the server can return, and search engines may use the information from both sources differently.
     *
     * @var string
     */
    private $last_mod;
    /**
     * How frequently the page is likely to change.
     * This value provides general information to search engines and may not correlate exactly to how often they crawl the page.
     * Valid values are:
     *
     * always
     * hourly
     * daily
     * weekly
     * monthly
     * yearly
     * never
     *
     * The value "always" should be used to describe documents that change each time they are accessed.
     * The value "never" should be used to describe archived URLs.
     *
     * Please note that the value of this tag is considered a hint and not a command.
     * Even though search engine crawlers may consider this information when making decisions, they may crawl pages marked "hourly" less frequently than that, and they may crawl pages marked "yearly" more frequently than that.
     * Crawlers may periodically crawl pages marked "never" so that they can handle unexpected changes to those pages.
     *
     * @var string
     */
    private $change_freq = 'yearly';
    /**
     * The priority of this URL relative to other URLs on your site. Valid values range from 0.0 to 1.0.
     * This value does not affect how your pages are compared to pages on other sites—it only lets the search engines know which pages you deem most important for the crawlers.
     * The default priority of a page is 0.5.
     *
     * Please note that the priority you assign to a page is not likely to influence the position of your URLs in a search engine's result pages.
     * Search engines may use this information when selecting between URLs on the same site, so you can use this tag to increase the likelihood that your most important pages are present in a search index.
     *
     * Also, please note that assigning a high priority to all of the URLs on your site is not likely to help you. Since the priority is relative, it is only used to select between URLs on your site.
     *
     * @var float
     */
    private $priority = 0.5;

    public function __construct()
    {
        $this->sitemap = new Sitemap();
        $this->response = new Response();
    }

    /**
     * @return Sitemap
     */
    public function getSitemap()
    {
        return $this->sitemap;
    }

    /**
     * @return array
     */
    public function getUrllist()
    {
        return $this->url_list;
    }

    /**
     * @return void
     */
    public function add_url_to_list()
    {
        $this->url_list[] = $this->getUrl();
        $this->setUrl(array());
    }

    /**
     * @return array
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param  array  $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getLastMod()
    {
        if (empty($this->last_mod)) {
            $this->setLastMod(date('Y-m-d'));
        }
        return $this->last_mod;
    }

    /**
     * @param  string  $last_mod
     */
    public function setLastMod($last_mod)
    {
        $this->last_mod = $last_mod;
    }

    /**
     * @return string
     */
    public function getChangeFreq()
    {
        return $this->change_freq;
    }

    /**
     * @param  string  $change_freq
     */
    public function setChangeFreq($change_freq)
    {
        $this->change_freq = $change_freq;
    }

    /**
     * @return float
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param  float  $priority
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    /**
     * @return mixed|string
     */
    public function get_url_loc()
    {
        return isset($this->url['loc']) ? $this->url['loc'] : '';
    }

    /**
     * @param $url_loc
     * @return void
     */
    public function set_url_loc($url_loc)
    {
        if (strpos($url_loc, $this->getSitemap()->getDomain()) == false) {
            $url_loc .= $this->getSitemap()->getDomain().$url_loc;
        }
        $this->url['loc'] = $url_loc;
    }

    /**
     * @return mixed|string
     */
    public function get_url_last_mod()
    {
        return isset($this->url['lastmod']) ? $this->url['lastmod'] : '';
    }

    /**
     * @param $url_last_mod
     * @return void
     */
    public function set_url_last_mod($url_last_mod)
    {
        if (empty($url_last_mod)) {
            $url_last_mod = $this->getLastMod();
        }
        $this->url['lastmod'] = $url_last_mod;
    }

    /**
     * @return mixed|string
     */
    public function get_url_priority()
    {
        return isset($this->url['priority']) ? $this->url['priority'] : '';
    }

    /**
     * @param $url_priority
     * @return void
     */
    public function set_url_priority($url_priority)
    {
        $this->url['priority'] = $url_priority;
    }

    /**
     * @return void
     */
    public function set_urlset_body()
    {
        $url_list = $this->getUrllist();
        $data = '<!--created with PHP Sitemap Generator by Berkan Ümütlü (https://github.com/berkanumutlu/php-sitemap-generator)-->';
        if (!empty($url_list)) {
            foreach ($this->url_list as $url) {
                $item = (object) $url;
                $data .= '<url>';
                if (isset($item->loc)) {
                    $data .= '<loc>'.$item->loc.'</loc>';
                }
                if (isset($item->lastmod)) {
                    $data .= '<lastmod>'.$item->lastmod.'</lastmod>';
                }
                if (isset($item->priority)) {
                    $data .= '<priority>'.$item->priority.'</priority>';
                }
                $data .= '</url>';
            }
        }
        $this->sitemap->setUrlsetBody($data);
    }

    /**
     * @param $path
     * @return Response|true
     */
    public function create_file_path($path)
    {
        $this->response->setStatus(false);
        $dir = is_file($path) ? pathinfo($path, PATHINFO_DIRNAME) : $path;
        if (is_dir($dir)) {
            $this->response->setStatus(true);
        } else {
            if (mkdir($dir)) {
                chmod($dir, 0777);
                $this->response->setStatus(true);
            } else {
                $this->response->setMessage('The directory could not be created.<br>Date: <strong>'.$this->response->getDate().'</strong>');
            }
        }
        return $this->response;
    }

    /**
     * @param $file_name
     * @param $file_path
     * @param $file_ext
     * @param $file_data
     * @return Response
     */
    public function write($file_name, $file_path, $file_ext, $file_data)
    {
        $this->response->setStatus(false);
        $create_file_path = $this->create_file_path($file_path);
        $full_path = $file_path.$file_name.$file_ext;
        if ($create_file_path->isStatus()) {
            $path_info = pathinfo($full_path);
            $httpProtocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
            $domain = $_SERVER['HTTP_HOST'];
            $base_url = $httpProtocol.'://'.$domain;
            $file_url = $base_url.str_replace($_SERVER["DOCUMENT_ROOT"], '',
                    $path_info['dirname']).'/'.$path_info['basename'].'?v='.$this->response->getDate();
            file_put_contents($full_path, $file_data);
            if (file_exists($full_path)) {
                $this->response->setStatus(true);
                $this->response->setMessage('Sitemap file created successfully.<br>Date: <strong>'.$this->response->getDate().'</strong>,  File path: <a href="'.$file_url.'" target="_blank"><strong>'.$full_path.'</strong></a>');
            } else {
                $this->response->setMessage('Sitemap file could not write.<br>Date: <strong>'.$this->response->getDate().'</strong>');
            }
        } else {
            $this->response = $create_file_path;
        }
        return $this->response;
    }

    /**
     * @return Response
     */
    public function generate()
    {
        $file_path = $this->sitemap->getFilePath();
        $file_name = $this->sitemap->getFileName();
        $file_ext = $this->sitemap->getFileExt();
        $this->set_urlset_body();
        $file_data = $this->sitemap->getHeader().$this->sitemap->getUrlsetHeader().$this->sitemap->getUrlsetBody().$this->sitemap->getUrlsetFooter();
        return $this->write($file_name, $file_path, $file_ext, $file_data);
    }
}