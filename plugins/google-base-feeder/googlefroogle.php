<?php
/**
 * googlefroogle.php
 *
 * @package google base feeder
 * @copyright Copyright 2007 Numinix Technology http://www.numinix.com
 * @copyright Portions Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: googlefroogle.php, v 1.5.3 28.08.2007 10:25 numinix $
 * @author Andrew Berezin & Jeff Lew
 */
@define('GOOGLE_FROOGLE_VERSION', '1.5.3 28.08.2007 10:25');
/*
Google Base - Attribute List - http://base.google.co.uk/base/tab_instructions.html
Google Base - Products - Creating your bulk upload - http://base.google.com/base/products.html

TODO List
*/
	require('includes/application_top.php');

	$stimer = microtime_float();
	
	@define('GOOGLE_FROOGLE_EXPIRATION_DAYS', 30);
	@define('GOOGLE_FROOGLE_EXPIRATION_BASE', 'now'); // now/product
	@define('GOOGLE_FROOGLE_OFFER_ID', 'id'); // id/model/false
	@define('GOOGLE_FROOGLE_DIRECTORY', 'feed/');
	@define('GOOGLE_FROOGLE_OUTPUT_BUFFER_MAXSIZE', 1024*1024);
	@define('GOOGLE_FROOGLE_CHECK_IMAGE', 'false');
	@define('GOOGLE_FROOGLE_STAT', false);
	$anti_timeout_counter = 0; //for timeout issues as well as counting number of products processed
	$max_limit = false;
	$today = date("Y-m-d");
	@define('GOOGLE_FROOGLE_USE_CPATH', 'false');
	@define('NL', "<br />\n");

	require(zen_get_file_directory(DIR_WS_LANGUAGES . $_SESSION['language'] .'/', 'googlefroogle.php', 'false'));

	$languages = $db->execute("select code, languages_id from " . TABLE_LANGUAGES . " where name='" . GOOGLE_FROOGLE_LANGUAGE . "' limit 1");

	$product_url_add = (GOOGLE_FROOGLE_LANGUAGE_DISPLAY == 'true' ? "&language=" . $languages->fields['code'] : '') . (GOOGLE_FROOGLE_CURRENCY_DISPLAY == 'true' ? "&currency=" . GOOGLE_FROOGLE_CURRENCY : '');

	echo TEXT_GOOGLE_FROOGLE_STARTED . NL;
	echo TEXT_GOOGLE_FROOGLE_FILE_LOCATION . DIR_FS_CATALOG . GOOGLE_FROOGLE_DIRECTORY . GOOGLE_FROOGLE_OUTPUT_FILENAME . NL;
	echo "Processing: Feed - " . (isset($_GET['feed']) && $_GET['feed'] == "yes" ? "Yes" : "No") . ", Upload - " . (isset($_GET['upload']) && $_GET['upload'] == "yes" ? "Yes" : "No") . NL;

