<?php
define('BASE_PATH', __DIR__.'/');
require_once '../vendor/autoload.php';
require_once 'config/db.php';

use App\Library\SitemapGenerator;

if (!empty($_POST['sitemap'])) {
    $response = new \App\Library\Response();
    $sitemap_generator = new SitemapGenerator();
    try {
        /*
         * Setting form options
         */
        $sitemap_generator->getSitemap()->setHttpSecure(!empty($_POST['http_secure']));
        if (!empty($_POST['domain'])) {
            $sitemap_generator->getSitemap()->setDomain(trim($_POST['domain']));
        }
        if (!empty($_POST['last_mod'])) {
            $sitemap_generator->setLastMod(trim($_POST['last_mod']));
        }
        if (!empty($_POST['change_freq'])) {
            $sitemap_generator->setChangeFreq(trim($_POST['change_freq']));
        }
        if (!empty($_POST['priority'])) {
            $sitemap_generator->setPriority(trim($_POST['priority']));
        }
        if (!empty($_POST['file_path'])) {
            $sitemap_generator->getSitemap()->setFilePath(BASE_PATH.trim($_POST['file_path']));
        }
        if (!empty($_POST['file_name'])) {
            $file_name = trim($_POST['file_name']);
            if (!empty($_POST['file_name_unique'])) {
                $file_name .= '-'.uniqid();
            }
            if (!empty($_POST['file_name_date'])) {
                $file_name .= '-'.date('Y-m-d');
            }
            $sitemap_generator->getSitemap()->setFileName($file_name);
        }
        if (!empty($_POST['file_ext'])) {
            $sitemap_generator->getSitemap()->setFileExt(trim($_POST['file_ext']));
        }
        if (!empty($_POST['file_header'])) {
            $sitemap_generator->getSitemap()->setHeader(trim($_POST['file_header']));
        }
        if (!empty($_POST['file_urlset_header'])) {
            $sitemap_generator->getSitemap()->setUrlsetHeader(trim($_POST['file_urlset_header']));
        }
        if (!empty($_POST['file_urlset_footer'])) {
            $sitemap_generator->getSitemap()->setUrlsetFooter(trim($_POST['file_urlset_footer']));
        }
        if (!empty($_POST['url_limit'])) {
            $sitemap_generator->setUrlLimit(trim($_POST['url_limit']));
        }
        /*
         * Adding base url
         */
        $sitemap_generator->set_url_loc('');
        $sitemap_generator->set_url_last_mod(date('Y-m-d'));
        $sitemap_generator->set_url_priority(1);
        $sitemap_generator->add_url_to_list();
        /*
         * Adding page urls
         */
        $query_pages = $db->query("SELECT * from tbl_pages", PDO::FETCH_ASSOC);
        if ($query_pages && $query_pages->rowCount()) {
            $pages = $query_pages->fetchAll(PDO::FETCH_ASSOC);
            foreach ($pages as $page) {
                $sitemap_generator->set_url_loc(urlencode($page['slug']));
                $date = !empty($page['updated_at']) ? $page['updated_at'] : $page['created_at'];
                $sitemap_generator->set_url_last_mod(date('Y-m-d', strtotime($date)));
                $sitemap_generator->set_url_priority(0.8);
                if (!empty($page['image'])) {
                    $sitemap_generator->set_url_image_loc('assets/images/pages/'.urlencode($page['image']));
                    $sitemap_generator->set_url_image_title($page['name']);
                }
                $sitemap_generator->add_url_to_list();
            }
        }
        /*
         * Adding products urls
         */
        $query_products = $db->query("SELECT * from tbl_products", PDO::FETCH_ASSOC);
        if ($query_products && $query_products->rowCount()) {
            $products = $query_products->fetchAll(PDO::FETCH_ASSOC);
            foreach ($products as $product) {
                $sitemap_generator->set_url_loc('product-detail/'.urlencode($product['slug']));
                $date = !empty($product['updated_at']) ? $product['updated_at'] : $product['created_at'];
                $sitemap_generator->set_url_last_mod(date('Y-m-d', strtotime($date)));
                $sitemap_generator->set_url_priority(1);
                if (!empty($product['image'])) {
                    $sitemap_generator->set_url_image_loc('assets/images/products/'.urlencode($product['image']));
                    $sitemap_generator->set_url_image_title($product['name']);
                }
                $sitemap_generator->add_url_to_list();
            }
        }
        /*
         * Generating sitemap
         */
        $response = $sitemap_generator->generate();
    } catch (\Exception $e) {
        $response->setStatus(false);
        $response->setStatusCode(500);
        $response->setStatusText($e->getMessage());
        $response->setMessage('The sitemap could not be created.');
    }
    echo $response->toJson();
    return true;
}
if (!empty($_POST['submit_sitemap'])) {
    $response = new \App\Library\Response();
    if (empty($_POST['sitemap_url'])) {
        $response->setMessage('Sitemap not found.');
        echo $response->toJson();
        exit();
    }
    $sitemap_generator = new SitemapGenerator();
    $response = $sitemap_generator->submit_sitemap($_POST['sitemap_url']);
    echo $response->toJson();
    return true;
}