<?php
//
// +----------------------------------------------------------------------+
// |zen-cart Open Source E-commerce                                       |
// +----------------------------------------------------------------------+
// | Copyright (c) 2003 The zen-cart developers                           |
// |                                                                      |
// | http://www.zen-cart.com/index.php                                    |
// |                                                                      |
// | Portions Copyright (c) 2003 osCommerce                               |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the GPL license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.zen-cart.com/license/2_0.txt.                             |
// | If you did not receive a copy of the zen-cart license and are unable |
// | to obtain it through the world-wide-web, please send a note to       |
// | license@zen-cart.com so we can mail you a copy immediately.          |
// +----------------------------------------------------------------------+
// $Id: categories_css.php 2004/06/23 00:00:00 DrByteZen Exp $
//

  // load the UL-generator class and produce the menu list dynamically from there
  require_once (DIR_WS_CLASSES . 'categories_ul_generator.php');
  $zen_CategoriesUL = new zen_categories_ul_generator;
  $menulist = $zen_CategoriesUL->buildTree(true);



  require($template->get_template_dir('tpl_categories_css.php',DIR_WS_TEMPLATE, $current_page_base,'sideboxes'). '/tpl_categories_css.php');

  $title = BOX_HEADING_CATEGORIES;
  $left_corner = false;
  $right_corner = false;
  $right_arrow = false;
  $title_link = false;

    require($template->get_template_dir($column_box_default, DIR_WS_TEMPLATE, $current_page_base,'common') . '/' . $column_box_default);

?>