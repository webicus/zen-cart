<?php
/**
 * Google XML Sitemap Feed
 *
 * @package Google XML Sitemap Feed
 * @copyright Copyright 2005-2007, Andrew Berezin eCommerce-Service.com
 * @copyright Portions Copyright 2005, Bobby Easland
 * @copyright Portions Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @link http://www.sitemaps.org/
 * @link http://www.google.com/webmasters/sitemaps/docs/en/about.html About Google Sitemap
 * @version $Id: googlesitemap.php, v 1.3.12 21.06.2007 12:37 Andrew Berezin $
 */

define('HEADING_TITLE', 'Amministrazione Google XML Sitemap');
define('TEXT_GOOGLESITEMAP_OVERVIEW_HEAD', '<p><strong>SOMMARIO:</strong></p>');
define('TEXT_GOOGLESITEMAP_OVERVIEW_TEXT', '<p>Questo modulo genera automaticamente delle Google sitemaps XML per il tuo store zen-cart: una principale, una per le categorie, e una per i prodotti.</p>');
define('TEXT_GOOGLESITEMAP_INSTRUCTIONS_HEAD', '<p><strong>ISTRUZIONI: </strong></p>');
define('TEXT_GOOGLESITEMAP_INSTRUCTIONS_STEP1', '<p><strong><font color="#FF0000">PASSO 1:</font></strong> Clicca <a href=%s><strong>[QUI]</strong></a> per creare / aggiornare la tua sitemap. </p><p>NOTA: Registrare la sitemap con Google SiteMaps prima di procedere con il passo 2. </p>');
define('TEXT_GOOGLESITEMAP_INSTRUCTIONS_STEP2', '<p><strong><font color="#FF0000">PASSO 2:</font></strong> Clicca <a href=%s><strong>[Google]</strong></a> or <a href=%s><strong>[Yahoo!]</strong></a> per pingare il server di google, al fine di notificare l\'aggiornamento della sitemap.</p>');
define('TEXT_GOOGLESITEMAP_LOGIN_HEAD', '<strong>Cosa è Google SiteMaps?</strong>');
define('TEXT_GOOGLESITEMAP_LOGIN', '<p>Google SiteMaps ti permette di caricare delle sitemaps XML di tutte le categorie e prodotti direttamente su google.com per un\'indicizzazione più veloce.</p><p>Per registrare o loggarti nel tuo account Google, clicca <strong><a href="https://www.google.com/webmasters/sitemaps/login" target="_blank" class="splitPageLink">[QUI]</a></strong>.</p>');
?>