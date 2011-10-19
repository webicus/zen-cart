<?php
/**
 * Sitemap XML
 *
 * @package Sitemap XML
 * @copyright Copyright 2005-2010, Andrew Berezin eCommerce-Service.com
 * @copyright Portions Copyright 2003-2008 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: sitemapxml_testimonials.php, v 2.3.1 21.07.2010 9:18:18 AndrewBerezin $
 */

if (defined('TABLE_TESTIMONIALS_MANAGER')) {
  echo '<h3>' . TEXT_HEAD_TESTIMONIALS . '</h3>';
  $last_date = $db->Execute("SELECT MAX(GREATEST(t.date_added, IFNULL(t.last_update, '0001-01-01 00:00:00'))) AS last_date
                             FROM " . TABLE_TESTIMONIALS_MANAGER . " t
                             WHERE t.status = '1'");
  if ($zen_SiteMapXML->SitemapOpen('testimonials', $last_date->fields['last_date'])) {
    $testimonials = $db->Execute("SELECT t.testimonials_id, GREATEST(t.date_added, IFNULL(t.last_update, '0001-01-01 00:00:00')) AS last_date, t.language_id
                                  FROM " . TABLE_TESTIMONIALS_MANAGER . " t
                                  WHERE t.status = '1'
                                    AND t.language_id IN (" . $zen_SiteMapXML->getLanguagesIDs() . ") " .
                                  (SITEMAPXML_TESTIMONIALS_ORDERBY != '' ? "ORDER BY " . SITEMAPXML_TESTIMONIALS_ORDERBY : ''));
    $zen_SiteMapXML->SitemapSetMaxItems($testimonials->RecordCount());
    while(!$testimonials->EOF) {
      $langParm = $zen_SiteMapXML->getLanguageParameter($testimonials->fields['language_id']);
      if ($langParm !== false) {
        $link = zen_href_link(FILENAME_TESTIMONIALS_MANAGER, 'testimonials_id=' . $testimonials->fields['testimonials_id'] . $langParm, 'NONSSL', false);
        $zen_SiteMapXML->SitemapWriteItem($link, strtotime($testimonials->fields['last_date']), SITEMAPXML_TESTIMONIALS_CHANGEFREQ);
        $testimonials->MoveNext();
      }
    }
    $zen_SiteMapXML->SitemapClose();
  }
}