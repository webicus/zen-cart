Name
====
Google Base Feeder

Version Date
==============
v 1.5.3 28.08.2007 10:25

Author
======
numinix webmaster@numinix.com http://www.numinix.com/index.php?main_page=page&id=42&chapter=0

Description
===========
This Script generates a Google Base data feed as described here:
http://www.google.com/base/products.html

Support Thread
==============
http://www.zen-cart.com/forum/showthread.php?t=67850

Affected files
==============
admin/includes/languages/english/product.php
admin/includes/modules/product/collect_info.php
admin/includes/modules/update_product.php


Affects DB
==========
Yes (creates new records into configuration_group, configuration tables and products)

DISCLAIMER
==========
Installation of this contribution is done at your own risk.
Backup your ZenCart database and any and all applicable files before proceeding.

Features:
=========
- supports Search-Engine Safe URLs
- could be accessed by http or command line
- auto-upload to Google Base Merchant's FTP
- full zen-cart compatibility

Install:
========
1. Unzip and upload all files to your store directory (except .sql files);
2. Chmod feed directory to 777
3. Go to Admin->Tools->Install SQL Patches and install googlefroogle.sql by copying and pasting (do not upload);
4. Go to http://base.google.com and create/sign to your account.  Follow the link on the right hand side of their website to create your FTP account.
5. Go to Admin->Configuration->Google Froogle Configuration and setup all parameters;
6. Register your bulk upload .xml file using the same name in your Google Froogle Configuration at base.google.com
7. Go to Admin->Tools>Google Froogle Feeder and follow instructions to create, view, and upload feed file.

Update:
=======
1. Upload/Overwrite all files;
2. Install current version update_#_#_#.sql by copying and pasting (do not upload);
3. If no update.sql file available, then no SQL updates required;

Tips
====
To have this update and automatically upload to Google Base, you
will need to set up a Cron job via your host's control panel.

To run it as a cron job (at 5:0am like you wanted), put something in your crontab like the 
following:


0 5 * * * GET http://your_http_catalog/googlefroogle.php?feed=yes&upload=yes
or
0 5 * * * wget http://your_http_catalog/googlefroogle.php?feed=yes&upload=yes
or
0 5 * * * curl -s http://your_http_catalog/googlefroogle.php?feed=yes&upload=yes

If your host has disabled cron jobs, try webcron services such as http://www.webcron.org/

Troubleshooting:
================

1. If your script is timing out, in the root of your server (not store root), create/open a file called php.ini and add the following lines:
	max_execution_time = 300
	max_input_time = 90
	memory_limit = 32M 
	
2. If you are having issues with the FTP function, please contact your host to make sure all the required ports for PHP FTP functions are open.

3. If you are seeing constants in the ADMIN->CONFIGURATION or ADMIN->TOOLS (i.e. BOX_GOOGLEFROOGLE), then you are missing a definitions file.  
	Reupload the files by overwriting the old ones.

4. If the FTP function is timing out, as indicated by a timeout error, increase the timeout length in admin->googlefroogle.php near the beginning of the file.

5. For additional troubleshooting, please visit the support thread, read the entire thread for existing bugs or installation support before posting your questions.

History
=======
V.1.2.1 29th July 2006
1. Add Encodes an ISO-8859-1 string to UTF-8;
2. Use Google Base fields name.
V.1.2.2
1. Add check for Google Froogle settings.
2. Use ISO3 for countries.
V.1.2.3 11.08.2006 1:38
1. Add tax to price.
V.1.2.4 14.08.2006 20:15
1. Fixed priced_by_attribute.
2. Skip is_call and is_free products.
v 1.2.5 16.08.2006 19:42
1. Don't use redirect to setting up language and currency - direct use google froogle settings.
v 1.2.6 19.08.2006 7:30
1. Remove all old unsupported Google Base Attributes (http://base.google.com/base/attributes.html). Apply update_1_2_6.sql patch for db updates.
v 1.2.8 05.10.2006 23:27
1. Change attribute "category" to attribute "label".
v 1.2.9 09.10.2006 11:26
1. Convert all applicable characters to HTML entities.
v 1.2.10 13.10.2006 0:18
1. Add expiration_date attribute.
v 1.2.11 23.11.2006 15:00
1. Add check support ftp-functions
v 1.2.12 25.11.2006 15:26
1. Add @set_time_limit(0);
v 1.2.13 25.11.2006 17:51
1. Code optimization;
v 1.3.0 28.11.2006 2:23
1. More code optimization;
2. Output buffering (see GOOGLE_FROOGLE_OUTPUT_BUFFER_MAXSIZE constant);
3. Using products_price_sorter field instead of function zen_get_products_actual_price;
4. Using selected type_handler field instead of function zen_get_info_page;
v 1.3.1 13.12.2006 5:54
1. Traditional Code optimization :-); 
2. Add configuration parameter GOOGLE_FROOGLE_USE_CPATH. If it set to 'true' - 'cPath' parameter add to product url.
v 1.3.2 30.01.2007 7:05
1. Fix bug with currency value;
2. Add IH support (Thanks to Dan);
3. Don't use FILENAME_MAIN_PRODUCT_IMAGE;
4. Fix bug with price = 0;
v 1.3.3 07.02.2007 20:45
1. Code clean;
2. Add "condition" attribute (Thanks to j0ney3);
================= This is my last version - I stopped to developing and support this module ==========================
================= The following updates made by Jeff Lew on behalf of Numinix Technology =============================
v 1.3.4 07.06.2007 20:45
1. Removed label and fixed product_type;
2. Added mpn output;
v 1.3.5 08.06.2007 12:50
1. Added specials pricing;
v 1.3.6 13/06/2007
1. Updated the Google Base logo;
2. Added a counter to help combat timeouts;
3. Added tax region;
4. Removed product_type from the configuration;
5. Code optimization;
v 1.3.7 19/06/2007
1. Removed shipping attribute;
2. Added instock attribute based on quantity attribute;
3. Code optimization for counter;
v 1.3.8 04/07/2007
1. Code optimization;
2. Rewrote readme.txt file;
v 1.4.0b 04/07/2007
1. Added Google Base Adwords functionality;
2. Only enabled adwords for selected categories;
v 1.4.1 09/07/2007
1. Removed Google Base Adwords;
2. Allow category selection;
3. Module now known as Google Base Feeder;
v 1.4.2 11/07/2007
1. Removed instock output;
2. Updated readme file instructions;
v 1.4.3 12/07/2007
1. Added UPC and ISBN;
v 1.4.4 13/07/2007
1. Fixed bugs and typos;
v 1.4.5 15/07/2007
1. Added excluded categories;
2. Esthetic updates;
v 1.4.6 16/07/2007
1. Fixed folder heirarchy;
2. Added missing ";" in SQL;
v 1.4.7 17/07/2007
1. Bug fix;
v 1.4.8 17/07/2007
1. Bug fix;
v 1.4.9b 01/08/2007
1. Code optimization;
2. Updated configuration_group_title;
v 1.4.10 02/08/2007
1. Fixed duplicate entries from linked products;
2. Code optimization;
v 1.5.0 05/08/2007
1. Rewritten to support RSS 2.0 format;
v 1.5.1 06/08/2007
1. Bug fixes;
v 1.5.2 10/08/2007
1. Code optimization;
2. Added g:weight attribute;
3. Added troubleshooting to the readme.txt;
4. Automatically creates the .xml file if it does not already exist;
5. Removed UPC/ISBN mod as required (must now install separately);
v 1.5.3 28/08/2007
1. Added support for Auction Site Attributes (required if using UPC);
2. Outputs items in descending order for RSS readers;