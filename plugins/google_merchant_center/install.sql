#GOOGLE BASE FEEDER
#

SET @configuration_group_id=0;
SELECT (@configuration_group_id:=configuration_group_id) 
FROM configuration_group 
WHERE configuration_group_title= 'Google Base Feeder Configuration' 
LIMIT 1;
DELETE FROM configuration WHERE configuration_group_id = @configuration_group_id AND @configuration_group_id != 0;
DELETE FROM configuration_group WHERE configuration_group_id = @configuration_group_id AND @configuration_group_id != 0;

INSERT INTO configuration_group (configuration_group_id, configuration_group_title, configuration_group_description, sort_order, visible) VALUES (NULL, 'Google Base Feeder Configuration', 'Set Google Base Options', '1', '1');
SET @configuration_group_id=last_insert_id();
UPDATE configuration_group SET sort_order = @configuration_group_id WHERE configuration_group_id = @configuration_group_id;

INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES 
(NULL, 'Google Base FTP Username', 'GOOGLE_FROOGLE_USERNAME', 'ftp_username', 'Enter your Google Base FTP username', @configuration_group_id, 0, NOW(), NULL, NULL),
(NULL, 'Google Base FTP Password', 'GOOGLE_FROOGLE_PASSWORD', 'ftp_password', 'Enter your Google Base FTP password', @configuration_group_id, 0, NOW(), NULL, NULL),
(NULL, 'Google Base Server', 'GOOGLE_FROOGLE_SERVER', 'uploads.google.com', 'Enter froogle server<br />default: hedwig.google.com', @configuration_group_id, 0, NOW(), NULL, NULL),
(NULL, 'Google Base PASV', 'GOOGLE_BASE_PASV', 'true', 'Turn PASV mode on or off for FTP upload?', @configuration_group_id, 0, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'Store Address', 'GOOGLE_BASE_ADDRESS', 'http://www.domain.com', 'Enter your website address', @configuration_group_id, 1, NOW(), NULL, NULL),
(NULL, 'Store Description', 'GOOGLE_BASE_DESCRIPTION', '', 'Enter a short description of your store', @configuration_group_id, 1, NOW(), NULL, NULL),
(NULL, 'Numinix Product Fields', 'GOOGLE_BASE_ASA', 'false', 'Activate Numinix Product Fields (requires separate add-on)?', @configuration_group_id, 2, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'UPC/ISBN', 'GOOGLE_BASE_ASA_UPC', 'false', 'If using Numinix Product Fields, include UPC/ISBN?', @configuration_group_id, 2, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'Description 2', 'GOOGLE_BASE_ASA_DESCRIPTION_2', 'false', 'If using Numinix Product Fields, append description 2 to description?', @configuration_group_id, 2, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),

