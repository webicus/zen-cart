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

	@define('GOOGLE_SITEMAP_VERSION', 'v 1.3.12 21.06.2007 12:37');

//	require_once('includes/application_top.php');
	@include('includes/local/configure.php');
	@require_once('includes/configure.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="ru">
<head>
<title>Zen Cart! Google Sitemap Installer</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<meta http-equiv="imagetoolbar" content="no" />

<base href="http://zen.tt/" />

</head>

<body id="indexBody">
<?php
if(isset($_POST['action'])) {
	if (!@mysql_connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD)) {
		echo 'Error connecting to mySQL: ' . mysql_errno() . ': ' . mysql_error() . '<br />';
		die;
	}
	if (!mysql_select_db(DB_DATABASE)) {
		echo 'Error selecting data base: ' . mysql_errno() . ': ' . mysql_error() . '<br />';
		die;
	}
	$mysql_error = true;
	function xmysql_query($sql) {
		global $mysql_error;
		if(!$mysql_error) {
			echo 'Skipping: ' . nl2br($sql) . '<br />';
		} else {
			echo 'Executing: ' . nl2br($sql) . '<br />';
			if(!$mysql_error = mysql_query("$sql")) {
				echo 'Error: ' . mysql_errno() . ': ' . mysql_error() . '<br />';
			}
		}
		
	}
	switch ($_POST['action']) {
		case 'install':
			echo "Installing ..." .'<br />';
			xmysql_query("SET @configuration_group_id=0;");
			xmysql_query("SELECT (@configuration_group_id:=configuration_group_id) FROM " . DB_PREFIX . "configuration_group WHERE configuration_group_title= 'Google XML Sitemap' LIMIT 1;");
			xmysql_query("DELETE FROM " . DB_PREFIX . "configuration WHERE configuration_group_id = @configuration_group_id;");
			xmysql_query("DELETE FROM " . DB_PREFIX . "configuration_group WHERE configuration_group_id = @configuration_group_id;");
			xmysql_query("INSERT INTO " . DB_PREFIX . "configuration_group (configuration_group_id, configuration_group_title, configuration_group_description, sort_order, visible) VALUES (NULL, 'Google XML Sitemap', 'Google XML Sitemap Configuration', '1', '1');");
			xmysql_query("SET @configuration_group_id=last_insert_id();");
			xmysql_query("UPDATE " . DB_PREFIX . "configuration_group SET sort_order = @configuration_group_id WHERE configuration_group_id = @configuration_group_id;");
			xmysql_query("INSERT INTO " . DB_PREFIX . "configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES
(NULL, 'Compress XML File', 'GOOGLE_SITEMAP_COMPRESS', 'false', 'Compress Google XML Sitemap file', @configuration_group_id, 1, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'Products changefreq', 'GOOGLE_SITEMAP_PROD_CHANGE_FREQ', 'weekly', 'How frequently the Product pages page is likely to change.', @configuration_group_id, 2, NOW(), NULL, 'zen_cfg_select_option(array(\'always\', \'hourly\', \'daily\', \'weekly\', \'monthly\', \'yearly\', \'never\'),'),
(NULL, 'Category changefreq', 'GOOGLE_SITEMAP_CAT_CHANGE_FREQ', 'weekly', 'How frequently the Category pages page is likely to change.', @configuration_group_id, 3, NOW(), NULL, 'zen_cfg_select_option(array(\'always\', \'hourly\', \'daily\', \'weekly\', \'monthly\', \'yearly\', \'never\'),'),
(NULL, 'Lastmod tag format', 'GOOGLE_SITEMAP_LASTMOD_FORMAT', 'date', 'Lastmod tag format:<br />date - Complete date: YYYY-MM-DD (eg 1997-07-16)<br />full -    Complete date plus hours, minutes and seconds: YYYY-MM-DDThh:mm:ssTZD (eg 1997-07-16T19:20:30+01:00)', @configuration_group_id, 4, NOW(), NULL, 'zen_cfg_select_option(array(\'date\', \'full\'),'),
(NULL, 'Category priority', 'GOOGLE_SITEMAP_CAT_CHANGE_PRIOR', '0.5', 'The default priority of the products URL. Valid values range from 0.0 to 1.0.', @configuration_group_id, 3, NOW(), NULL, NULL),
(NULL, 'Products priority', 'GOOGLE_SITEMAP_PROD_CHANGE_PRIOR', '0', 'The default priority of the products URL. Valid values range from 0.0 to 1.0.', @configuration_group_id, 5, NOW(), NULL, NULL),
(NULL, 'Use Google Sitemaps Stylesheet', 'GOOGLE_SITEMAP_USE_XSL', 'true', 'Google Sitemaps Stylesheet gss.xsl', @configuration_group_id, 6, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'XML directory', 'GOOGLE_SITEMAP_XML_FS_DIRECTORY', '', 'Directory using for saving XML files. Setting it to your root directory. If empty, Google Sitemap use DIR_FS_CATALOG directory.', @configuration_group_id, 7, NOW(), NULL, NULL),
(NULL, 'EZPages Header', 'GOOGLE_SITEMAP_EZPAGES_HEADER', 'true', 'Use EZPages Header links to feed sitemapezpages.xml?', @configuration_group_id, 10, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'EZPages Sidebox', 'GOOGLE_SITEMAP_EZPAGES_SIDEBOX', 'true', 'Use EZPages Sidebox links to feed sitemapezpages.xml?', @configuration_group_id, 10, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'EZPages Footer', 'GOOGLE_SITEMAP_EZPAGES_FOOTER', 'true', 'Use EZPages Footer links to feed sitemapezpages.xml?', @configuration_group_id, 10, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'EZPages changefreq', 'GOOGLE_SITEMAP_EZPAGES_CHANGE_FREQ', 'weekly', 'How frequently the EZPages pages page is likely to change.', @configuration_group_id, 3, NOW(), NULL, 'zen_cfg_select_option(array(\'always\', \'hourly\', \'daily\', \'weekly\', \'monthly\', \'yearly\', \'never\'),'),
(NULL, 'EZPages priority', 'GOOGLE_SITEMAP_EZPAGES_CHANGE_PRIOR', '0.5', 'The default priority of the EZPages URL. Valid values range from 0.0 to 1.0.', @configuration_group_id, 3, NOW(), NULL, NULL);");
			break;
		case 'update_1_3_2':
			echo "Update_1_3_2 ..." .'<br />';
			xmysql_query("SET @configuration_group_id=0;");
			xmysql_query("SELECT @configuration_group_id:=configuration_group_id FROM " . DB_PREFIX . "configuration_group WHERE configuration_group_title= 'Google XML Sitemap' LIMIT 1;");
			xmysql_query("INSERT INTO " . DB_PREFIX . "configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES
(NULL, 'Use Google Sitemaps Stylesheet', 'GOOGLE_SITEMAP_USE_XSL', 'true', 'Google Sitemaps Stylesheet gss.xsl', @configuration_group_id, 6, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'Use root directory', 'GOOGLE_SITEMAP_USE_ROOT_DIRECTORY', 'true', 'Use root directory for sitemap files', @configuration_group_id, 7, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),');");
			break;
		case 'update_1_3_3':
			echo "Update_1_3_3 ..." .'<br />';
			xmysql_query("SET @configuration_group_id=0;");
			xmysql_query("SELECT @configuration_group_id:=configuration_group_id FROM " . DB_PREFIX . "configuration_group WHERE configuration_group_title= 'Google XML Sitemap' LIMIT 1;");
			xmysql_query("INSERT INTO " . DB_PREFIX . "configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES 
(NULL, 'EZPages Header', 'GOOGLE_SITEMAP_EZPAGES_HEADER', 'true', 'Use EZPages Header links to feed sitemapezpages.xml?', @configuration_group_id, 10, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'EZPages Sidebox', 'GOOGLE_SITEMAP_EZPAGES_SIDEBOX', 'true', 'Use EZPages Sidebox links to feed sitemapezpages.xml?', @configuration_group_id, 10, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'EZPages Footer', 'GOOGLE_SITEMAP_EZPAGES_FOOTER', 'true', 'Use EZPages Footer links to feed sitemapezpages.xml?', @configuration_group_id, 10, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'EZPages changefreq', 'GOOGLE_SITEMAP_EZPAGES_CHANGE_FREQ', 'weekly', 'How frequently the EZPages pages page is likely to change.', @configuration_group_id, 3, NOW(), NULL, 'zen_cfg_select_option(array(\'always\', \'hourly\', \'daily\', \'weekly\', \'monthly\', \'yearly\', \'never\'),'),
(NULL, 'EZPages priority', 'GOOGLE_SITEMAP_EZPAGES_CHANGE_PRIOR', '0.5', 'The default priority of the EZPages URL. Valid values range from 0.0 to 1.0.', @configuration_group_id, 3, NOW(), NULL, NULL);");
			break;
		case 'update_1_3_4':
			echo "Update_1_3_4 ..." .'<br />';
			xmysql_query("DELETE FROM " . DB_PREFIX . "configuration WHERE configuration_key = 'GOOGLE_SITEMAP_USE_ROOT_DIRECTORY' LIMIT 1;");
			xmysql_query("SET @configuration_group_id=0;");
			xmysql_query("SELECT @configuration_group_id:=configuration_group_id FROM " . DB_PREFIX . "configuration_group WHERE configuration_group_title= 'Google XML Sitemap' LIMIT 1;");
			xmysql_query("INSERT INTO " . DB_PREFIX . "configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES 
(NULL, 'XML directory', 'GOOGLE_SITEMAP_XML_FS_DIRECTORY', '', 'Directory using for saving XML files. Setting it to your root directory. If empty, Google Sitemap use DIR_FS_CATALOG directory.', @configuration_group_id, 7, NOW(), NULL, NULL);");
			break;
		case 'uninstall':
			echo "Uninstall ..." .'<br />';
			xmysql_query("SET @configuration_group_id=0;");
			xmysql_query("SELECT @configuration_group_id:=configuration_group_id FROM " . DB_PREFIX . "configuration_group WHERE configuration_group_title= 'Google XML Sitemap' LIMIT 1;");
			xmysql_query("DELETE FROM " . DB_PREFIX . "configuration_group WHERE configuration_group_id = @configuration_group_id;");
			xmysql_query("DELETE FROM " . DB_PREFIX . "configuration WHERE configuration_group_id = @configuration_group_id;");
			break;

		default:
			break;
	}
	echo "OK!" .'<br />';
}
define('HEADING_TITLE', 'Google Sitemap Installer');
define('TEXT_INSTALL', 'Install');
define('TEXT_UPDATE_1_3_2', 'Update up to v 1.3.2');
define('TEXT_UPDATE_1_3_3', 'Update up to v 1.3.3');
define('TEXT_UPDATE_1_3_4', 'Update up to v 1.3.4');
define('TEXT_UNINSTALL', 'Unistall');
?>
	<form action="<?php echo HTTP_SERVER . DIR_WS_ADMIN . 'install_googlesitemap.php'; ?>" method="post">
		<fieldset>
			<legend><?php echo HEADING_TITLE; ?></legend>
			<input name="action" id="install" type="radio" value="install" />
			<label class="Label" for="install"><?php echo TEXT_INSTALL; ?></label>
			<br class="clearBoth" />
			<input name="action" id="update_1_3_2" type="radio" value="update_1_3_2" />
			<label class="Label" for="update_1_3_2"><?php echo TEXT_UPDATE_1_3_2; ?></label>
			<br class="clearBoth" />
			<input name="action" id="update_1_3_3" type="radio" value="update_1_3_3" />
			<label class="Label" for="update_1_3_3"><?php echo TEXT_UPDATE_1_3_3; ?></label>
			<br class="clearBoth" />
			<input name="action" id="update_1_3_4" type="radio" value="update_1_3_4" />
			<label class="Label" for="update_1_3_4"><?php echo TEXT_UPDATE_1_3_4; ?></label>
			<br class="clearBoth" />
			<input name="action" id="uninstall" type="radio" value="uninstall" />
			<label class="Label" for="uninstall"><?php echo TEXT_UNINSTALL; ?></label>
			<br class="clearBoth" />
			<input name="go" type="submit" value="Process" />
		</fieldset>
	</form>
</body>
</html>
