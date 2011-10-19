<?php
/**
 * Sitemap XML
 *
 * @package Sitemap XML
 * @copyright Copyright 2005-2011, Andrew Berezin eCommerce-Service.com
 * @copyright Portions Copyright 2003-2011 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @link http://www.sitemaps.org/
 * @version $Id: sitemapxml.php, v 2.5.1 05.08.2011 13:43:05 AndrewBerezin $
 */

/*
Version 2.0.
Generate xml-sitemaps for:
1. Products,
2. Categories,
3. Reviews,
4. EZ-pages
- multi-language support,
- 'EZ pages rel=nofollow attribute' support (http://www.zen-cart.com/index.php?main_page=product_contrib_info&products_id=944),
- 'date_added'/'last_modified' support,
- check internal links ('alt_url') by "noindex" rule (main_page in ROBOTS_PAGES_TO_SKIP),
- toc_chapter proccessing
5. Testimonials,

If the products, categories, reviews have not changed since the last generation (time creation corresponding xml-sitemap file), a new xml-sitemap file not created (using existing xml-sitemap).

Priority is calculated on the basis of the positions in the selection from the database, ie the operator ORDER BY in the sql query. First item have priority=1.00, last=0.10. So can no longer be situations where all items in the file have the same priority.
Products - ORDER BY p.products_sort_order ASC, last_date DESC
Categories - ORDER BY c.sort_order ASC, last_date DESC
Reviews - ORDER BY r.reviews_rating ASC, last_date DESC
EZ-pages - ORDER BY p.sidebox_sort_order ASC, last_date DESC
Testimonials - ORDER BY last_date DESC

Support 3 $_GET parameters:
ping=yes - Pinging Google, Yahoo!, Ask.com and Microsoft.
inline=yes - output file sitemapindex.xml.
genxml=no - don't generate xml-files.
rebuild=yes - force rebuild xml files
checkurl=yes - check urls

Comments and suggestions are welcome.
If you need any more sitemaps (faq, news, etc) you may ask me, but I will do only if it matches with my interests.
*/

//@ini_set('display_errors', '1');
//error_reporting(E_ALL);

if (!get_cfg_var('safe_mode') && function_exists('set_time_limit')) {
  set_time_limit(0);
}

// This should be first line of the script:
$zco_notifier->notify('NOTIFY_HEADER_START_SITEMAPXML');
require(DIR_WS_CLASSES . 'sitemapxml.php');
/**
 * load language files
 */
require(DIR_WS_MODULES . zen_get_module_directory('require_languages.php'));
$breadcrumb->add(NAVBAR_TITLE);

$inline   = (isset($_GET['inline']) && $_GET['inline'] == 'yes') ? true : false;
$genxml   = (!isset($_GET['genxml']) || $_GET['genxml'] != 'no') ? true : false;
$ping     = (isset($_GET['ping']) && $_GET['ping'] == 'yes') ? true : false;
$checkurl = (isset($_GET['checkurl']) && $_GET['checkurl'] == 'yes') ? true : false;
$rebuild = (isset($_GET['rebuild']) && $_GET['rebuild'] == 'yes') ? true : false;

/**
 * load the site map class
 */

$zen_SiteMapXML = new zen_SiteMapXML($inline, $ping, $rebuild, $genxml);

$zen_SiteMapXML->setCheckURL($checkurl);

$tpl_dir = $template->get_template_dir('gss\.xsl', DIR_WS_TEMPLATE, $current_page_base, 'css');
$zen_SiteMapXML->setStylesheet($tpl_dir . '/gss.xsl');

$SiteMapXMLmodules = array();
$SiteMapXMLmodules = glob(DIR_WS_MODULES . 'pages/' . $current_page_base . '/sitemapxml_*.php');

// This should be last line of the script:
$zco_notifier->notify('NOTIFY_HEADER_END_SITEMAPXML');

//  @ini_set('display_errors', '1');
//  error_reporting(1);
