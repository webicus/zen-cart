<?php
/**
 * Sitemap XML Feed
 *
 * @package Sitemap XML Feed
 * @copyright Copyright 2005-2011, Andrew Berezin eCommerce-Service.com
 * @copyright Portions Copyright 2003-2011 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @link http://www.sitemaps.org/
 * @version $Id: sitemapxml.php, v 3.0.3 27.08.2011 13:11 AndrewBerezin $
 */

  require('includes/application_top.php');

  $current_version = '3.0.3 27.08.2011 13:11';

  $install_configuration = array(
'SITEMAPXML_VERSION' => array(TEXT_CONFIGURATION_TITLE_SITEMAPXML_VERSION, $current_version, TEXT_CONFIGURATION_DESCRIPTION_SITEMAPXML_VERSION, -10, null, 'zen_cfg_read_only('),

'SITEMAPXML_SITEMAPINDEX' => array(TEXT_CONFIGURATION_TITLE_SITEMAPXML_SITEMAPINDEX, 'sitemapindex', TEXT_CONFIGURATION_DESCRIPTION_SITEMAPXML_SITEMAPINDEX, 1, null, null),

'SITEMAPXML_COMPRESS' => array(TEXT_CONFIGURATION_TITLE_SITEMAPXML_COMPRESS, 'false', TEXT_CONFIGURATION_DESCRIPTION_SITEMAPXML_COMPRESS, 2, null, 'zen_cfg_select_option(array(\'true\', \'false\'),'),

'SITEMAPXML_LASTMOD_FORMAT' => array(TEXT_CONFIGURATION_TITLE_SITEMAPXML_LASTMOD_FORMAT, 'date', TEXT_CONFIGURATION_DESCRIPTION_SITEMAPXML_LASTMOD_FORMAT, 3, null, 'zen_cfg_select_option(array(\'date\', \'full\'),'),

'SITEMAPXML_USE_EXISTING_FILES' => array(TEXT_CONFIGURATION_TITLE_SITEMAPXML_USE_EXISTING_FILES, 'true', TEXT_CONFIGURATION_DESCRIPTION_SITEMAPXML_USE_EXISTING_FILES, 4, null, 'zen_cfg_select_option(array(\'true\', \'false\'),'),

'SITEMAPXML_USE_DEFAULT_LANGUAGE' => array(TEXT_CONFIGURATION_TITLE_SITEMAPXML_USE_DEFAULT_LANGUAGE, 'false', TEXT_CONFIGURATION_DESCRIPTION_SITEMAPXML_USE_DEFAULT_LANGUAGE, 5, null, 'zen_cfg_select_option(array(\'true\', \'false\'),'),

'SITEMAPXML_PING_URLS' => array(TEXT_CONFIGURATION_TITLE_SITEMAPXML_PING_URLS,
'Google => http://www.google.com/webmasters/sitemaps/ping?sitemap=%s;
Yahoo! => http://search.yahooapis.com/SiteExplorerService/V1/ping?sitemap=%s;
Ask.com => http://submissions.ask.com/ping?sitemap=%s;
Bing => http://www.bing.com/webmaster/ping.aspx?siteMap=%s', TEXT_CONFIGURATION_DESCRIPTION_SITEMAPXML_PING_URLS, 10, null, 'zen_cfg_textarea('),

'SITEMAPXML_HOMEPAGE_ORDERBY' => array(TEXT_CONFIGURATION_TITLE_SITEMAPXML_HOMEPAGE_ORDERBY, 'sort_order ASC', TEXT_CONFIGURATION_DESCRIPTION_SITEMAPXML_HOMEPAGE_ORDERBY, 20, null, null),
'SITEMAPXML_HOMEPAGE_CHANGEFREQ' => array(TEXT_CONFIGURATION_TITLE_SITEMAPXML_HOMEPAGE_CHANGEFREQ, 'weekly', TEXT_CONFIGURATION_DESCRIPTION_SITEMAPXML_HOMEPAGE_CHANGEFREQ, 21, null, 'zen_cfg_select_option(array(\'no\', \'always\', \'hourly\', \'daily\', \'weekly\', \'monthly\', \'yearly\', \'never\'),'),

'SITEMAPXML_PRODUCTS_ORDERBY' => array(TEXT_CONFIGURATION_TITLE_SITEMAPXML_PRODUCTS_ORDERBY, 'products_sort_order ASC, last_date DESC', TEXT_CONFIGURATION_DESCRIPTION_SITEMAPXML_PRODUCTS_ORDERBY, 30, null, null),
'SITEMAPXML_PRODUCTS_CHANGEFREQ' => array(TEXT_CONFIGURATION_TITLE_SITEMAPXML_PRODUCTS_CHANGEFREQ, 'weekly', TEXT_CONFIGURATION_DESCRIPTION_SITEMAPXML_PRODUCTS_CHANGEFREQ, 31, null, 'zen_cfg_select_option(array(\'no\', \'always\', \'hourly\', \'daily\', \'weekly\', \'monthly\', \'yearly\', \'never\'),'),

'SITEMAPXML_CATEGORIES_ORDERBY' => array(TEXT_CONFIGURATION_TITLE_SITEMAPXML_CATEGORIES_ORDERBY, 'sort_order ASC, last_date DESC', TEXT_CONFIGURATION_DESCRIPTION_SITEMAPXML_CATEGORIES_ORDERBY, 40, null, null),
'SITEMAPXML_CATEGORIES_CHANGEFREQ' => array(TEXT_CONFIGURATION_TITLE_SITEMAPXML_CATEGORIES_CHANGEFREQ, 'weekly', TEXT_CONFIGURATION_DESCRIPTION_SITEMAPXML_CATEGORIES_CHANGEFREQ, 41, null, 'zen_cfg_select_option(array(\'no\', \'always\', \'hourly\', \'daily\', \'weekly\', \'monthly\', \'yearly\', \'never\'),'),

'SITEMAPXML_REVIEWS_ORDERBY' => array(TEXT_CONFIGURATION_TITLE_SITEMAPXML_REVIEWS_ORDERBY, 'reviews_rating ASC, last_date DESC', TEXT_CONFIGURATION_DESCRIPTION_SITEMAPXML_REVIEWS_ORDERBY, 50, null, null),
'SITEMAPXML_REVIEWS_CHANGEFREQ' => array(TEXT_CONFIGURATION_TITLE_SITEMAPXML_REVIEWS_CHANGEFREQ, 'weekly', TEXT_CONFIGURATION_DESCRIPTION_SITEMAPXML_REVIEWS_CHANGEFREQ, 51, null, 'zen_cfg_select_option(array(\'no\', \'always\', \'hourly\', \'daily\', \'weekly\', \'monthly\', \'yearly\', \'never\'),'),

'SITEMAPXML_EZPAGES_ORDERBY' => array(TEXT_CONFIGURATION_TITLE_SITEMAPXML_EZPAGES_ORDERBY, 'sidebox_sort_order ASC, header_sort_order ASC, footer_sort_order ASC', TEXT_CONFIGURATION_DESCRIPTION_SITEMAPXML_EZPAGES_ORDERBY, 60, null, null),
'SITEMAPXML_EZPAGES_CHANGEFREQ' => array(TEXT_CONFIGURATION_TITLE_SITEMAPXML_EZPAGES_CHANGEFREQ, 'weekly', TEXT_CONFIGURATION_DESCRIPTION_SITEMAPXML_EZPAGES_CHANGEFREQ, 61, null, 'zen_cfg_select_option(array(\'no\', \'always\', \'hourly\', \'daily\', \'weekly\', \'monthly\', \'yearly\', \'never\'),'),

'SITEMAPXML_TESTIMONIALS_ORDERBY' => array(TEXT_CONFIGURATION_TITLE_SITEMAPXML_TESTIMONIALS_ORDERBY, 'last_date DESC', TEXT_CONFIGURATION_DESCRIPTION_SITEMAPXML_TESTIMONIALS_ORDERBY, 70, null, null),
'SITEMAPXML_TESTIMONIALS_CHANGEFREQ' => array(TEXT_CONFIGURATION_TITLE_SITEMAPXML_TESTIMONIALS_CHANGEFREQ, 'weekly', TEXT_CONFIGURATION_DESCRIPTION_SITEMAPXML_TESTIMONIALS_CHANGEFREQ, 71, null, 'zen_cfg_select_option(array(\'no\', \'always\', \'hourly\', \'daily\', \'weekly\', \'monthly\', \'yearly\', \'never\'),'),

'SITEMAPXML_NEWS_ORDERBY' => array(TEXT_CONFIGURATION_TITLE_SITEMAPXML_NEWS_ORDERBY, 'last_date DESC', TEXT_CONFIGURATION_DESCRIPTION_SITEMAPXML_NEWS_ORDERBY, 80, null, null),
'SITEMAPXML_NEWS_CHANGEFREQ' => array(TEXT_CONFIGURATION_TITLE_SITEMAPXML_NEWS_CHANGEFREQ, 'weekly', TEXT_CONFIGURATION_DESCRIPTION_SITEMAPXML_NEWS_CHANGEFREQ, 81, null, 'zen_cfg_select_option(array(\'no\', \'always\', \'hourly\', \'daily\', \'weekly\', \'monthly\', \'yearly\', \'never\'),'),

'SITEMAPXML_MANUFACTURERS_ORDERBY' => array(TEXT_CONFIGURATION_TITLE_SITEMAPXML_MANUFACTURERS_ORDERBY, 'last_date DESC', TEXT_CONFIGURATION_DESCRIPTION_SITEMAPXML_MANUFACTURERS_ORDERBY, 80, null, null),
'SITEMAPXML_MANUFACTURERS_CHANGEFREQ' => array(TEXT_CONFIGURATION_TITLE_SITEMAPXML_MANUFACTURERS_CHANGEFREQ, 'weekly', TEXT_CONFIGURATION_DESCRIPTION_SITEMAPXML_MANUFACTURERS_CHANGEFREQ, 81, null, 'zen_cfg_select_option(array(\'no\', \'always\', \'hourly\', \'daily\', \'weekly\', \'monthly\', \'yearly\', \'never\'),'),

//'SITEMAPXML_EXTRAURLS' => array(TEXT_CONFIGURATION_TITLE_SITEMAPXML_EXTRAURLS, 'last_date DESC', TEXT_CONFIGURATION_DESCRIPTION_SITEMAPXML_EXTRAURLS, 80, null, 'zen_cfg_textarea('),

/*
'SITEMAPXML_VIDEO_PING_URLS' => array('Ping urls',
'Google => http://www.google.com/webmasters/sitemaps/ping?sitemap=%s;
Yahoo! => http://search.yahooapis.com/SiteExplorerService/V1/ping?sitemap=%s;
Ask.com => http://submissions.ask.com/ping?sitemap=%s;
Bing => http://www.bing.com/webmaster/ping.aspx?siteMap=%s', 'List of pinging urls separated by ;', 10, null, 'zen_cfg_textarea('),
*/
);

/*
  $admin_page = array(
          'page_key' => 'sitemapxml',
          'language_key' => 'BOX_SITEMAPXML',
          'main_page' => 'FILENAME_SITEMAPXML',
          'page_params' => '',
          'menu_key' => 'tools',
          'display_on_menu' => 'Y',
          'sort_order' => '',
                      );
  $ext_modules->install_admin_pages($admin_page);
*/
/*
  $admin_page = array(
          'page_key' => 'sitemapxmlConfig',
          'language_key' => 'BOX_CONFIGURATION_SITEMAPXML',
          'main_page' => 'FILENAME_CONFIGURATION',
          'page_params' => 'gID=' . $ext_modules->configuration_group_id,
          'menu_key' => 'configuration',
          'display_on_menu' => 'Y',
          'sort_order' => $ext_modules->configuration_group_id,
                      );
  $ext_modules->install_admin_pages($admin_page);
*/

  $install_table_sitemapxml_extraurls_sql =
"CREATE TABLE IF NOT EXISTS `" . TABLE_SITEMAPXML_EXTRAURLS . "` (
  `id` int(11) NOT NULL auto_increment,
  `loc` varchar(256) NOT NULL DEFAULT '',
  `lastmod` varchar(32) NOT NULL DEFAULT '',
  `changefreq` varchar(8) NOT NULL DEFAULT '',
  `priority` varchar(4) NOT NULL DEFAULT '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM" . ((strtolower(DB_CHARSET) == 'utf8') ? ' /*!40101 DEFAULT CHARSET=utf8 */;' : ';');
//  $ext_modules->install_db_table(TABLE_SITEMAPXML_EXTRAURLS, $install_table_sitemapxml_extraurls_sql);

$action = (isset($_GET['action']) ? $_GET['action'] : '');

if (zen_not_null($action)) {

  switch ($action) {
    // demo active test
    case (zen_admin_demo()):
      $action='';
      $messageStack->add_session(ERROR_ADMIN_DEMO, 'caution');
      zen_redirect(zen_href_link(FILENAME_SITEMAPXML));
      break;

    case 'upgrade':
    case 'install':
      require_once(DIR_WS_CLASSES . 'ext_modules.php');
      $ext_modules = new ext_modules;
      $ext_modules->install_configuration_group('SITEMAPXML_', 'BOX_CONFIGURATION_SITEMAPXML', 'SitemapXML', 'sitemapxmlConfig');
      $ext_modules->install_configuration($install_configuration);
/*
      if ($action == 'upgrade') {
        if (sizeof($ext_modules->configUpdates['add']) > 0) {
          $messageStack->add_session(TEXT_UPGRADE_CONFIG_ADD, 'success');
          foreach ($ext_modules->configUpdates['add'] as $msg) {
            $messageStack->add_session('&nbsp;nbsp;nbsp;' . $msg, 'success');
          }
        }
        if (sizeof($ext_modules->configUpdates['upd']) > 0) {
          $messageStack->add_session(TEXT_UPGRADE_CONFIG_UPD, 'success');
          foreach ($ext_modules->configUpdates['upd'] as $msg) {
            $messageStack->add_session('&nbsp;nbsp;nbsp;' . $msg, 'success');
          }
        }
        if (sizeof($ext_modules->configUpdates['del']) > 0) {
          $messageStack->add_session(TEXT_UPGRADE_CONFIG_DEL, 'success');
          foreach ($ext_modules->configUpdates['del'] as $msg) {
            $messageStack->add_session('&nbsp;nbsp;nbsp;' . $msg, 'success');
          }
        }
      }
*/
/*
if (function_exists('zen_register_admin_page')) {
  $admin_page = array(
          'page_key' => 'sitemapxml',
          'language_key' => 'BOX_SITEMAPXML',
          'main_page' => 'FILENAME_SITEMAPXML',
          'page_params' => '',
          'menu_key' => 'tools',
          'display_on_menu' => 'Y',
          'sort_order' => '',
                      );
  if (zen_page_key_exists($page['page_key']) == FALSE) {
    if (!isset($page['sort_order']) || (int)$page['sort_order'] == 0) {
      $sql = "SELECT MAX(sort_order) AS sort_order_max FROM " . TABLE_ADMIN_PAGES . " WHERE menu_key = :menu_key:";
      $sql = $db->bindVars($sql, ':menu_key:', $page['menu_key'], 'string');
      $result = $db->Execute($sql);
      $page['sort_order'] = $result->fields['sort_order_max']+1;
    }
    zen_register_admin_page($page['page_key'], $page['language_key'], $page['main_page'], $page['page_params'], $page['menu_key'], $page['display_on_menu'], $page['sort_order'])
  }
}
*/
      zen_redirect(zen_href_link(FILENAME_SITEMAPXML));
      break;

    case 'uninstall':
      require_once(DIR_WS_CLASSES . 'ext_modules.php');
      $ext_modules = new ext_modules;
      $ext_modules->uninstall_configuration('SITEMAPXML_');
      $ext_modules->uninstall_admin_pages(array('sitemapxml', 'sitemapxmlConfig'));
      zen_redirect(zen_href_link(FILENAME_SITEMAPXML));
      break;

  }

}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/cssjsmenuhover.css" media="all" id="hoverJS">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
<script type="text/javascript">
  <!--
  function init()
  {
    cssjsmenu('navbar');
    if (document.getElementById)
    {
      var kill = document.getElementById('hoverJS');
      kill.disabled = true;
    }
  }
  // -->
</script>
<script type="text/javascript">
<!--
function getFormFields(obj) {
  var getParms = "&";
  for (i=0; i<obj.childNodes.length; i++) {
    if (obj.childNodes[i].tagName == "INPUT") {
      if (obj.childNodes[i].type == "text") {
        getParms += obj.childNodes[i].name + "=" + obj.childNodes[i].value + "&";
      }
      if (obj.childNodes[i].type == "hidden") {
        getParms += obj.childNodes[i].name + "=" + obj.childNodes[i].value + "&";
      }
      if (obj.childNodes[i].type == "checkbox") {
        if (obj.childNodes[i].checked) {
          getParms += obj.childNodes[i].name + "=" + obj.childNodes[i].value + "&";
        } else {
          getParms += obj.childNodes[i].name + "=&";
        }
      }
      if (obj.childNodes[i].type == "radio") {
        if (obj.childNodes[i].checked) {
          getParms += obj.childNodes[i].name + "=" + obj.childNodes[i].value + "&";
        }
      }
    }
    if (obj.childNodes[i].tagName == "SELECT") {
      var sel = obj.childNodes[i];
      getParms += sel.name + "=" + sel.options[sel.selectedIndex].value + "&";
    }
  }
  getParms = getParms.replace(/\s+/g," ");
  getParms = getParms.replace(/ /g, "+");
  return getParms;
}
  // -->
</script>
</head>
<body onload="init()">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo (defined('SITEMAPXML_VERSION') ? ' v ' . SITEMAPXML_VERSION : ''); ?></td>
          </tr>
        </table></td>
      </tr>

<?php
$configuration_group_id = $db->Execute("SELECT configuration_group_id
                                        FROM " . TABLE_CONFIGURATION . "
                                        WHERE configuration_key LIKE 'SITEMAPXML_%' LIMIT 1");

if (!$configuration_group_id->EOF) {
  $sitemapxml_configuration_group_id = $configuration_group_id->fields['configuration_group_id'];
}
if (!defined('SITEMAPXML_VERSION') && !isset($sitemapxml_configuration_group_id)) {
?>
      <tr>
        <td>
          <div style="border: solid 1px; padding: 4px;"><?php echo '<a href="' . zen_href_link(FILENAME_SITEMAPXML, 'action=install') . '">' . TEXT_INSTALL . '</a>'; ?></div>
        </td>
      </tr>
<?php
} elseif (SITEMAPXML_VERSION != $current_version) {
?>
      <tr>
        <td>
          <div style="border: solid 1px; padding: 4px;"><?php echo '<a href="' . zen_href_link(FILENAME_SITEMAPXML, 'action=upgrade') . '">' . TEXT_UPGRADE . '</a>'; ?></div>
        </td>
      </tr>
<?php
}
?>

<?php
$sitemapxml_install_notes = '';
$filesArray = array(
        DIR_FS_CATALOG . 'googlesitemap.php.txt',
        DIR_FS_CATALOG . 'googlesitemap.php',
        DIR_FS_ADMIN . DIR_WS_INCLUDES . 'auto_loaders/config.ext_modules.php',
        DIR_FS_ADMIN . DIR_WS_INCLUDES . 'init_includes/init_ext_modules.php',
        DIR_FS_ADMIN . DIR_WS_MODULES . 'ext_modules/sitemapxml.php',
        DIR_FS_ADMIN . DIR_WS_FUNCTIONS . 'extra_functions/sitemapxml.php',
                    );
if (version_compare(PROJECT_VERSION_NAME . ' ' . PROJECT_VERSION_MAJOR . '.' . PROJECT_VERSION_MINOR, '1.5.0', '>=')) {
  $filesArray[] = DIR_FS_ADMIN . DIR_WS_INCLUDES . 'boxes/extra_boxes/sitemapxml_tools_dhtml.php';
}
foreach ($filesArray as $file) {
  if (is_file($file)) {
    if (!@unlink($file)) {
      $sitemapxml_install_notes .= TEXT_SITEMAPXML_INSTALL_DELETE_FILE . ' - ' . $file . '<br />';
    }
  }
}
if ($sitemapxml_install_notes != '') {
?>
      <tr>
        <td>
                <h3><?php echo TEXT_SITEMAPXML_INSTALL_HEAD; ?></h3>
                <div style="border: solid 1px; padding: 4px;"><b><?php echo $sitemapxml_install_notes; ?></b></div>
        </td>
      </tr>
<?php } ?>

<?php
if (defined('SITEMAPXML_VERSION')) {
?>
      <tr>
        <td width="100%" valign="top">
          <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="main">
            <tr>
              <td width="100%" align="left" valign="top">
                <h3><?php echo TEXT_SITEMAPXML_OVERVIEW_HEAD; ?></h3>
                <div style="border: solid 1px; padding: 4px;"><?php echo TEXT_SITEMAPXML_OVERVIEW_TEXT; ?></div>
                <h3><?php echo TEXT_SITEMAPXML_INSTRUCTIONS_HEAD; ?></h3>
                <fieldset>
                  <legend><?php echo TEXT_SITEMAPXML_CHOOSE_PARAMETERS; ?></legend>
                  <form name="pingSE" action="<?php echo zen_catalog_href_link(FILENAME_SITEMAPXML); ?>" method="get" id="pingSE" target="_blank" onsubmit="javascript:window.open('<?php echo zen_catalog_href_link(FILENAME_SITEMAPXML); ?>'+getFormFields(this), 'sitemapPing', 'resizable=1,statusbar=5,width=700,height=400,top=0,left=50,scrollbars=yes');return false;">
                    <?php echo zen_draw_checkbox_field('rebuild', 'yes', false, '', 'id="rebuild"'); ?>
                    <label for="rebuild"><?php echo TEXT_SITEMAPXML_CHOOSE_PARAMETERS_REBUILD; ?></label>
                    <br class="clearBoth" />
                    <?php echo zen_draw_checkbox_field('inline', 'yes', false, '', 'id="inline"'); ?>
                    <label for="inline"><?php echo TEXT_SITEMAPXML_CHOOSE_PARAMETERS_INLINE; ?></label>
                    <br class="clearBoth" />
                    <?php echo zen_draw_checkbox_field('ping', 'yes', false, '', 'id="ping"'); ?>
                    <label for="ping"><?php echo TEXT_SITEMAPXML_CHOOSE_PARAMETERS_PING; ?></label>
                    <br class="clearBoth" />
                    <?php echo zen_image_submit('button_send.gif', IMAGE_SEND); ?>
                  </form>
                </fieldset>
                <h3><?php echo TEXT_SITEMAPXML_TIPS_HEAD; ?></h3>
                <div style="border: solid 1px; padding: 4px;"><?php echo TEXT_SITEMAPXML_TIPS_TEXT; ?></div>
              </td>
            </tr>
            <tr>
              <td width="100%" align="left" valign="top">
                <div style="border: padding: 4px;"><br /><?php echo '<a href="' . zen_href_link(FILENAME_SITEMAPXML, 'action=uninstall') . '">' . TEXT_UNINSTALL . '</a>'; ?></div>
              </td>
            </tr>
<?php
}
?>
          </table>
        </td>
      </tr>
<!-- body_text_eof //-->
    </table>
    </td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
