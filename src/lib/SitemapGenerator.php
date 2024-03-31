<?php namespace App\Library;

/**
 * @category   class
 * @package    SitemapGenerator
 * @author     Berkan Ümütlü (github.com/berkanumutlu)
 * @copyright  © 2023 Berkan Ümütlü
 * @version    1.0.2
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
     * @var string|null
     */
    private $base_url;
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
     * [
     *  'loc'   => 'image_url',
     *  'title' => 'Image Title'
     * ]
     * @var array
     */
    private $url_image = array();
    /**
     * @var int
     */
    private $url_limit = 50000;
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
     * Also, please note that assigning a high priority to all the URLs on your site is not likely to help you. Since the priority is relative, it is only used to select between URLs on your site.
     *
     * @var float
     */
    private $priority = 0.5;
    /**
     * @var string[]
     */
    private $search_engine_list = [
        "https://www.googleapis.com/webmasters/v3/sites/{site_url}/sitemaps/{sitemap_url}",
        "https://www.bing.com/webmaster/ping.aspx?siteMap={sitemap_url}",
        "https://search.yahooapis.com/SiteExplorerService/V1/ping?sitemap={sitemap_url}",
        "https://submissions.ask.com/ping?sitemap={sitemap_url}"
    ];
    /**
     * @var bool
     */
    private $create_gzip_file = false;
    /**
     * @var bool
     */
    private $create_robots_txt = false;

    public function __construct()
    {
        $this->sitemap = new Sitemap();
        $this->response = new Response();
        $this->base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http").'://'.$_SERVER['HTTP_HOST'];
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
        if ($this->getUrlImage()) {
            $this->url['image'] = $this->getUrlImage();
        }
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
     * @return array
     */
    public function getUrlImage()
    {
        return $this->url_image;
    }

    /**
     * @param  array  $url_image
     */
    public function setUrlImage($url_image)
    {
        $this->url_image = $url_image;
    }

    /**
     * @return int
     */
    public function getUrlLimit()
    {
        return $this->url_limit;
    }

    /**
     * @param  int  $url_limit
     */
    public function setUrlLimit($url_limit)
    {
        $this->url_limit = $url_limit;
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
     * @return string[]
     */
    public function getSearchEngineList()
    {
        return $this->search_engine_list;
    }

    /**
     * @param  string[]  $search_engine_list
     */
    public function setSearchEngineList($search_engine_list)
    {
        $this->search_engine_list = $search_engine_list;
    }

    /**
     * @return bool
     */
    public function isCreateGzipFile()
    {
        return $this->create_gzip_file;
    }

    /**
     * @param  bool  $create_gzip_file
     */
    public function setCreateGzipFile($create_gzip_file)
    {
        $this->create_gzip_file = $create_gzip_file;
    }

    /**
     * @return bool
     */
    public function isCreateRobotsTxt()
    {
        return $this->create_robots_txt;
    }

    /**
     * @param  bool  $create_robots_txt
     */
    public function setCreateRobotsTxt($create_robots_txt)
    {
        $this->create_robots_txt = $create_robots_txt;
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
        if (!strpos($url_loc, $this->getSitemap()->getDomain())) {
            $url_loc = $this->getSitemap()->getDomain().'/'.$url_loc;
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
     * @param $url_image_loc
     * @return void
     */
    public function set_url_image_loc($url_image_loc)
    {
        if (!strpos($url_image_loc, $this->getSitemap()->getDomain())) {
            $url_image_loc = $this->getSitemap()->getDomain().'/'.$url_image_loc;
        }
        $this->url_image['loc'] = $url_image_loc;
    }

    /**
     * @param $url_image_title
     * @return void
     */
    public function set_url_image_title($url_image_title)
    {
        $this->url_image['title'] = $url_image_title;
    }

    /**
     * @param  array  $url_list
     * @return void
     */
    public function set_urlset_body(array $url_list = array())
    {
        if (empty($url_list)) {
            $url_list = $this->getUrllist();
        }
        $data = '<!--Created with PHP Sitemap Generator by Berkan Ümütlü (https://github.com/berkanumutlu/php-sitemap-generator)-->';
        if (!empty($url_list)) {
            foreach ($url_list as $url) {
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
                if (isset($item->image)) {
                    $data .= '<image:image>';
                    if (isset($item->image['loc'])) {
                        $data .= '<image:loc>'.$item->image['loc'].'</image:loc>';
                    }
                    if (isset($item->image['title'])) {
                        $data .= '<image:title>'.$item->image['title'].'</image:title>';
                    }
                    $data .= '</image:image>';
                }
                $data .= '</url>';
            }
        }
        $this->sitemap->setUrlsetBody($data);
    }

    /**
     * @param $path
     * @return Response
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
                $this->response->setMessage('The directory could not be created.<br>Date: <strong>'.$this->response->getDate().'</strong>, Dir path: <strong>'.$dir.'</strong>');
            }
        }
        return $this->response;
    }

    /**
     * @param $file_name
     * @param $file_path
     * @param $file_ext
     * @param $index_path
     * @return Response
     */
    public function create_sitemap_index($file_name, $file_path, $file_ext, $index_path)
    {
        $this->response->setStatus(false);
        $sitemap_list = scandir($index_path);
        if (!empty($sitemap_list) && count($sitemap_list) > 2) {
            $sitemap_index_header = '<?xml version="1.0" encoding="UTF-8"?><sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<!--Created with PHP Sitemap Generator by Berkan Ümütlü (https://github.com/berkanumutlu/php-sitemap-generator)-->';
            $sitemap_index_footer = '</sitemapindex>';
            $sitemap_index_content = '';
            $sitemap_file_url = $this->getSitemap()->getDomain().str_replace($_SERVER["DOCUMENT_ROOT"], '',
                    $index_path);
            foreach ($sitemap_list as $sitemap_file) {
                if ($sitemap_file === '.' || $sitemap_file === '..') {
                    continue;
                }
                $sitemap_file_path_info = pathinfo($sitemap_file);
                if ($sitemap_file_path_info['extension'] == 'xml') {
                    $sitemap_index_content .= '<sitemap>
                            <loc>'.$sitemap_file_url.$sitemap_file.'</loc>
                            <lastmod>'.date('Y-m-d', filectime($index_path.$sitemap_file)).'</lastmod>';
                    if ($this->getPriority()) {
                        $sitemap_index_content .= '<priority>'.$this->getPriority().'</priority>';
                    }
                    $sitemap_index_content .= '</sitemap>';
                }
            }
            $sitemap_index_file_data = $sitemap_index_header.$sitemap_index_content.$sitemap_index_footer;
            $this->response = $this->write($file_name, $file_path, $file_ext, $sitemap_index_file_data);
        } else {
            $this->response->setMessage('Sitemap index files not found.<br>Date: <strong>'.$this->response->getDate().'</strong>, Sitemap index dir: <strong>'.$index_path.'</strong>');
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
        $sitemap_file_path = $file_path.$file_name.$file_ext;
        if ($create_file_path->isStatus()) {
            $result = file_put_contents($sitemap_file_path, $file_data);
            $date = $this->response->getDate();
            if (!empty($result)) {
                $this->response->setStatus(true);
                $sitemap_file_path_info = pathinfo($sitemap_file_path);
                $sitemap_file_url = $this->base_url.str_replace($_SERVER["DOCUMENT_ROOT"], '',
                        $sitemap_file_path_info['dirname']).'/'.$sitemap_file_path_info['basename'];
                if ($this->isCreateGzipFile()) {
                    $sitemap_file_url .= '.gz';
                }
                $sitemap_file_url .= '?v='.urlencode($date);
                $this->response->setMessage('Sitemap file created successfully.<br>Date: <strong>'.$date.'</strong>, File path: <a href="'.$sitemap_file_url.'" target="_blank"><strong>'.$sitemap_file_path.'</strong></a>');
                $this->response->setData(['file_url' => $sitemap_file_url]);
            } else {
                $this->response->setMessage('Sitemap file could not write.<br>Date: <strong>'.$date.'</strong>, File path: <strong>'.$sitemap_file_path.'</strong>');
            }
        } else {
            $this->response = $create_file_path;
        }
        return $this->response;
    }

    /**
     * @param $file_name
     * @param $file_path
     * @param $file_ext
     * @param $file_data
     * @return void
     */
    public function write_gzip_file($file_name, $file_path, $file_ext, $file_data)
    {
        $gzip_file_path = $file_path.$file_name.$file_ext.'.gz';
        $gzip = gzopen($gzip_file_path, 'w');
        gzwrite($gzip, $file_data);
        $result = gzclose($gzip);
        $date = $this->response->getDate();
        if ($result) {
            $gzip_file_path_info = pathinfo($gzip_file_path);
            $gzip_file_url = $this->base_url.str_replace($_SERVER["DOCUMENT_ROOT"], '',
                    $gzip_file_path_info['dirname']).'/'.$gzip_file_path_info['basename'].'?v='.urlencode($date);
            $message = 'Sitemap gzip file created successfully.<br>Date: <strong>'.$date.'</strong>, Gzip File path: <a href="'.$gzip_file_url.'" target="_blank"><strong>'.$gzip_file_path.'</strong></a>';
        } else {
            $message = 'Sitemap gzip file could not write.<br>Date: <strong>'.$date.'</strong>, Gzip File path: <strong>'.$gzip_file_path.'</strong>';
        }
        $response_message = $this->response->getMessage();
        $this->response->setMessage($response_message.'<br><br>'.$message);
    }

    /**
     * @param $file_name
     * @param $folder_path
     * @param $file_ext
     * @return void
     */
    public function write_gzip_files($file_name, $folder_path, $file_ext)
    {
        $gzip_file_path = $folder_path.$file_name.$file_ext.'.gz';
        $sitemap_index_header = '<?xml version="1.0" encoding="UTF-8"?><sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        $sitemap_index_footer = '</sitemapindex>';
        $gzip_file_content = '';
        $gzip = gzopen($gzip_file_path, 'w9');
        $files = scandir($folder_path);
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            $folder_file_path = $folder_path.$file;
            if (is_dir($folder_file_path)) {
                $folder_files = scandir($folder_file_path);
                foreach ($folder_files as $folder_file) {
                    if ($folder_file === '.' || $folder_file === '..') {
                        continue;
                    }
                    $folder_file_path_info = pathinfo($folder_file);
                    if ($folder_file_path_info['extension'] == 'gz') {
                        $file_url = $this->base_url.str_replace($_SERVER["DOCUMENT_ROOT"], '',
                                $folder_file_path).'/'.$folder_file;
                        $gzip_file_content .= '<sitemap>
                                <loc>'.$file_url.'</loc>
                                <lastmod>'.date('Y-m-d', filectime($folder_file_path.'/'.$folder_file)).'</lastmod>
                        </sitemap>';
                    }
                }
            }
        }
        gzwrite($gzip, $sitemap_index_header.$gzip_file_content.$sitemap_index_footer);
        $result = gzclose($gzip);
        $date = $this->response->getDate();
        if ($result) {
            $gzip_file_path_info = pathinfo($gzip_file_path);
            $gzip_file_url = $this->base_url.str_replace($_SERVER["DOCUMENT_ROOT"], '',
                    $gzip_file_path_info['dirname']).'/'.$gzip_file_path_info['basename'].'?v='.urlencode($date);
            $message = 'Sitemap gzip files created successfully.<br>Date: <strong>'.$date.'</strong>, Gzip Files path: <a href="'.$gzip_file_url.'" target="_blank"><strong>'.$gzip_file_path.'</strong></a>';
        } else {
            $message = 'Sitemap gzip files could not write.<br>Date: <strong>'.$date.'</strong>, Gzip Files path: <strong>'.$gzip_file_path.'</strong>';
        }
        $response_message = $this->response->getMessage();
        $this->response->setMessage($response_message.'<br><br>'.$message);
    }

    /**
     * @return Response
     */
    public function generate()
    {
        $create_sitemap_index = false;
        $file_path = $this->sitemap->getFilePath();
        $file_name = $this->sitemap->getFileName();
        $file_ext = $this->sitemap->getFileExt();
        $url_limit = $this->getUrlLimit();
        /*
         * If url limit is not 0 (zero)
         */
        if (!empty($url_limit)) {
            $url_list = $this->getUrllist();
            $url_list_chunk = array_chunk($url_list, $url_limit);
            /*
             * If there is more than 1 file, a sitemap index will be created
             */
            if (count($url_list_chunk) > 1) {
                $create_sitemap_index = true;
                $index_file_path = $file_path.'index/';
                $i = 1;
                foreach ($url_list_chunk as $list) {
                    $this->set_urlset_body($list);
                    $index_file_data = $this->sitemap->getHeader().$this->sitemap->getUrlsetHeader().$this->sitemap->getUrlsetBody().$this->sitemap->getUrlsetFooter();
                    $index_file_name = $file_name.'-'.$i;
                    $this->response = $this->write($index_file_name, $index_file_path, $file_ext, $index_file_data);
                    if (!$this->response->isStatus()) {
                        break;
                    }
                    if ($this->isCreateGzipFile()) {
                        $this->write_gzip_file($index_file_name, $index_file_path, $file_ext, $index_file_data);
                    }
                    $i++;
                }
                if ($this->response->isStatus()) {
                    $this->response = $this->create_sitemap_index($file_name, $file_path, $file_ext, $index_file_path);
                    if ($this->isCreateGzipFile()) {
                        $this->write_gzip_files($file_name, $file_path, $file_ext);
                    }
                }
            }
        }
        if (!$create_sitemap_index) {
            $this->set_urlset_body();
            $file_data = $this->sitemap->getHeader().$this->sitemap->getUrlsetHeader().$this->sitemap->getUrlsetBody().$this->sitemap->getUrlsetFooter();
            $this->response = $this->write($file_name, $file_path, $file_ext, $file_data);
            if ($this->isCreateGzipFile()) {
                $this->write_gzip_file($file_name, $file_path, $file_ext, $file_data);
            }
        }
        if ($this->isCreateRobotsTxt()) {
            $this->create_robots_txt($file_name, $file_path, $file_ext);
        }
        return $this->response;
    }

    /**
     * @param $sitemap_url
     * @return Response
     */
    public function submit_sitemap($sitemap_url)
    {
        $this->response->setStatus(false);
        if (!extension_loaded('curl')) {
            $this->response->setMessage('cURL library is not loaded.');
            return $this->response;
        }
        $search_engine_list = $this->getSearchEngineList();
        if (!empty($search_engine_list)) {
            $response_list = array();
            $site_url = str_replace(['http://', 'https://'], ['', ''], $this->getSitemap()->getDomain());
            $sitemap_url = urlencode($sitemap_url);
            foreach ($search_engine_list as $search_engine_url) {
                $search_engine_url = str_replace('{site_url}', $site_url, $search_engine_url);
                $search_engine_url = str_replace('{sitemap_url}', $sitemap_url, $search_engine_url);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $search_engine_url.$sitemap_url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                curl_close($ch);
                $response_list[] = [
                    'url'      => $search_engine_url,
                    'response' => $response
                ];
            }
            $this->response->setStatus(true);
            $this->response->setMessage('Submitting sitemap completed.');
            $this->response->setData($response_list);
        } else {
            $this->response->setMessage('Search engine list empty.');
        }
        return $this->response;
    }

    /**
     * More information about robots.txt: https://www.robotstxt.org/robotstxt.html
     * @param $file_name
     * @param $file_path
     * @param $file_ext
     * @return void
     */
    public function create_robots_txt($file_name, $file_path, $file_ext)
    {
        $previous_dir = dirname($file_path).'/';
        $robots_txt_file = $previous_dir.'robots.txt';
        $robots_txt_file_content = "# Created with PHP Sitemap Generator by Berkan Umutlu (https://github.com/berkanumutlu/php-sitemap-generator)";
        /*
         * If the robots.txt file exists, its content is retrieved and only the URL value starting with "Sitemap:" is changed.
         * Else creating a new robots.txt file
         */
        if (file_exists($robots_txt_file)) {
            $current_robots_txt_file = explode("\n", file_get_contents($robots_txt_file));
            foreach ($current_robots_txt_file as $key => $value) {
                if (substr($value, 0, 8) == 'Sitemap:') {
                    unset($current_robots_txt_file[$key]);
                } elseif ($value == $robots_txt_file_content) {
                    $robots_txt_file_content .= "\n";
                } else {
                    $robots_txt_file_content .= $value."\n";
                }
            }
        } else {
            $robots_txt_file_content .= "\n\nUser-agent: *\nAllow: /\n";
        }
        /*
         * Adding sitemap file url
         */
        $sitemap_file_path = $file_path.$file_name.$file_ext;
        $sitemap_file_path_info = pathinfo($sitemap_file_path);
        $sitemap_file_url = $this->base_url.str_replace($_SERVER["DOCUMENT_ROOT"], '',
                $sitemap_file_path_info['dirname']).'/'.$sitemap_file_path_info['basename'];
        if ($this->isCreateGzipFile()) {
            $sitemap_file_url .= '.gz';
        }
        $date = $this->response->getDate();
        $sitemap_file_url .= '?v='.urlencode($date);
        $robots_txt_file_content .= "Sitemap: $sitemap_file_url";
        if (!file_exists($robots_txt_file)) {
            $robots_txt_file_content .= "Sitemap: $sitemap_file_url\n";
        }
        /*
         * Writing robots.txt file contents
         */
        $result = file_put_contents($robots_txt_file, $robots_txt_file_content);
        if (!empty($result)) {
            $robots_txt_file_path_info = pathinfo($robots_txt_file);
            $robots_txt_file_url = $this->base_url.str_replace($_SERVER["DOCUMENT_ROOT"], '',
                    $robots_txt_file_path_info['dirname']).'/'.$robots_txt_file_path_info['basename'].'?v='.urlencode($date);
            $message = 'robots.txt file updated successfully.<br>Date: <strong>'.$date.'</strong>, robots.txt file path: <a href="'.$robots_txt_file_url.'" target="_blank"><strong>'.$robots_txt_file.'</strong></a>';
        } else {
            $message = 'robots.txt file could not write.<br>Date: <strong>'.$date.'</strong>, robots.txt file path: <strong>'.$robots_txt_file.'</strong>';
        }
        $response_message = $this->response->getMessage();
        $this->response->setMessage($response_message.'<br><br>'.$message);
    }
}