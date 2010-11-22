<?php
/**
 * paypal.php payment module class for Paypal IPN payment method
 *
 * @package paymentMethod
 * @copyright Copyright 2003-2007 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: paypal.php 6528 2007-06-25 23:25:27Z drbyte $
 */

define('MODULE_PAYMENT_PAYPAL_TAX_OVERRIDE', 'true');

// ensure dependencies are loaded
  include_once((IS_ADMIN_FLAG === true ? DIR_FS_CATALOG_MODULES : DIR_WS_MODULES) . 'payment/paypal/paypal_functions.php');
/**
 * paypal IPN payment method class
 *
 */
class paypal extends base {
  /**
   * string repesenting the payment method
   *
   * @var string
   */
  var $code;
  /**
   * $title is the displayed name for this payment method
   *
   * @var string
    */
  var $title;
  /**
   * $description is a soft name for this payment method
   *
   * @var string
    */
  var $description;
  /**
   * $enabled determines whether this module shows or not... in catalog.
   *
   * @var boolean
    */
  var $enabled;
  /**
    * constructor
    *
    * @param int $paypal_ipn_id
    * @return paypal
    */
  function paypal($paypal_ipn_id = '') {
    global $order, $messageStack;
    $this->code = 'paypal';
    if (IS_ADMIN_FLAG === true) {
      $this->title = MODULE_PAYMENT_PAYPAL_TEXT_ADMIN_TITLE; // Payment Module title in Admin
      if (IS_ADMIN_FLAG === true && defined('MODULE_PAYMENT_PAYPAL_IPN_DEBUG') && MODULE_PAYMENT_PAYPAL_IPN_DEBUG != 'Off') $this->title .= '<span class="alert"> (debug mode active)</span>';
      if (IS_ADMIN_FLAG === true && MODULE_PAYMENT_PAYPAL_TESTING == 'Test') $this->title .= '<span class="alert"> (dev/test mode active)</span>';
    } else {
      $this->title = MODULE_PAYMENT_PAYPAL_TEXT_CATALOG_TITLE; // Payment Module title in Catalog
    }
    $this->description = MODULE_PAYMENT_PAYPAL_TEXT_DESCRIPTION;
    $this->sort_order = MODULE_PAYMENT_PAYPAL_SORT_ORDER;
    $this->enabled = ((MODULE_PAYMENT_PAYPAL_STATUS == 'True') ? true : false);
    if ((int)MODULE_PAYMENT_PAYPAL_ORDER_STATUS_ID > 0) {
      $this->order_status = MODULE_PAYMENT_PAYPAL_ORDER_STATUS_ID;
    }
    if (is_object($order)) $this->update_status();
    if (MODULE_PAYMENT_PAYPAL_TESTING == 'Test') {
      if (!file_exists(DIR_WS_CATALOG . 'ipn_test.php')) {
        $messageStack->add('header', 'WARNING: PayPal TEST mode enabled but ipn_test*.php files not found', 'caution');
      }
      $this->form_action_url = DIR_WS_CATALOG . 'ipn_test.php';
    } else {
      $this->form_action_url = 'https://' . MODULE_PAYMENT_PAYPAL_HANDLER;
    }
  }
  /**
   * calculate zone matches and flag settings to determine whether this module should display to customers or not
    *
    */
  function update_status() {
    global $order, $db;

    if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_PAYPAL_ZONE > 0) ) {
      $check_flag = false;
      $check_query = $db->Execute("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_PAYPAL_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
      while (!$check_query->EOF) {
        if ($check_query->fields['zone_id'] < 1) {
          $check_flag = true;
          break;
        } elseif ($check_query->fields['zone_id'] == $order->billing['zone_id']) {
          $check_flag = true;
          break;
        }
        $check_query->MoveNext();
      }

      if ($check_flag == false) {
        $this->enabled = false;
      }
    }
  }
  /**
   * JS validation which does error-checking of data-entry if this module is selected for use
   * (Number, Owner, and CVV Lengths)
   *
   * @return string
    */
  function javascript_validation() {
    return false;
  }
  /**
   * Displays Credit Card Information Submission Fields on the Checkout Payment Page
   * In the case of paypal, this only displays the paypal title
   *
   * @return array
    */
  function selection() {
    return array('id' => $this->code,
                 'module' => $this->title);
  }
  /**
   * Normally evaluates the Credit Card Type for acceptance and the validity of the Credit Card Number & Expiration Date
   * Since paypal module is not collecting info, it simply skips this step.
   *
   * @return boolean
   */
  function pre_confirmation_check() {
    return false;
  }
  /**
   * Display Credit Card Information on the Checkout Confirmation Page
   * Since none is collected for paypal before forwarding to paypal site, this is skipped
   *
   * @return boolean
    */
  function confirmation() {
    return false;
  }
  /**
   * Build the data and actions to process when the "Submit" button is pressed on the order-confirmation screen.
   * This sends the data to the payment gateway for processing.
   * (These are hidden fields on the checkout confirmation page)
   *
   * @return string
    */
  function process_button() {
    global $db, $order, $currencies, $currency;
    $options = array();
    $optionsCore = array();
    $optionsPhone = array();
    $optionsShip = array();
    $optionsTrans = array();
    $buttonArray = array();

    $this->totalsum = $order->info['total'];

    // save the session stuff permanently in case paypal loses the session
    $_SESSION['ppipn_key_to_remove'] = session_id();
    $db->Execute("delete from " . TABLE_PAYPAL_SESSION . " where session_id = '" . zen_db_input($_SESSION['ppipn_key_to_remove']) . "'");

    $sql = "insert into " . TABLE_PAYPAL_SESSION . " (session_id, saved_session, expiry) values (
            '" . zen_db_input($_SESSION['ppipn_key_to_remove']) . "',
            '" . base64_encode(serialize($_SESSION)) . "',
            '" . (time() + (1*60*60*24*2)) . "')";

    $db->Execute($sql);

    // Determine locale for paypal login page -- (determines language display content setting)
    $lang_code = 'US';
    $storeISO = zen_get_countries(STORE_COUNTRY, true);
    if (in_array(strtoupper($storeISO['countries_iso_code_2']), array('US', 'AU', 'DE', 'FR', 'IT', 'GB', 'ES'))) {
      $lang_code = strtoupper($storeISO['countries_iso_code_2']);
    } elseif (in_array(strtoupper($_SESSION['languages_code']), array('EN', 'US', 'AU', 'DE', 'FR', 'IT', 'GB', 'ES'))) {
      $lang_code = $_SESSION['languages_code'];
      if (strtoupper($lang_code) == 'EN') $lang_code = 'US';
    }
    $my_currency = select_pp_currency();
    $this->transaction_currency = $my_currency;

    $this->transaction_amount = ($this->totalsum * $currencies->get_value($my_currency));

    $telephone = preg_replace('/\D/', '', $order->customer['telephone']);
    if ($telephone != '') {
      $optionsPhone['H_PhoneNumber'] = $telephone;
      if (in_array($order->customer['country']['iso_code_2'], array('US','CA'))) {
        $optionsPhone['night_phone_a'] = substr($telephone,0,3);
        $optionsPhone['night_phone_b'] = substr($telephone,3,3);
        $optionsPhone['night_phone_c'] = substr($telephone,6,4);
        $optionsPhone['day_phone_a'] = substr($telephone,0,3);
        $optionsPhone['day_phone_b'] = substr($telephone,3,3);
        $optionsPhone['day_phone_c'] = substr($telephone,6,4);
    } else {
        $optionsPhone['night_phone_b'] = $telephone;
        $optionsPhone['day_phone_b'] = $telephone;
      }
    }

    $optionsCore = array(
                   'bn' => 'zencart',
                   'cmd' => '_ext-enter',
                   'rm' => 2,
                   'mrb' => 'R-6C7952342H795591R',
                   'pal' => '9E82WJBKKGPLQ',
                   'page_style' => MODULE_PAYMENT_PAYPAL_PAGE_STYLE,
                   'lc' => $lang_code,
                   'custom' => zen_session_name() . '=' . zen_session_id(),
                   'charset' => CHARSET,
                   'return' => zen_href_link(FILENAME_CHECKOUT_PROCESS, 'referer=paypal', 'SSL'),
                   'cancel_return' => zen_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'),
                   'shopping_url' => zen_href_link(FILENAME_SHOPPING_CART, '', 'SSL'),
                   'notify_url' => zen_href_link('ipn_main_handler.php', '', 'SSL',false,false,true),
                   'redirect_cmd' => '_xclick',
                   'business' => MODULE_PAYMENT_PAYPAL_BUSINESS_ID,
                   );
    $optionsCust = array(
                   'first_name' => replace_accents($order->customer['firstname']),
                   'last_name' => replace_accents($order->customer['lastname']),
                   'address1' => replace_accents($order->customer['street_address']),
                   'city' => replace_accents($order->customer['city']),
                   'state' => zen_get_zone_code($order->customer['country']['id'], $order->customer['zone_id'], $order->customer['zone_id']),
                   'zip' => $order->customer['postcode'],
                   'country' => $order->customer['country']['iso_code_2'],
                   'email' => $order->customer['email_address'],
                   );
    if ($order->customer['suburb'] != '') $optionsCust['address2'] = $order->customer['suburb'];
    $optionsShip = array(
                   //'address_override' => MODULE_PAYMENT_PAYPAL_ADDRESS_OVERRIDE,
                   'no_shipping' => MODULE_PAYMENT_PAYPAL_ADDRESS_REQUIRED,
                   );
    $optionsTrans = array(
                   'currency_code' => $my_currency,
                   'paypal_order_id' => $paypal_order_id,
                   //'invoice' => '',
                   'item_name' => MODULE_PAYMENT_PAYPAL_PURCHASE_DECRIPTION_TITLE,
                   'item_number' => 'Store Receipt',
                   //'num_cart_items' => sizeof($order->products),
                   'upload' => sizeof($order->products),
                   'amount' => number_format($this->transaction_amount, $currencies->get_decimal_places($my_currency)),
                   'shipping' => '0.00',
                    );
    if (MODULE_PAYMENT_PAYPAL_TAX_OVERRIDE == 'true') $optionsTrans['tax'] = '0.00';
    if (MODULE_PAYMENT_PAYPAL_TAX_OVERRIDE == 'true') $optionsTrans['tax_cart'] = '0.00';
    $options = array_merge($optionsCore, $optionsCust, $optionsPhone, $optionsShip, $optionsTrans);

    ipn_debug_email('Keys for submission: ' . print_r($options, true));

    // build the button fields
    foreach ($options as $name => $value) {
      // remove quotation marks
      $value = str_replace('"', '', $value);
      // check for invalid chars
      if (preg_match('/[^a-zA-Z_0-9]/', $name)) {
        ipn_debug_email('datacheck - ABORTING - preg_match found invalid submission key: ' . $name . ' (' . $value . ')');
        break;
      }
      // do we need special handling for & and = symbols?
      //if (strpos($value, '&') !== false || strpos($value, '=') !== false) $value = urlencode($value);

      $buttonArray[] = zen_draw_hidden_field($name, $value);
    }
    $process_button_string = implode("\n", $buttonArray) . "\n";

    $_SESSION['paypal_transaction_info'] = array($this->transaction_amount, $this->transaction_currency);
    return $process_button_string;
  }
  /**
   * Store transaction info to the order and process any results that come back from the payment gateway
   */
  function before_process() {
    global $order_total_modules;
    list($this->transaction_amount, $this->transaction_currency) = $_SESSION['paypal_transaction_info'];
    unset($_SESSION['paypal_transaction_info']);
    if (isset($_GET['referer']) && $_GET['referer'] == 'paypal') {
      $this->notify('NOTIFY_PAYMENT_PAYPAL_RETURN_TO_STORE');
      if (MODULE_PAYMENT_PAYPAL_TESTING == 'Test') {
        // simulate call to ipn_handler.php here
        ipn_simulate_ipn_handler((int)$_GET['count']);
      }
      if (defined('MODULE_PAYMENT_PAYPAL_PDTTOKEN') && MODULE_PAYMENT_PAYPAL_PDTTOKEN != '') {
        $pdtStatus = $this->_getPDTresults($this->transaction_amount, $this->transaction_currency);
      } else {
        $pdtStatus = false;
      }
      if ($pdtStatus == false) {
        $_SESSION['cart']->reset(true);
        unset($_SESSION['sendto']);
        unset($_SESSION['billto']);
        unset($_SESSION['shipping']);
        unset($_SESSION['payment']);
        unset($_SESSION['comments']);
        unset($_SESSION['cot_gv']);
        $order_total_modules->clear_posts();//ICW ADDED FOR CREDIT CLASS SYSTEM
        zen_redirect(zen_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL'));
      } else {
        // PDT was good, so delete IPN session from PayPal table -- housekeeping.
        global $db;
        $db->Execute("delete from " . TABLE_PAYPAL_SESSION . " where session_id = '" . zen_db_input($_SESSION['ppipn_key_to_remove']) . "'");
        unset($_SESSION['ppipn_key_to_remove']);
        $_SESSION['paypal_transaction_PDT_passed'] = true;
        return true;
      }
    } else {
      $this->notify('NOTIFY_PAYMENT_PAYPAL_CANCELLED_DURING_CHECKOUT');
      zen_redirect(zen_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
    }
  }
  /**
    * Checks referrer
    *
    * @param string $zf_domain
    * @return boolean
    */
  function check_referrer($zf_domain) {
    return true;
  }
  /**
    * Build admin-page components
    *
    * @param int $zf_order_id
    * @return string
    */
  function admin_notification($zf_order_id) {
    global $db;
    $output = '';
    $sql = "select * from " . TABLE_PAYPAL . " where zen_order_id = '" . (int)$zf_order_id . "' order by paypal_ipn_id DESC LIMIT 1";
    $ipn = $db->Execute($sql);
    if ($ipn->RecordCount() > 0) require(DIR_FS_CATALOG. DIR_WS_MODULES . 'payment/paypal/paypal_admin_notification.php');
    return $output;
  }
  /**
   * Post-processing activities
   *
   * @return boolean
    */
  function after_process() {
    global $insert_id, $db;
    if ($_SESSION['paypal_transaction_PDT_passed'] != true) {
      $_SESSION['order_created'] = '';
      unset($_SESSION['paypal_transaction_PDT_passed']);
      return false;
    } else {
      unset($_SESSION['paypal_transaction_PDT_passed']);
      $sql = "insert into " . TABLE_ORDERS_STATUS_HISTORY . " (comments, orders_id, orders_status_id, date_added) values (:orderComments, :orderID, :orderStatus, now() )";
      $sql = $db->bindVars($sql, ':orderComments', 'PayPal status: ' . $this->pdtData['payment_status'] . ' ' . ' @ ' . $this->pdtData['payment_date'] . "\n" . ' Trans ID:' . $this->pdtData['txn_id'] . "\n" . ' Amount: ' . $this->pdtData['mc_gross'] . ' ' . $this->pdtData['mc_currency'] . '.', 'string');
      $sql = $db->bindVars($sql, ':orderID', $insert_id, 'integer');
      $sql = $db->bindVars($sql, ':orderStatus', $this->order_status, 'integer');
      $db->Execute($sql);
      ipn_debug_email('PDT NOTICE :: Order added: ' . $insert_id . "\n" . 'PayPal status: ' . $this->pdtData['payment_status'] . ' ' . ' @ ' . $this->pdtData['payment_date'] . "\n" . ' Trans ID:' . $this->pdtData['txn_id'] . "\n" . ' Amount: ' . $this->pdtData['mc_gross'] . ' ' . $this->pdtData['mc_currency']);
    }
  }
  /**
   * Used to display error message details
   *
   * @return boolean
    */
  function output_error() {
    return false;
  }
  /**
   * Check to see whether module is installed
   *
   * @return boolean
    */
  function check() {
    global $db;
    if (!isset($this->_check)) {
      $check_query = $db->Execute("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_PAYPAL_STATUS'");
      $this->_check = $check_query->RecordCount();
    }
    return $this->_check;
  }
  /**
   * Install the payment module and its configuration settings
    *
    */
  function install() {
    global $db;
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable PayPal Module', 'MODULE_PAYMENT_PAYPAL_STATUS', 'True', 'Do you want to accept PayPal payments?', '6', '0', 'zen_cfg_select_option(array(\'True\', \'False\'), ', now())");
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Business ID', 'MODULE_PAYMENT_PAYPAL_BUSINESS_ID','".STORE_OWNER_EMAIL_ADDRESS."', 'Primary email address for your PayPal account.<br />NOTE: This must match <strong>EXACTLY </strong>the primary email address on your PayPal account settings.  It <strong>IS case-sensitive</strong>, so please check your PayPal profile preferences at paypal.com and be sure to enter the EXACT same primary email address here.', '6', '2', now())");
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Transaction Currency', 'MODULE_PAYMENT_PAYPAL_CURRENCY', 'Selected Currency', 'Which currency should the order be sent to PayPal as? <br />NOTE: if an unsupported currency is sent to PayPal, it will be auto-converted to USD.', '6', '3', 'zen_cfg_select_option(array(\'Selected Currency\', \'Only USD\', \'Only AUD\', \'Only CAD\', \'Only EUR\', \'Only GBP\', \'Only CHF\', \'Only CZK\', \'Only DKK\', \'Only HKD\', \'Only HUF\', \'Only JPY\', \'Only NOK\', \'Only NZD\', \'Only PLN\', \'Only SEK\', \'Only SGD\', \'Only THB\'), ', now())");
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_PAYPAL_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '6', '4', 'zen_get_zone_class_title', 'zen_cfg_pull_down_zone_classes(', now())");
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Pending Notification Status', 'MODULE_PAYMENT_PAYPAL_PROCESSING_STATUS_ID', '" . DEFAULT_ORDERS_STATUS_ID .  "', 'Set the status of orders made with this payment module that are not yet completed to this value<br />(\'Pending\' recommended)', '6', '5', 'zen_cfg_pull_down_order_statuses(', 'zen_get_order_status_name', now())");
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Order Status', 'MODULE_PAYMENT_PAYPAL_ORDER_STATUS_ID', '2', 'Set the status of orders made with this payment module that have completed payment to this value<br />(\'Processing\' recommended)', '6', '6', 'zen_cfg_pull_down_order_statuses(', 'zen_get_order_status_name', now())");
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Refund Order Status', 'MODULE_PAYMENT_PAYPAL_REFUND_ORDER_STATUS_ID', '1', 'Set the status of orders that have been refunded made with this payment module to this value<br />(\'Pending\' recommended)', '6', '7', 'zen_cfg_pull_down_order_statuses(', 'zen_get_order_status_name', now())");
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_PAYPAL_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '8', now())");
    //     $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Handling Charge', 'MODULE_PAYMENT_PAYPAL_HANDLING', '0', 'The cost of handling. This is not quantity specific. The same handling will be charged regardless of the number of items purchased. If omitted or 0, no handling charges will be assessed.', '6', '15', now())");
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Address override', 'MODULE_PAYMENT_PAYPAL_ADDRESS_OVERRIDE', '', 'If set to 1 the address passed in via Zen Cart will override the customer PayPal-stored address. The customer will be shown their address from Zen Cart, but will not be able to edit it. If the address is not valid (i.e. missing required fields, including country) or not included, then no address will be shown.<br />Empty=No Override<br />1=Passed-in Address overrides customers PayPal-stored address', '6', '18', 'zen_cfg_select_option(array(\'\',\'1\'), ', now())");
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Shipping Address Options', 'MODULE_PAYMENT_PAYPAL_ADDRESS_REQUIRED', '1', 'The buyers shipping address. If set to 0 your customer will be prompted to include a shipping address. If set to 1 your customer will not be asked for a shipping address. If set to 2 your customer will be required to provide a shipping address.<br />0=Prompt<br />1=Not Asked<br />2=Required<br /><br /><strong>NOTE: If you allow your customers to enter their own shipping address, then MAKE SURE you verify the PayPal confirmation details to verify the proper address when filling orders. Zen Cart does not know if they choose an alternate shipping address compared to the one entered when placing an order.</strong>', '6', '20', 'zen_cfg_select_option(array(\'0\',\'1\',\'2\'), ', now())");
//    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Continue Button Text', 'MODULE_PAYMENT_PAYPAL_CBT', '', 'Sets the text for the Continue button on the PayPal Payment Complete page. <br />Requires Return URL to be set.<br />Leave blank if no customization required.', '6', '22', now())");
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Page Style', 'MODULE_PAYMENT_PAYPAL_PAGE_STYLE', 'Primary', 'Sets the Custom Payment Page Style for payment pages. The value of page_style is the same as the Page Style Name you chose when adding or editing the page style. You can add and edit Custom Payment Page Styles from the Profile subtab of the My Account tab on the PayPal site. If you would like to always reference your Primary style, set this to \"primary.\" If you would like to reference the default PayPal page style, set this to \"paypal\".', '6', '25', now())");
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Mode for PayPal web services<br /><br />Default:<br /><code>www.paypal.com/cgi-bin/webscr</code><br />or<br /><code>www.paypal.com/us/cgi-bin/webscr</code><br />or for the UK,<br /><code>www.paypal.com/uk/cgi-bin/webscr</code>', 'MODULE_PAYMENT_PAYPAL_HANDLER', 'www.paypal.com/cgi-bin/webscr', 'Choose the URL for PayPal live processing', '6', '73', '', now())");
// sandbox:  www.sandbox.paypal.com/cgi-bin/webscr
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function) values ('PDT Token (Payment Data Transfer)', 'MODULE_PAYMENT_PAYPAL_PDTTOKEN', '', 'Enter your PDT Token value here in order to activate transactions immediately after processing (if they pass validation).', '6', '25', now(), 'zen_cfg_password_display')");

    // Paypal testing options go here
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Debug Mode', 'MODULE_PAYMENT_PAYPAL_IPN_DEBUG', 'Off', 'Enable debug logging? <br />NOTE: This can REALLY clutter your email inbox!<br />Logging goes to the /includes/modules/payment/paypal/logs folder<br />Email goes to the store-owner address.<br /><strong>Leave OFF for normal operation.</strong>', '6', '71', 'zen_cfg_select_option(array(\'Off\',\'Log File\',\'Log and Email\'), ', now())");
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Status Live/Testing', 'MODULE_PAYMENT_PAYPAL_TESTING', 'Live', 'Set PayPal module to Live or Test', '6', '1', 'zen_cfg_select_option(array(\'Live\', \'Test\'), ', now())");
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Debug Email Address', 'MODULE_PAYMENT_PAYPAL_DEBUG_EMAIL_ADDRESS','".STORE_OWNER_EMAIL_ADDRESS."', 'The email address to use for PayPal debugging', '6', '72', now())");

    $this->notify('NOTIFY_PAYMENT_PAYPAL_INSTALLED');
  }
  /**
   * Remove the module and all its settings
    *
    */
  function remove() {
    global $db;
    $db->Execute("delete from " . TABLE_CONFIGURATION . " where configuration_key LIKE 'MODULE\_PAYMENT\_PAYPAL\_%'");
    $this->notify('NOTIFY_PAYMENT_PAYPAL_UNINSTALLED');
  }
  /**
   * Internal list of configuration keys used for configuration of the module
   *
   * @return array
    */
  function keys() {
    $keys_list = array(
                       'MODULE_PAYMENT_PAYPAL_STATUS',
                       'MODULE_PAYMENT_PAYPAL_BUSINESS_ID',
                       'MODULE_PAYMENT_PAYPAL_PDTTOKEN',
                       'MODULE_PAYMENT_PAYPAL_CURRENCY',
                       'MODULE_PAYMENT_PAYPAL_ZONE',
                       'MODULE_PAYMENT_PAYPAL_PROCESSING_STATUS_ID',
                       'MODULE_PAYMENT_PAYPAL_ORDER_STATUS_ID',
                       'MODULE_PAYMENT_PAYPAL_REFUND_ORDER_STATUS_ID',
                       'MODULE_PAYMENT_PAYPAL_SORT_ORDER',
                       //         'MODULE_PAYMENT_PAYPAL_HANDLING' ,
                       //         'MODULE_PAYMENT_PAYPAL_ADDRESS_OVERRIDE' ,
                       //         'MODULE_PAYMENT_PAYPAL_ADDRESS_REQUIRED' ,
                       //'MODULE_PAYMENT_PAYPAL_CBT' ,
                       'MODULE_PAYMENT_PAYPAL_PAGE_STYLE' ,
                       'MODULE_PAYMENT_PAYPAL_HANDLER',
                        );

    // Paypal testing/debug options go here:
    $keys_list[]='MODULE_PAYMENT_PAYPAL_IPN_DEBUG';
    if (isset($_GET['debug']) && $_GET['debug']=='on' && IS_ADMIN_FLAG === true) {
      $keys_list[]='MODULE_PAYMENT_PAYPAL_DEBUG_EMAIL_ADDRESS';  /* this defaults to store-owner-email-address */
      $keys_list[]='MODULE_PAYMENT_PAYPAL_TESTING';  /* this is for ipn_test tools, for developers only */
    }
    return $keys_list;
  }

  function _getPDTresults($orderAmount, $my_currency) {
    global $db;
    $ipnData  = ipn_postback('PDT');
    $respdata = $ipnData['info'];

    // parse the data
    $lines = explode("\n", $respdata);
    $this->pdtData = array();
    for ($i=1; $i<count($lines);$i++){
      if (!strstr($lines[$i], "=")) continue;
      list($key,$val) = explode("=", $lines[$i]);
      $this->pdtData[urldecode($key)] = urldecode($val);
    }

    ipn_debug_email('PDT Returned Data ' . print_r($this->pdtData, true));

    $_POST['mc_gross'] = $this->pdtData['mc_gross'];
    $_POST['mc_currency'] = $this->pdtData['mc_currency'];
    $_POST['business'] = $this->pdtData['business'];
    $_POST['receiver_email'] = $this->pdtData['receiver_email'];

    $PDTstatus = (ipn_validate_transaction($respdata, $this->pdtData, 'PDT') && valid_payment($orderAmount, $my_currency, 'PDT') && $this->pdtData['payment_status'] == 'Completed');
    if ($this->pdtData['payment_status'] != 'Completed') {
      ipn_debug_email('PDT WARNING :: Order not marked as "Completed".  Check for Pending reasons or wait for IPN to complete.' . "\n" . '[payment_status] => ' . $this->pdtData['payment_status'] . "\n" . '[pending_reason] => ' . $this->pdtData['pending_reason']);
    }

    $useTable = (MODULE_PAYMENT_PAYPAL_TESTING == 'Test') ? TABLE_PAYPAL_TESTING : TABLE_PAYPAL;
    $sql = "SELECT zen_order_id, paypal_ipn_id, payment_status, txn_type, pending_reason
                FROM " . $useTable . "
                WHERE txn_id = :transactionID OR parent_txn_id = :transactionID
                ORDER BY zen_order_id DESC  ";
    $sql = $db->bindVars($sql, ':transactionID', $this->pdtData['txn_id'], 'string');
    $ipn_id = $db->Execute($sql);
    if($ipn_id->RecordCount() != 0) {
      ipn_debug_email('PDT WARNING :: Transaction already exists. Perhaps IPN already added it.  PDT processing ended.');
      $pdtTXN_is_unique = false;
    } else {
      $pdtTXN_is_unique = true;
    }

    $PDTstatus = ($pdtTXN_is_unique && $PDTstatus);

    return $PDTstatus;
  }
}
?>