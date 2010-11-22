#GOOGLE BASE FEEDER
#
#REMOVE #'s BELOW TO INSTALL UPC/ISBN SUPPORT:
#ALTER TABLE products ADD products_upc varchar(32) NULL default NULL after products_model; 
#ALTER TABLE products ADD products_isbn varchar(32) NULL default NULL after products_upc;

SET @configuration_group_id=0;
SELECT @configuration_group_id:=configuration_group_id
FROM configuration_group
WHERE configuration_group_title= 'Google Base Feeder Configuration'
LIMIT 1;
DELETE FROM configuration WHERE configuration_group_id = @configuration_group_id;
DELETE FROM configuration_group WHERE configuration_group_id = @configuration_group_id;

INSERT INTO configuration_group (configuration_group_id, configuration_group_title, configuration_group_description, sort_order, visible) VALUES (NULL, 'Google Base Feeder Configuration', 'Set Google Base Options', '1', '1');
SET @configuration_group_id=last_insert_id();
UPDATE configuration_group SET sort_order = @configuration_group_id WHERE configuration_group_id = @configuration_group_id;

INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES 
(NULL, 'Google Base FTP Username', 'GOOGLE_FROOGLE_USERNAME', 'ftp_username', 'Enter your Google Base FTP username', @configuration_group_id, 0, NOW(), NULL, NULL),
(NULL, 'Google Base FTP Password', 'GOOGLE_FROOGLE_PASSWORD', 'ftp_password', 'Enter your Google Base FTP password', @configuration_group_id, 0, NOW(), NULL, NULL),
(NULL, 'Google Base Server', 'GOOGLE_FROOGLE_SERVER', 'uploads.google.com', 'Enter froogle server<br />default: hedwig.google.com', @configuration_group_id, 0, NOW(), NULL, NULL),
(NULL, 'Store Address', 'GOOGLE_BASE_ADDRESS', 'http://www.domain.com', 'Enter your website address', @configuration_group_id, 1, NOW(), NULL, NULL),
(NULL, 'Store Description', 'GOOGLE_BASE_DESCRIPTION', '', 'Enter a short description of your store', @configuration_group_id, 1, NOW(), NULL, NULL),
(NULL, 'Auction Site Attributes', 'GOOGLE_BASE_ASA', 'false', 'Activate Auction Site Attributes?', @configuration_group_id, 2, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'Show Default Currency', 'GOOGLE_FROOGLE_CURRENCY_DISPLAY', 'true', 'Display Currency', @configuration_group_id, 4, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'Default Currency', 'GOOGLE_FROOGLE_CURRENCY', 'USD', 'Select currency', @configuration_group_id, 5, NOW(), NULL, 'zen_cfg_pull_down_currencies('),
(NULL, 'Show Offer ID', 'GOOGLE_FROOGLE_OFFER_ID', 'id', 'A unique alphanumeric identifier for the item - products_id code. ', @configuration_group_id, 6, NOW(), NULL, 'zen_cfg_select_option(array(\'id\', \'model\', \'UPC\', \'ISBN\', \'false\'),'),
(NULL, 'Show Quantity', 'GOOGLE_FROOGLE_IN_STOCK', 'false', 'Display products quantity?', @configuration_group_id, 7, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'Shipping Options', 'GOOGLE_FROOGLE_SHIPPING', '', 'The shipping options available for an item', @configuration_group_id, 8, NOW(), NULL, NULL),
(NULL, 'Condition', 'GOOGLE_FROOGLE_CONDITION', 'new', 'Choose your default condition', @configuration_group_id, 12, NOW(), NULL, 'zen_cfg_select_option(array(\'new\', \'used\', \'refurbished\'),'),
(NULL, 'Show Feed Lanugage', 'GOOGLE_FROOGLE_LANGUAGE_DISPLAY', 'false', 'Display Feed Language', @configuration_group_id, 13, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'Feed Language', 'GOOGLE_FROOGLE_LANGUAGE', 'English', 'If Show Feed Language is True, what is your feed language?<br />default = en', @configuration_group_id, 14, NOW(), NULL, 'zen_cfg_pull_down_languages_list('),
(NULL, 'Output File Name', 'GOOGLE_FROOGLE_OUTPUT_FILENAME', 'google_base.xml', 'Set the name of your froogle output file', @configuration_group_id, 19, NOW(), NULL, NULL),
(NULL, 'Compress Feed File', 'GOOGLE_FROOGLE_COMPRESS', 'false', 'Compress Google froogle file', @configuration_group_id, 20, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'Uploaded date', 'GOOGLE_FROOGLE_UPLOADED_DATE', '', 'Date and time of the last upload', @configuration_group_id, 21, NOW(), NULL, NULL),
(NULL, 'Output Directory', 'GOOGLE_FROOGLE_DIRECTORY', 'feed/', 'Set the name of your froogle output directory', @configuration_group_id, 20, NOW(), NULL, NULL),
(NULL, 'Expiration Date Base', 'GOOGLE_FROOGLE_EXPIRATION_BASE', 'now', 'Expiration Date Base:<ul><li>now - add Adjust to current date;</li><li>product - add Adjust to product date (max(date_added, last_modified, date_available))</li></ul>', @configuration_group_id, 2, NOW(), NULL, 'zen_cfg_select_option(array(\'now\', \'product\'),'),
(NULL, 'Expiration Date Adjust', 'GOOGLE_FROOGLE_EXPIRATION_DAYS', '365', 'Expiration Date Adjust in Days', @configuration_group_id, 2, NOW(), NULL, NULL),
(NULL, 'Use cPath in url', 'GOOGLE_FROOGLE_USE_CPATH', 'false', 'Use cPath in product info url', @configuration_group_id, 20, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'Tax Region', 'GOOGLE_FROOGLE_TAX_REGION', 'Tax applicable regions', 'Enter the tax region', @configuration_group_id, 22, NOW(), NULL, NULL),
(NULL, 'Show Tax Region', 'GOOGLE_FROOGLE_TAX_DISPLAY', 'false', 'Display Tax Region?', @configuration_group_id, 23, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'Max products', 'GOOGLE_FROOGLE_MAX_PRODUCTS', '0', 'Default = 0 for infinite # of products', @configuration_group_id, 24, NOW(), NULL, NULL),
(NULL, 'Pickup', 'GOOGLE_FROOGLE_PICKUP', 'do not display', 'Local pickup available?', @configuration_group_id, 25, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\', \'do not display\'),'),
(NULL, 'Included Categories', 'GOOGLE_BASE_POS_CATEGORIES', '', 'Enter category names separated by commas <br>(i.e. computers,software,televisions)<br>Leave blank to allow all categories', @configuration_group_id, 30, NOW(), NULL, NULL),
(NULL, 'Excluded Categories', 'GOOGLE_BASE_NEG_CATEGORIES', '', 'Enter category names separated by commas <br>(i.e. computers,software,televisions)<br>Leave blank to deactivate', @configuration_group_id, 30, NOW(), NULL, NULL),
(NULL, 'Show Weight', 'GOOGLE_BASE_WEIGHT', 'false', 'Include products weight?', @configuration_group_id, 33, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'Weight Units', 'GOOGLE_BASE_UNITS', 'pounds', 'What unit of weight measure?<br />pounds OR kilograms', @configuration_group_id, 33, NOW(), NULL, 'zen_cfg_select_option(array(\'pounds\', \'kilograms\'),');