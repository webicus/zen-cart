#####################################
#                                   #
#     CSS Flyout Category Menu      #
#                                   #
#       by DrByte 2004-07-11        #
#                                   #
#     for Zen Cart v1.2.x/1.3.x     #
#                                   #
# donations to: paypal@zen-cart.com #
#                                   #
#####################################

Now you can have a CSS-based flyout menu for your Zen Cart categories sidebox!

To see what it looks like, view the enclosed screenshot.jpg

It's currently configured to go up to 5 subcategories deep, but can be set to 
however deep you desire it to go.


############################
Installation
----------------------------
1. Unzip the package, retaining the folder structure.

2. Read the following notes before proceeding to copy all the files to your site, 
   Before copying, take care to rename the "YOURTEMPLATE" folders to match your 
   custom template's foldername.

3. You need to enable the categories_css sidebox.
   Go to Admin->Tools->Layout Boxes Controller and enable the new "categories_css" sidebox.

4. Of course, you don't need to upload this readme.txt or the screenshot.jpg ... :)

############################
About this contribution
----------------------------
This contribution was compiled as a combination of resources including:
- site map contribution by NetworkDad, but modified to produce the proper UL structure for this menu
- Eric Meyer's CSS Flyout Menu work
- Zen Cart's built-in sidebox manipulation features and other overrides functions

#############################
Compatibility
-----------------------------
This contribution should work fine in any Zen Cart v1.2.x or v1.3.0 installation
Version 1.1.x installations will require a few changes to the modules/sideboxes/.../categories_css.php file


#############################
Version History
-----------------------------
2004-07-11 First Release
2005-04-17 Adapted slightly to conform a little better with version 1.2.4.x
2005-04-19 Updated to properly exclude disabled categories from the menu. Props to Merlin for spotting this.
2005-04-21 Updated to fix a missing </a> tag in the menu bar, causing a small 
           pink strip down right side of menu. Props to mamont for pointing this out!
2006-03-06 Updated the csshover.htc script to newer version with bugfixes from their website.
2006-04-09 Updated for compatibility with Zen Cart v1.3.0
2007-03-31 Updated to include background images with rollover by Robert Holt (Get Em Fast).    