<div align="center" dir="auto">
    <a href="https://php.net">
        <img alt="PHP" src="https://www.php.net/images/logos/new-php-logo.svg" width="150">
    </a>
</div>
<br>
<p align="center">
<a href="https://www.php.net/releases/5_6_0.php" target="_blank" rel="nofollow"><img src="https://img.shields.io/badge/PHP->=v5.6-777BB4?logo=php&logoColor=white&labelColor=777BB4" alt="PHP Version"></a>
<a href="https://getbootstrap.com/docs/5.3/getting-started/introduction/" target="_blank" rel="nofollow"><img src="https://img.shields.io/badge/Bootstrap-v5.3.3-7952B3?logo=bootstrap&logoColor=white&labelColor=7952B3" alt="Bootstrap Version"></a>
<a href="https://blog.jquery.com/2023/08/28/jquery-3-7-1-released-reliable-table-row-dimensions/" target="_blank" rel="nofollow"><img src="https://img.shields.io/badge/jQuery-v3.7.1-0769AD?logo=jquery&logoColor=white&labelColor=0769AD" alt="jQuery Version"></a>
<a href="https://github.com/berkanumutlu/php-sitemap-generator/blob/master/LICENSE" target="_blank" rel="nofollow"><img src="https://img.shields.io/github/license/berkanumutlu/laravel-example-app" alt="License"></a>
</p>

# PHP Sitemap Generator

This project can be used to generate sitemaps. It can build a sitemap file from a list of URLs. The URLs may have attached the last modification date, change frequency, priority and image properties.

Sitemap format: http://www.sitemaps.org/protocol.html

## Sitemap file

After creating your sitemap.xml file, you should add the XML file to your `robots.txt`.

Line for the robots.txt:

```
Sitemap: http://example.com/sitemap/sitemap.xml
```

## Output

Example output when generating a sitemap

```XML
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    <!-- created with PHP Sitemap Generator by Berkan Ümütlü (https://github.com/berkanumutlu/php-sitemap-generator) -->
    <url>
        <loc>http://example.com/</loc>
        <lastmod>2024-03-17</lastmod>
        <priority>1</priority>
    </url>
    <url>
        <loc>http://example.com/about-us</loc>
        <lastmod>2024-03-08</lastmod>
        <priority>0.8</priority>
        <image:image>
            <image:loc>http://example.com/assets/images/pages/about-us.jpg</image:loc>
        </image:image>
    </url>
    <url>
        <loc>http://example.com/uber-uns</loc>
        <lastmod>2024-03-08</lastmod>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>http://example.com/a-propos-de-nous</loc>
        <lastmod>2024-03-08</lastmod>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>http://example.com/sobre-nosotros</loc>
        <lastmod>2024-03-08</lastmod>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>http://example.com/o-nas</loc>
        <lastmod>2024-03-08</lastmod>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>http://example.com/%D9%85%D8%B9%D9%84%D9%88%D9%85%D8%A7%D8%AA-%D8%B9%D9%86%D8%A7</loc>
        <lastmod>2024-03-08</lastmod>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>http://example.com/chi-siamo</loc>
        <lastmod>2024-03-08</lastmod>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>http://example.com/hakkimizda</loc>
        <lastmod>2024-03-08</lastmod>
        <priority>0.8</priority>
    </url>
</urlset>
```

## Screenshots

![screenshot01](screenshots/screenshot01.png)
![screenshot02](screenshots/screenshot02.png)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.