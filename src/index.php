<?php
require_once '../vendor/autoload.php';

use App\Library\SitemapGenerator;

$sitemap_generator = new SitemapGenerator();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sitemap Generator</title>
    <meta name="description" content="PHP Sitemap Generator">
    <meta name="keywords" content="php, sitemap, sitemap generator, php sitemap generator">
    <meta name="author" content="Berkan Ümütlü">
    <meta name="copyright" content="Berkan Ümütlü">
    <meta name="owner" content="Berkan Ümütlü">
    <meta name="url" content="https://github.com/berkanumutlu">
    <link rel="stylesheet" href="assets/plugins/bootstrap-5.3.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/plugins/flatpickr/flatpickr.min.css">
    <link rel="stylesheet" href="assets/web/css/style.min.css">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-xl-8 mx-auto">
            <div class="card my-5">
                <div class="card-header d-flex align-items-center">
                    <h1 class="mb-0 fs-4 fw-semibold">Sitemap Generator</h1>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs justify-content-center mb-4" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-db-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-db" type="button" role="tab"
                                    aria-controls="pills-db"
                                    aria-selected="true">DB
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-url-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-url" type="button" role="tab"
                                    aria-controls="pills-url" aria-selected="false">URL
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-db" role="tabpanel"
                             aria-labelledby="pills-db-tab" tabindex="0">
                            <form action="ajax.php" method="POST" class="sitemap-generator">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="input-group align-items-center mb-3">
                                            <label for="domain" class="form-label mb-0 me-2">Domain</label>
                                            <input type="text" id="domain" name="domain" class="form-control"
                                                   placeholder="Domain"
                                                   value="<?= $sitemap_generator->getSitemap()->getDomain() ?>">
                                            <div class="form-check form-switch ms-2">
                                                <input class="form-check-input" type="checkbox" name="http_secure"
                                                       id="http_secure"
                                                       role="switch" <?= $sitemap_generator->getSitemap()->isHttpSecure() ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="http_secure">Http secure</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="input-group align-items-center mb-3">
                                            <label for="last_mod" class="form-label mb-0 me-2">LastMod</label>
                                            <input type="text" id="last_mod" name="last_mod"
                                                   class="form-control flatpickr"
                                                   placeholder="The date of last modification of the page."
                                                   value="<?= $sitemap_generator->getLastMod() ?>">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="input-group align-items-center mb-3">
                                            <?php $change_freq = $sitemap_generator->getChangeFreq(); ?>
                                            <label for="change_freq" class="form-label mb-0 me-2">ChangeFreq</label>
                                            <select id="change_freq" name="change_freq" class="form-select">
                                                <option value="always" <?= $change_freq == 'always' ? 'selected' : '' ?>>
                                                    always
                                                </option>
                                                <option value="hourly" <?= $change_freq == 'hourly' ? 'selected' : '' ?>>
                                                    hourly
                                                </option>
                                                <option value="daily" <?= $change_freq == 'daily' ? 'selected' : '' ?>>
                                                    daily
                                                </option>
                                                <option value="weekly" <?= $change_freq == 'weekly' ? 'selected' : '' ?>>
                                                    weekly
                                                </option>
                                                <option value="monthly" <?= $change_freq == 'monthly' ? 'selected' : '' ?>>
                                                    monthly
                                                </option>
                                                <option value="yearly" <?= $change_freq == 'yearly' ? 'selected' : '' ?>>
                                                    yearly
                                                </option>
                                                <option value="never" <?= $change_freq == 'never' ? 'selected' : '' ?>>
                                                    never
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="input-group align-items-center mb-3">
                                            <label for="priority" class="form-label mb-0 me-2">Priority</label>
                                            <input type="number" id="priority" name="priority" class="form-control"
                                                   min="0" max="1" step="0.01" onkeyup="checkInputNumberValue(this)"
                                                   placeholder="The priority of this URL relative to other URLs on your site. Valid values range from 0.0 to 1.0."
                                                   value="<?= $sitemap_generator->getPriority() ?>">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="input-group align-items-center mb-3">
                                            <label for="file_path" class="form-label mb-0 me-2">File</label>
                                            <input type="text" id="file_path" name="file_path" class="form-control"
                                                   placeholder="File Path"
                                                   value="<?= $sitemap_generator->getSitemap()->getFilePath() ?>">
                                            <input type="text" id="file_name" name="file_name" class="form-control"
                                                   placeholder="File Name"
                                                   value="<?= $sitemap_generator->getSitemap()->getFileName() ?>">
                                            <input type="text" id="file_ext" name="file_ext" class="form-control"
                                                   placeholder="File Ext" readonly
                                                   value="<?= $sitemap_generator->getSitemap()->getFileExt() ?>">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="input-group align-items-center mb-3">
                                            <label for="url_limit" class="form-label mb-0 me-2">URL Limit</label>
                                            <input type="number" id="url_limit" name="url_limit" class="form-control"
                                                   min="0" max="50000" step="1" onkeyup="checkInputNumberValue(this)"
                                                   value="<?= $sitemap_generator->getUrlLimit() ?>">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="input-group mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="file_name_unique"
                                                       id="file_name_unique" role="switch">
                                                <label class="form-check-label" for="file_name_unique">Unique file name
                                                    <br><small><?= '(e.g. '.$sitemap_generator->getSitemap()->getFileName().'-'.uniqid().$sitemap_generator->getSitemap()->getFileExt().')' ?></small></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="input-group mb-3">
                                            <div class="form-check form-switch ms-3">
                                                <input class="form-check-input" type="checkbox" name="file_name_date"
                                                       id="file_name_date" role="switch">
                                                <label class="form-check-label" for="file_name_date">File name with date
                                                    <br><small><?= '(e.g. '.$sitemap_generator->getSitemap()->getFileName().'-'.date('Y-m-d').$sitemap_generator->getSitemap()->getFileExt().')' ?></small></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-5">
                                        <div class="mb-3">
                                            <label for="file_header" class="form-label">File Header</label>
                                            <textarea name="file_header" id="file_header" class="form-control"
                                                      rows="6"><?= $sitemap_generator->getSitemap()->getHeader() ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-7">
                                        <div class="mb-3">
                                            <label for="file_urlset_header" class="form-label">Urlset Header</label>
                                            <textarea name="file_urlset_header" id="file_urlset_header"
                                                      class="form-control"
                                                      rows="3"><?= $sitemap_generator->getSitemap()->getUrlsetHeader() ?></textarea>
                                            <label for="file_urlset_footer" class="form-label mt-1">Urlset
                                                Footer</label>
                                            <textarea name="file_urlset_footer" id="file_urlset_footer"
                                                      class="form-control"
                                                      rows="1"><?= $sitemap_generator->getSitemap()->getUrlsetFooter() ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="input-group mb-3">
                                            <div class="form-check form-switch ms-3">
                                                <input class="form-check-input" type="checkbox" name="create_gzip_file"
                                                       id="create_gzip_file" role="switch">
                                                <label class="form-check-label" for="create_gzip_file">Create gzip file
                                                    <br><small><?= '(output: '.$sitemap_generator->getSitemap()->getFileName().$sitemap_generator->getSitemap()->getFileExt().'.gz)' ?></small></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="input-group mb-3">
                                            <div class="form-check form-switch ms-3">
                                                <input class="form-check-input" type="checkbox" name="create_robots_txt"
                                                       id="create_robots_txt" role="switch">
                                                <label class="form-check-label" for="create_robots_txt">Create/Update
                                                    robots.txt
                                                    <br><small>(output: robots.txt)</small></label>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="col-12">
                                        <div class="d-flex justify-content-center">
                                            <button type="submit" class="btn btn-primary">Generate</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="alert-message alert-sitemap-generator">
                                <hr>
                                <div class="alert alert-danger d-flex align-items-center mb-0" role="alert">
                                    <div class="alert-icon alert-success">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                             fill="currentColor"
                                             class="bi bi-check-circle-fill" viewBox="0 0 16 16" aria-label="Success:">
                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                        </svg>
                                    </div>
                                    <div class="alert-icon alert-danger">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                             fill="currentColor"
                                             class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16"
                                             aria-label="Danger:">
                                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                                        </svg>
                                    </div>
                                    <div class="text"></div>
                                </div>
                            </div>
                            <div class="text-center mt-3">
                                <a href="ajax.php" class="btn btn-primary sitemap-submit-button" data-sitemap-url=""
                                   style="display: none;"
                                >Submit Sitemap</a>
                            </div>
                            <div class="alert-message alert-sitemap-submit-button">
                                <hr>
                                <div class="alert alert-danger d-flex align-items-center mb-0" role="alert">
                                    <div class="alert-icon alert-success">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                             fill="currentColor"
                                             class="bi bi-check-circle-fill" viewBox="0 0 16 16" aria-label="Success:">
                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                        </svg>
                                    </div>
                                    <div class="alert-icon alert-danger">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                             fill="currentColor"
                                             class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16"
                                             aria-label="Danger:">
                                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                                        </svg>
                                    </div>
                                    <div class="text" style="word-break: break-word;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-url" role="tabpanel"
                             aria-labelledby="pills-url-tab" tabindex="0">
                            <form action="ajax.php" method="POST" class="sitemap-generator-url">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="input-group align-items-center mb-3">
                                            <label for="domain" class="form-label mb-0 me-2">URL</label>
                                            <input type="text" id="domain" name="domain" class="form-control"
                                                   placeholder="https://www.google.com.tr"
                                                   value="<?= $sitemap_generator->getSitemap()->getDomain() ?>">>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="input-group align-items-center mb-3">
                                            <label for="last_mod" class="form-label mb-0 me-2">LastMod</label>
                                            <input type="text" id="last_mod" name="last_mod"
                                                   class="form-control flatpickr"
                                                   placeholder="The date of last modification of the page."
                                                   value="<?= $sitemap_generator->getLastMod() ?>">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="input-group align-items-center mb-3">
                                            <?php $change_freq = $sitemap_generator->getChangeFreq(); ?>
                                            <label for="change_freq" class="form-label mb-0 me-2">ChangeFreq</label>
                                            <select id="change_freq" name="change_freq" class="form-select">
                                                <option value="always" <?= $change_freq == 'always' ? 'selected' : '' ?>>
                                                    always
                                                </option>
                                                <option value="hourly" <?= $change_freq == 'hourly' ? 'selected' : '' ?>>
                                                    hourly
                                                </option>
                                                <option value="daily" <?= $change_freq == 'daily' ? 'selected' : '' ?>>
                                                    daily
                                                </option>
                                                <option value="weekly" <?= $change_freq == 'weekly' ? 'selected' : '' ?>>
                                                    weekly
                                                </option>
                                                <option value="monthly" <?= $change_freq == 'monthly' ? 'selected' : '' ?>>
                                                    monthly
                                                </option>
                                                <option value="yearly" <?= $change_freq == 'yearly' ? 'selected' : '' ?>>
                                                    yearly
                                                </option>
                                                <option value="never" <?= $change_freq == 'never' ? 'selected' : '' ?>>
                                                    never
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="input-group align-items-center mb-3">
                                            <label for="priority" class="form-label mb-0 me-2">Priority</label>
                                            <input type="number" id="priority" name="priority" class="form-control"
                                                   min="0" max="1" step="0.01" onkeyup="checkInputNumberValue(this)"
                                                   placeholder="The priority of this URL relative to other URLs on your site. Valid values range from 0.0 to 1.0."
                                                   value="<?= $sitemap_generator->getPriority() ?>">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="input-group align-items-center mb-3">
                                            <label for="url_limit" class="form-label mb-0 me-2">URL Limit</label>
                                            <input type="number" id="url_limit" name="url_limit" class="form-control"
                                                   min="0" max="50000" step="1" onkeyup="checkInputNumberValue(this)"
                                                   value="<?= $sitemap_generator->getUrlLimit() ?>">
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="col-12">
                                        <div class="d-flex justify-content-center">
                                            <button type="submit" class="btn btn-primary">Generate</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="alert-message alert-sitemap-generator-url">
                                <hr>
                                <div class="alert alert-danger d-flex align-items-center mb-0" role="alert">
                                    <div class="alert-icon alert-success">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                             fill="currentColor"
                                             class="bi bi-check-circle-fill" viewBox="0 0 16 16" aria-label="Success:">
                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                        </svg>
                                    </div>
                                    <div class="alert-icon alert-danger">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                             fill="currentColor"
                                             class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16"
                                             aria-label="Danger:">
                                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                                        </svg>
                                    </div>
                                    <div class="text"></div>
                                </div>
                            </div>
                            <div class="text-center mt-3">
                                <a href="ajax.php" class="btn btn-primary sitemap-generator-url-submit-button"
                                   data-sitemap-url=""
                                   style="display: none;"
                                >Submit Sitemap</a>
                            </div>
                            <div class="alert-message alert-sitemap-generator-url-submit-button">
                                <hr>
                                <div class="alert alert-danger d-flex align-items-center mb-0" role="alert">
                                    <div class="alert-icon alert-success">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                             fill="currentColor"
                                             class="bi bi-check-circle-fill" viewBox="0 0 16 16" aria-label="Success:">
                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                        </svg>
                                    </div>
                                    <div class="alert-icon alert-danger">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                             fill="currentColor"
                                             class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16"
                                             aria-label="Danger:">
                                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                                        </svg>
                                    </div>
                                    <div class="text" style="word-break: break-word;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-body-secondary">
                    <p class="mb-0">Copyright © 2023
                        <a href="https://github.com/berkanumutlu" target="_blank">Berkan Ümütlü</a>. All Right Reserved.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="assets/plugins/jquery/jquery-3.7.1.min.js"></script>
<script src="assets/plugins/bootstrap-5.3.3/js/bootstrap.min.js"></script>
<script src="assets/plugins/flatpickr/flatpickr.js"></script>
<script src="assets/web/js/main.js"></script>
</body>
</html>