(NULL, 'Enable Map Pricing', 'GOOGLE_BASE_MAP_PRICING', 'false', 'Enable MAP Pricing (requires separate add-on)?', @configuration_group_id, 2, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'), 

(NULL, 'Expiration Date Base', 'GOOGLE_FROOGLE_EXPIRATION_BASE', 'now', 'Expiration Date Base:<ul><li>now - add Adjust to current date;</li><li>product - add Adjust to product date (max(date_added, last_modified, date_available))</li></ul>', @configuration_group_id, 2, NOW(), NULL, 'zen_cfg_select_option(array(\'now\', \'product\'),'),
(NULL, 'Expiration Date Adjust', 'GOOGLE_FROOGLE_EXPIRATION_DAYS', '30', 'Expiration Date Adjust in Days', @configuration_group_id, 2, NOW(), NULL, NULL),

(NULL, 'Show Default Currency', 'GOOGLE_FROOGLE_CURRENCY_DISPLAY', 'true', 'Display Currency', @configuration_group_id, 3, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'Default Currency', 'GOOGLE_FROOGLE_CURRENCY', 'USD', 'Select currency', @configuration_group_id, 5, NOW(), NULL, 'zen_cfg_pull_down_currencies('),
(NULL, 'Show Offer ID', 'GOOGLE_FROOGLE_OFFER_ID', 'id', 'A unique alphanumeric identifier for the item - products_id code. ', @configuration_group_id, 6, NOW(), NULL, 'zen_cfg_select_option(array(\'id\', \'model\', \'UPC\', \'ISBN\', \'false\'),'),
(NULL, 'Show Quantity', 'GOOGLE_FROOGLE_IN_STOCK', 'false', 'Display products quantity?', @configuration_group_id, 7, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'Include Zero Quantity', 'GOOGLE_BASE_ZERO_QUANTITY', 'false', 'Include products with zero quantity?', @configuration_group_id, 7, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'Default Quantity', 'GOOGLE_BASE_DEFAULT_QUANTITY', '0', 'What is the default quantity for products with zero quantity?', @configuration_group_id, 7, NOW(), NULL, NULL), 
(NULL, 'Shipping Options', 'GOOGLE_FROOGLE_SHIPPING', '', 'The shipping options available for an item', @configuration_group_id, 8, NOW(), NULL, NULL),
(NULL, 'Condition', 'GOOGLE_FROOGLE_CONDITION', 'new', 'Choose your default condition', @configuration_group_id, 12, NOW(), NULL, 'zen_cfg_select_option(array(\'new\', \'used\', \'refurbished\'),'),

(NULL, 'Show Feed Lanugage', 'GOOGLE_FROOGLE_LANGUAGE_DISPLAY', 'false', 'Display Feed Language', @configuration_group_id, 13, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'Feed Language', 'GOOGLE_FROOGLE_LANGUAGE', 'English', 'If Show Feed Language is True, what is your feed language?<br />default = en', @configuration_group_id, 14, NOW(), NULL, 'zen_cfg_pull_down_languages_list('),

(NULL, 'Magic SEO URLs', 'GOOGLE_BASE_MAGIC_SEO_URLS', 'false', 'Output Magic SEO URLs (separate module required)?', @configuration_group_id, 14, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),

(NULL, 'Output File Name', 'GOOGLE_FROOGLE_OUTPUT_FILENAME', 'domain', 'Set the name of your froogle output file', @configuration_group_id, 19, NOW(), NULL, NULL),
(NULL, 'Compress Feed File', 'GOOGLE_FROOGLE_COMPRESS', 'false', 'Compress Google froogle file', @configuration_group_id, 20, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'Uploaded date', 'GOOGLE_FROOGLE_UPLOADED_DATE', '', 'Date and time of the last upload', @configuration_group_id, 21, NOW(), NULL, NULL),
(NULL, 'Output Directory', 'GOOGLE_FROOGLE_DIRECTORY', 'feed/google/', 'Set the name of your froogle output directory', @configuration_group_id, 20, NOW(), NULL, NULL),

(NULL, 'Use cPath in url', 'GOOGLE_FROOGLE_USE_CPATH', 'false', 'Use cPath in product info url', @configuration_group_id, 20, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),

(NULL, 'Tax Region', 'GOOGLE_FROOGLE_TAX_REGION', 'Tax applicable regions', 'Enter the tax region', @configuration_group_id, 22, NOW(), NULL, NULL),
(NULL, 'Display Tax', 'GOOGLE_FROOGLE_TAX_DISPLAY', 'false', 'Display tax per product?', @configuration_group_id, 23, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),

(NULL, 'Max products', 'GOOGLE_FROOGLE_MAX_PRODUCTS', '0', 'Default = 0 for infinite # of products', @configuration_group_id, 24, NOW(), NULL, NULL),
(NULL, 'Starting Point', 'GOOGLE_BASE_START_PRODUCTS', '0', 'Start at which entry (not product_id)?<br />Default=0', @configuration_group_id, 24, NOW(), NULL, NULL),

(NULL, 'Pickup', 'GOOGLE_FROOGLE_PICKUP', 'do not display', 'Local pickup available?', @configuration_group_id, 25, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\', \'do not display\'),'),

(NULL, 'Included Categories', 'GOOGLE_BASE_POS_CATEGORIES', '', 'Enter category names separated by commas <br>(i.e. 1,2,3)<br>Leave blank to allow all categories', @configuration_group_id, 30, NOW(), NULL, NULL),
(NULL, 'Excluded Categories', 'GOOGLE_BASE_NEG_CATEGORIES', '', 'Enter category names separated by commas <br>(i.e. 1,2,3)<br>Leave blank to deactivate', @configuration_group_id, 30, NOW(), NULL, NULL),

(NULL, 'Show Weight', 'GOOGLE_BASE_WEIGHT', 'false', 'Include products weight?', @configuration_group_id, 33, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'Weight Units', 'GOOGLE_BASE_UNITS', 'pounds', 'What unit of weight measure?<br />pounds OR kilograms', @configuration_group_id, 33, NOW(), NULL, 'zen_cfg_select_option(array(\'pounds\', \'kilograms\'),'),

(NULL, 'Product Type', 'GOOGLE_BASE_PRODUCT_TYPE', 'top', 'Use top-level, bottom-level, or full-path as product_type?', @configuration_group_id, 34, NOW(), NULL, 'zen_cfg_select_option(array(\'top\', \'bottom\', \'full\'),'),

(NULL, 'Payments Accepted', 'GOOGLE_BASE_PAYMENT_METHODS', 'Cash,Check,Visa,MasterCard,AmericanExpress,Discover,WireTransfer', 'What payment methods do you accept?', @configuration_group_id, 35, NOW(), NULL, NULL),
(NULL, 'Payment Notes', 'GOOGLE_BASE_PAYMENT_NOTES', 'GoogleCheckout', 'Add payment notes (use this for showing you accept Google Checkout)', @configuration_group_id, 35, NOW(), NULL, NULL),

(NULL, 'Alternate Image URL', 'GOOGLE_BASE_ALTERNATE_IMAGE_URL', '', 'Add an alternate URL if your images are hosted offsite (i.e. http://www.domain.com/images/).  Your defined image will be appended to the end of this URL.', @configuration_group_id, 36, NOW(), NULL, NULL),
(NULL, 'Image Handler', 'GOOGLE_FROOGLE_IMAGE_HANDLER', 'false', 'Resize images using image handler (separate module required)?', @configuration_group_id, 36, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),

(NULL, 'Use Meta Title', 'GOOGLE_BASE_META_TITLE', 'false', 'Use meta title as the title if it exists (for products only)?', @configuration_group_id, 40, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),

(NULL, 'Select Shipping Method', 'GOOGLE_BASE_SHIPPING_METHOD', 'none', 'Select a shipping method from the drop-down list that is used in your store, or leave as none', @configuration_group_id, 50, NOW(), NULL, 'zen_cfg_select_option(array(\'zones table rate\', \'flat rate\', \'per item\', \'per weight unit\', \'table rate\', \'zones\', \'percategory\', \'free shipping\', \'free rules shipping\', \'none\'),'),
(NULL, 'Table Zone ID', 'GOOGLE_BASE_RATE_ZONE', '', 'Enter the table rate ID if using a shipping method that uses table rates:', @configuration_group_id, 51, NOW(), NULL, NULL),  
(NULL, 'Shipping Country', 'GOOGLE_BASE_SHIPPING_COUNTRY', '', 'Select the destination country for the shipping rates:', @configuration_group_id, 52, NOW(), NULL, 'zen_cfg_pull_down_country_list('),
(NULL, 'Shipping Region', 'GOOGLE_BASE_SHIPPING_REGION', '', 'Enter the destination region within the selected country (state code, or zip with wildcard *):', @configuration_group_id, 53, NOW(), NULL, NULL),
(NULL, 'Shipping Service', 'GOOGLE_BASE_SHIPPING_SERVICE', '', 'Enter the shipping service type (i.e. Ground):', @configuration_group_id, 54, NOW(), NULL, NULL),

(NULL, 'Debug', 'GOOGLE_BASE_DEBUG', 'false', 'Turn on simple debug?', @configuration_group_id, 0, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),');