if (isset($_GET['feed']) && $_GET['feed'] == "yes") {
	if (is_dir(DIR_FS_CATALOG . GOOGLE_FROOGLE_DIRECTORY)) {
		if (!is_writeable(DIR_FS_CATALOG . GOOGLE_FROOGLE_DIRECTORY)) {
			echo ERROR_GOOGLE_FROOGLE_DIRECTORY_NOT_WRITEABLE . NL;
			die;
		}
	} else {
		echo ERROR_GOOGLE_FROOGLE_DIRECTORY_DOES_NOT_EXIST . NL;
		die;
	}

	$stimer_feed = microtime_float();
	if (!get_cfg_var('safe_mode') && function_exists('safe_mode')) {
		set_time_limit(0);
	}

	$output_buffer = "";

	$outfile = DIR_FS_CATALOG . GOOGLE_FROOGLE_DIRECTORY . GOOGLE_FROOGLE_OUTPUT_FILENAME;
	if (file_exists($outfile)) {
		chmod($outfile, 0777);
	} else {
		fopen($outfile, "w");
	}
	if (is_writeable($outfile)) {
		
		$content = array();
		
		$content["xml"] = '<?xml version="1.0" encoding="UTF-8" ?>';
		$content["rss"] = '<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">';
		$content["channel"]= '<channel>';
		$content["title"] = '<title>' . STORE_NAME . '</title>';
		$content["link"] = '<link>' . GOOGLE_BASE_ADDRESS . '</link>';
		$content["channel_description"] = '<description>' . zen_xml_sanitizer(GOOGLE_BASE_DESCRIPTION) . '</description>';
		zen_froogle_fwrite($content, "wb");
		
		
		$categories_array = zen_froogle_category_tree();
		
		if (GOOGLE_BASE_ASA == 'true') {
		$products_query = "SELECT p.products_id, p.products_model, pd.products_name, pd.products_description, p.products_image, p.products_tax_class_id, p.products_price_sorter, p.products_upc, p.products_isbn, s.specials_new_products_price, s.expires_date, GREATEST(p.products_date_added, p.products_last_modified, p.products_date_available) AS base_date, m.manufacturers_name, p.products_quantity, pt.type_handler, p.products_weight, p.products_condition, p.products_category
										 FROM " . TABLE_PRODUCTS . " p
											 LEFT JOIN " . TABLE_MANUFACTURERS . " m ON (p.manufacturers_id = m.manufacturers_id)
											 LEFT JOIN " . TABLE_PRODUCTS_DESCRIPTION . " pd ON (p.products_id = pd.products_id)
											 LEFT JOIN " . TABLE_PRODUCT_TYPES . " pt ON (p.products_type=pt.type_id)
											 LEFT JOIN " . TABLE_SPECIALS . " s ON (s.products_id = p.products_id)
										 WHERE p.products_status = 1
											 AND p.product_is_call = 0
											 AND p.product_is_free = 0
											 AND pd.language_id = " . $languages->fields['languages_id'] ."
										 ORDER BY p.products_id DESC";
		} else {
		$products_query = "SELECT p.products_id, p.products_model, pd.products_name, pd.products_description, p.products_image, p.products_tax_class_id, p.products_price_sorter, s.specials_new_products_price, s.expires_date, GREATEST(p.products_date_added, p.products_last_modified, p.products_date_available) AS base_date, m.manufacturers_name, p.products_quantity, pt.type_handler, p.products_weight
										 FROM " . TABLE_PRODUCTS . " p
											 LEFT JOIN " . TABLE_MANUFACTURERS . " m ON (p.manufacturers_id = m.manufacturers_id)
											 LEFT JOIN " . TABLE_PRODUCTS_DESCRIPTION . " pd ON (p.products_id = pd.products_id)
											 LEFT JOIN " . TABLE_PRODUCT_TYPES . " pt ON (p.products_type=pt.type_id)
											 LEFT JOIN " . TABLE_SPECIALS . " s ON (s.products_id = p.products_id)
										 WHERE p.products_status = 1
											 AND p.product_is_call = 0
											 AND p.product_is_free = 0
											 AND pd.language_id = " . $languages->fields['languages_id'] ."
										 ORDER BY p.products_id DESC";
		}
										 									 
		$products = $db->Execute($products_query);
		$tax_rate = array();
			while (!$products->EOF && !$max_limit) { // run until end of file or until maximum number of products reached
				list($categories_list, $cPath) = zen_froogle_get_category($products->fields['products_id']);
				if (zen_google_base_categories((GOOGLE_BASE_POS_CATEGORIES), $categories_list, 1) == true && zen_google_base_categories((GOOGLE_BASE_NEG_CATEGORIES), $categories_list, 2) == false) { // check to see if category limits are set.  If so, only process for those categories.
					if ($anti_timeout_counter == GOOGLE_FROOGLE_MAX_PRODUCTS && GOOGLE_FROOGLE_MAX_PRODUCTS != 0) { // if counter is greater than or equal to maximum products
						$max_limit = true; // then max products reached
					} else {
						$max_limit = false; // otherwise, max products not reached
					}
					if ($products->fields['specials_id'] == $products->fields['products_id']) {
						if ($today < $products->fields['expires_date']) {
							$price = $products->fields['specials_new_products_price'];
						}
					} else if (PROJECT_VERSION_MINOR < 3.6) {
						$price = zen_get_products_actual_price($products->fields['products_id']);
					} else {
						$price = $products->fields['products_price_sorter'];
					}
					if ($price > 0) {
						$anti_timeout_counter++;
						if (!isset($tax_rate[$products->fields['products_tax_class_id']])) {
							$tax_rate[$products->fields['products_tax_class_id']] = zen_get_tax_rate($products->fields['products_tax_class_id']);
						}
						$price = zen_add_tax($price, $tax_rate[$products->fields['products_tax_class_id']]);
						$price = $currencies->value($price, true, GOOGLE_FROOGLE_CURRENCY, $currencies->get_value(GOOGLE_FROOGLE_CURRENCY));
						$href = ($products->fields['type_handler'] ? $products->fields['type_handler'] : 'product') . '_info';
		
						$cPath_href = (GOOGLE_FROOGLE_USE_CPATH == 'true' ? 'cPath=' . $cPath . '&' : '');
						$href = zen_href_link($href, $cPath_href . 'products_id=' . $products->fields['products_id'] . $product_url_add, 'NONSSL', false);
						
						$content = array();
						$content["item_start"] = "\n" . '<item>';
						$content["title"] = '<title>' . zen_xml_sanitizer($products->fields['products_name']) . '</title>'; 
						$content["brand"] = '<g:brand>' . zen_xml_sanitizer($products->fields['manufacturers_name']) . '</g:brand>';
						$content["condition"] = ($products->fields['products_condition'] != '' ? '<g:condition>' . $products->fields['products_condition'] . '</g:condition>' : '<g:condition>' . GOOGLE_FROOGLE_CONDITION . '</g:condition>');
						
						$product_type = zen_froogle_get_category($products->fields['products_id']);
						array_pop($product_type);
						$product_type = implode(",", $product_type);
						$product_type = htmlentities($product_type);
						$content["product_type"] = (GOOGLE_BASE_ASA == 'true' ? ($products->fields['products_category'] != '' ? '<g:product_type>' . $products->fields['products_category'] . '</g:product_type>' : '<g:product_type>' . $product_type . '</g:product_type>') : '<g:product_type>' . $product_type . '</g:product_type>');
					
						$content["expiration_date"] = '<g:expiration_date>' . zen_froogle_expiration_date($products->fields['base_date']) . '</g:expiration_date>';
						
						if (GOOGLE_FROOGLE_OFFER_ID != false) {
							if (GOOGLE_FROOGLE_OFFER_ID == 'id') {
								$content["id"] = '<g:id>' . $products->fields['products_id'] . '</g:id>';
							} else if (GOOGLE_FROOGLE_OFFER_ID == 'model') {
								$content["id"] = '<g:id>' . zen_froogle_sanita($products->fields['products_model']) . '</g:id>';
							} else if (GOOGLE_FROOGLE_OFFER_ID == 'UPC') {
								$content["id"] = '<g:id>' . zen_froogle_sanita($products->fields['products_upc']) . '</g:id>';
							} else if (GOOGLE_FROOGLE_OFFER_ID == 'ISBN') {
								$content["id"] = '<g:id>' . zen_froogle_sanita($products->fields['products_isbn']) . '</g:id>';
							}
						}

						$content["guid"] = '<guid isPermaLink="false">' . $products->fields['products_id'] . '</guid>';
						if (zen_froogle_image_url($products->fields['products_image']) != '') {
							$content["image_link"] = '<g:image_link>' . zen_froogle_image_url($products->fields['products_image']) . '</g:image_link>';
						}
						$content["link"] = '<link>' . $href . '</link>';
						$content["price"] = '<g:price>' . number_format($price, 2, '.', '') . '</g:price>';
						$content["mpn"] = '<g:mpn>' . zen_froogle_sanita($products->fields['products_model'], true) . '</g:mpn>';
						if (GOOGLE_BASE_ASA == 'true' && $products->fields['products_upc'] != '') {
							$content["upc"] = '<g:upc>' . zen_froogle_sanita($products->fields['products_upc'], true) . '</g:upc>';
						}
						if (GOOGLE_BASE_ASA == 'true' && $products->fields['products_isbn'] != '') {
							$content["isbn"] = '<g:isbn>' . zen_froogle_sanita($products->fields['products_isbn'], true) . '</g:isbn>';
						}
						if (GOOGLE_FROOGLE_IN_STOCK == 'true') {
							$content["quantity"] = '<g:quantity>' . $products->fields['products_quantity'] . '</g:quantity>';
						}
						if (GOOGLE_FROOGLE_LANGUAGE_DISPLAY == 'true') {
							$content["language"] = '<g:language>' . $languages->fields['code'] . '</g:language>';	
						}
						if (GOOGLE_FROOGLE_CURRENCY_DISPLAY == 'true') {
							$content["currency"] = '<g:currency>' . GOOGLE_FROOGLE_CURRENCY . '</g:currency>';
						}
						if (GOOGLE_FROOGLE_TAX_DISPLAY == 'true') {
							$content["tax_region"] = '<g:tax_region>' . GOOGLE_FROOGLE_TAX_REGION . '</g:tax_region>';
						}
						if(GOOGLE_FROOGLE_PICKUP != 'do not display') {
							$content["pickup"] = '<g:pickup>' . GOOGLE_FROOGLE_PICKUP . '</g:pickup>';
						}
						if(GOOGLE_BASE_WEIGHT == 'true') {
							$content["weight"] = '<g:weight>' . $products->fields['products_weight'] . ' ' . GOOGLE_BASE_UNITS . '</g:weight>';
						}
						$content["description"] = '<description>' . zen_xml_sanitizer($products->fields['products_description']) . '</description>';
						$content["item_end"] = '</item>';
						zen_froogle_fwrite($content, "a");
					}
				}
				$products->MoveNext();
			}
			$content = array();
			$content["channel"] = "\n" . '</channel>';
			$content["rss"] = '</rss>';
			zen_froogle_fwrite($content, "a");
			chmod($outfile, 0655);
		} else {
			echo ERROR_GOOGLE_FROOGLE_OPEN_FILE . NL;
			die;
		}

		

	$timer_feed = microtime_float()-$stimer_feed;
	
	echo TEXT_GOOGLE_FROOGLE_FEED_COMPLETE . ' ' . GOOGLE_FROOGLE_TIME_TAKEN . ' ' . sprintf("%f " . TEXT_GOOGLE_FROOGLE_FEED_SECONDS, number_format($timer_feed, 6) ) . ' ' . $anti_timeout_counter . TEXT_GOOGLE_FROOGLE_FEED_RECORDS . NL;	
}

if (isset($_GET['upload']) && $_GET['upload'] == "yes") {
	echo TEXT_GOOGLE_FROOGLE_UPLOAD_STARTED . NL;
	if(ftp_file_upload(GOOGLE_FROOGLE_SERVER, GOOGLE_FROOGLE_USERNAME, GOOGLE_FROOGLE_PASSWORD, DIR_FS_CATALOG . GOOGLE_FROOGLE_DIRECTORY . GOOGLE_FROOGLE_OUTPUT_FILENAME)) {
		echo TEXT_GOOGLE_FROOGLE_UPLOAD_OK . NL;
		$db->execute("update " . TABLE_CONFIGURATION . " set configuration_value = '" . date("Y/m/d H:i:s") . "' where configuration_key='GOOGLE_FROOGLE_UPLOADED_DATE'");
	} else {
		echo TEXT_GOOGLE_FROOGLE_UPLOAD_FAILED . NL;
	}
}


	function zen_froogle_fwrite($output='', $mode) {
		$output = implode("\n", $output);
		if(strtolower(CHARSET) != 'utf-8') {
			$output = utf8_encode($output);
		} else {
			$output = $output;
		}
		$fp = fopen(DIR_FS_CATALOG . GOOGLE_FROOGLE_DIRECTORY . GOOGLE_FROOGLE_OUTPUT_FILENAME, $mode);
		$retval = fwrite($fp, $output, GOOGLE_FROOGLE_OUTPUT_BUFFER_MAXSIZE);
		return $retval;
	}
	
	function trim_array($x) {
   		if (is_array($x)) {
       		return array_map('trim_array', $x);
   		} else {
   			return trim($x);
		}
	}
	
	function zen_google_base_categories($words, $compwords, $charge) {
		if ($words != '') {
			$match = 0;
			$compwords = split(",", $compwords);
			$words = split(",", $words);
			foreach ($words as $word) {
				foreach ($compwords as $compword) {
					if (trim(strtolower($compword)) == (trim(strtolower($word)))) {
						$match++;
						break;
					}
				}
			}
			if ($match > 0) {
				return true;
			} else {
				return false;
			}
		} else { // if $words is empty, return either true or false depending on positive or negative words
			if ($charge == 1) {
				return true;
			} else if ($charge == 2) {
				return false;
			}
		}
	}

	function zen_froogle_get_category($products_id) {
		global $categories_array, $db;
		static $p2c;
		if(!$p2c) {
			$q = $db->Execute("SELECT *
												FROM " . TABLE_PRODUCTS_TO_CATEGORIES);
			while (!$q->EOF) {
				if(!isset($p2c[$q->fields['products_id']]))
					$p2c[$q->fields['products_id']] = $q->fields['categories_id'];
				$q->MoveNext();
			}
		}
		if(isset($p2c[$products_id])) {
			$retval = $categories_array[$p2c[$products_id]]['name'];
			$cPath = $categories_array[$p2c[$products_id]]['cPath'];
		} else {
			$cPath = $retval =  "";
		}
		return array($retval, $cPath);
	}

	function zen_froogle_category_tree($id_parent=0, $cPath='', $cName='', $cats=array()){
		global $db, $languages;
		$cat = $db->Execute("SELECT c.categories_id, c.parent_id, cd.categories_name
												 FROM " . TABLE_CATEGORIES . " c
													 LEFT JOIN " . TABLE_CATEGORIES_DESCRIPTION . " cd on c.categories_id = cd.categories_id
												 WHERE c.parent_id = '" . (int)$id_parent . "'
												 AND cd.language_id='" . (int)$languages->fields['languages_id'] . "'
												 AND c.categories_status= '1'",
												 '', false, 150);
		while (!$cat->EOF) {
			$cats[$cat->fields['categories_id']]['name'] = (zen_not_null($cName) ? $cName . ', ' : '') . trim($cat->fields['categories_name']); // previously used zen_froogle_sanita instead of trim
			$cats[$cat->fields['categories_id']]['cPath'] = (zen_not_null($cPath) ? $cPath . '_' : '') . $cat->fields['categories_id'];
			if (zen_has_category_subcategories($cat->fields['categories_id'])) {
				$cats = zen_froogle_category_tree($cat->fields['categories_id'], $cats[$cat->fields['categories_id']]['cPath'], $cats[$cat->fields['categories_id']]['name'], $cats);
			}
			$cat->MoveNext();
		}
		return $cats;
	}

	function zen_froogle_sanita($str, $rt=false) { // currently using zen_xml_sanitizer below instead of zen_froogle_sanita
		$str = strip_tags($str);
		$str = str_replace(array("\t" , "\n", "\r"), ' ', $str);
		$str = preg_replace('/\s\s+/', ' ', $str);
//	$str = str_replace(array("&reg;", "®", "&copy;", "©", "&trade;", "™"), ' ', $str);
		$str = htmlentities(html_entity_decode($str));
		$in = $out = array();
		$in[] = "&reg;"; $out[] = '(r)';
		$in[] = "&copy;"; $out[] = '(c)';
		$in[] = "&trade;"; $out[] = '(tm)';
//		$str = str_replace($in, $out, $str);
		if($rt) {
			$str = str_replace(" ", "&nbsp;", $str);
			$str = str_replace("&nbsp;", "", $str);
		}
		$str = trim($str);
		return $str;
	}
			
	function zen_xml_sanitizer ($str) {
		$_strip_search = array("![\t ]+$|^[\t ]+!m",'%[\r\n]+%m'); // remove CRs and newlines
		$_strip_replace = array('',' ');
		$_cleaner_array = array(">" => "> ", "&reg;" => "", "®" => "", "&trade;" => "", "™" => "", "\t" => "", "    " => "");
		$str = html_entity_decode($str);
		$str = strtr($str, $_cleaner_array);
		$str = preg_replace($_strip_search, $_strip_replace, $str);
		$str = strip_tags($str);
		$str = eregi_replace("[^[:alnum:][:space:].,!()'-_/+=?]", "", $str);
		$str = htmlentities($str);
		//$partial_entity_array = array("quot;", "lt;", "gt;", "amp;", "apos;", "nbsp;", "deg;", "plusmn;", "ordm;", "sect;", "up2;", "circ;", "up3;", "uml;", "acute;", "ordf;", "frac12;");
		//$full_entity_array = array("&quot;", "&lt;", "&gt;", "&amp;", "&apos;", " ", "deg", "+/-", "", "", "^2", "^", "^3", "", "", "", "1/2");
		//$str = str_replace($partial_entity_array, $full_entity_array, $str);
		return $str;
	}

	function zen_froogle_image_url($products_image) {
		if($products_image == "") return "";

		$products_image_extention = substr($products_image, strrpos($products_image, '.'));
		$products_image_base = ereg_replace($products_image_extention, '', $products_image);
		$products_image_medium = $products_image_base . IMAGE_SUFFIX_MEDIUM . $products_image_extention;
		$products_image_large = $products_image_base . IMAGE_SUFFIX_LARGE . $products_image_extention;

		// check for a medium image else use small
		if (!file_exists(DIR_WS_IMAGES . 'medium/' . $products_image_medium)) {
		  $products_image_medium = DIR_WS_IMAGES . $products_image;
		} else {
		  $products_image_medium = DIR_WS_IMAGES . 'medium/' . $products_image_medium;
		}
		// check for a large image else use medium else use small
		if (!file_exists(DIR_WS_IMAGES . 'large/' . $products_image_large)) {
		  if (!file_exists(DIR_WS_IMAGES . 'medium/' . $products_image_medium)) {
		    $products_image_large = DIR_WS_IMAGES . $products_image;
		  } else {
		    $products_image_large = DIR_WS_IMAGES . 'medium/' . $products_image_medium;
		  }
		} else {
		  $products_image_large = DIR_WS_IMAGES . 'large/' . $products_image_large;
		}
		if (function_exists('handle_image')) {
			$image_ih = handle_image($products_image_large, '', MEDIUM_IMAGE_WIDTH, MEDIUM_IMAGE_HEIGHT, ''); // medium should be enough, it is 150 x 120 while Google needs 90 x 90
			$retval = (HTTP_SERVER . DIR_WS_CATALOG . $image_ih[0]);
		} else {
			$retval = (HTTP_SERVER . DIR_WS_CATALOG . $products_image_large); // medium should be enough, it is 150 x 120 while Google needs 90 x 90
		}
		return $retval;
	}

	function zen_froogle_expiration_date($base_date) {
		if(GOOGLE_FROOGLE_EXPIRATION_BASE == 'now')
			$expiration_date = time();
		else
			$expiration_date = strtotime($base_date);
		$expiration_date += GOOGLE_FROOGLE_EXPIRATION_DAYS*24*60*60;
		$retval = (date('Y-m-d', $expiration_date));
		return $retval;
	}

	function ftp_file_upload($url, $login, $password, $local_file, $ftp_dir='', $ftp_file=false, $ssl=false, $ftp_mode=FTP_ASCII) {
		if(!is_callable('ftp_connect')) {
			echo FTP_FAILED . NL;
			return false;
		}
		if(!$ftp_file)
			$ftp_file = basename($local_file);
		ob_start();
		if($ssl)
			$cd = ftp_ssl_connect($url);
		else
			$cd = ftp_connect($url);
		if (!$cd) {
			$out = ftp_get_error_from_ob();
			echo FTP_CONNECTION_FAILED . ' ' . $url . NL;
			echo $out . NL;
			return false;
		}
		echo FTP_CONNECTION_OK . ' ' . $url . NL;
		$login_result = ftp_login($cd, $login, $password);
		if (!$login_result) {
			$out = ftp_get_error_from_ob();
//			echo FTP_LOGIN_FAILED . FTP_USERNAME . ' ' . $login . FTP_PASSWORD . ' ' . $password . NL;
			echo FTP_LOGIN_FAILED . NL;
			echo $out . NL;
			ftp_close($cd);
			return false;
		}
//		echo FTP_LOGIN_OK . FTP_USERNAME . ' ' . $login . FTP_PASSWORD . ' ' . $password . NL;
		echo FTP_LOGIN_OK . NL;
		if ($ftp_dir != "") {
			if (!ftp_chdir($cd, $ftp_dir)) {
				$out = ftp_get_error_from_ob();
				echo FTP_CANT_CHANGE_DIRECTORY . '&nbsp;' . $url . NL;
				echo $out . NL;
				ftp_close($cd);
				return false;
			}
		}
		echo FTP_CURRENT_DIRECTORY . '&nbsp;' . ftp_pwd($cd) . NL;
		$attempt = 0;
		$success = false;
		while ($attempt < 3 || !$success) { // two attempts, one with pasv off, one with on
			if ($attempt = 0) {
				ftp_pasv($cd, false); // on first attempt, turn pasv off
			} else {
				ftp_pasv($cd, true); // on second attempt, turn pasv on
			}
			$attempt++;
			$upload = ftp_put($cd, $ftp_file, $local_file, $ftp_mode);
			$out = ftp_get_error_from_ob();
			$raw = ftp_rawlist($cd, $ftp_file, true);
			for($i=0,$n=sizeof($raw);$i<$n;$i++){
				$out .= $raw[$i] . '<br/>';
			}
			if (!$upload) {
				$success = false;
				if ($attempt = 1) {
					echo "First attempt (passive mode off): " . FTP_UPLOAD_FAILED . NL;
				} else if ($attempt = 2) {
					echo "Second attempt (passive mode on): " . FTP_UPLOAD_FAILED . NL;
				}
				if(isset($raw[0])) echo $raw[0] . NL;
				echo $out . NL;
				ftp_close($cd);
				return false;
			} else {
				$success = true;
				if ($attempt = 1) {
					echo "First attempt (passive mode off): " . FTP_UPLOAD_SUCCESS . NL;
				} else if ($attempt = 2) {
					echo "Second attempt (passive mode on): " . FTP_UPLOAD_SUCCESS . NL;
				}
				echo $raw[0] . NL;
				echo $out . NL;
			}
			ftp_close($cd);
			return true;
		}
	}

	function ftp_get_error_from_ob() {
		$out = ob_get_contents();
		ob_end_clean();
		$out = str_replace(array('\\', '<!--error-->', '<br>', '<br />', "\n", 'in <b>'),array('/', '', '', '', '', ''),$out);
		if(strpos($out, DIR_FS_CATALOG) !== false){
			$out = substr($out, 0, strpos($out, DIR_FS_CATALOG));
		}
		return $out;
	}

function microtime_float() {
   list($usec, $sec) = explode(" ", microtime());
   return ((float)$usec + (float)$sec);
}
?>