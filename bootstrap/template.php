<?
// $Id$

/**
 * @file
 * Contains theme override functions and preprocess functions for the theme.
 */
include_once(drupal_get_path('theme', 'bootstrap') . '/includes/form.inc');
include_once(drupal_get_path('theme', 'bootstrap') . '/includes/pager.inc');
include_once(drupal_get_path('theme', 'bootstrap') . '/includes/theme.inc');


// Save default theme settings variables
if (is_null(theme_get_setting('bootstrap'))) {
  global $theme_key;

  /*
   * The default values for the theme variables. Make sure $defaults exactly
   * matches the $defaults in the theme-settings.php file.
   */
  $defaults = array(
    'layout_front_display_page_title' => 1,
    'layout_front_display_page_content' => 1,
    'layout_front_offcanvas_sidebar' => 1,
    'mobile_contact_btns_icons' => 1,
    'layout_staff_directory_layout' => 'grid',
    'mobile_breadcrumb_btn_text' => 'Where am I?',
    'mobile_offcanvas_btn_text' => 'What else is in this section?',
  );

  // Get default theme settings.
  $settings = theme_get_settings($theme_key);
  // Don't save the toggle_node_info_ variables.
  if (module_exists('node')) {
    foreach (node_get_types() as $type => $name) {    
      unset($settings['toggle_node_info_' . $type]);
    }
  }
  
  // Save default theme settings.
  variable_set(
    str_replace('/', '_', 'theme_'. $theme_key .'_settings'),
    array_merge($defaults, $settings)
  );
  // Force refresh of Drupal internals.
  theme_get_setting('', TRUE);
}


/**
 * Implementation of preprocess_page().
 */
function bootstrap_preprocess_page(&$vars, $hook) {

  if (!module_exists('conditional_styles')) {
    $vars['styles'] .= $vars['conditional_styles'] = variable_get('conditional_styles_' . $GLOBALS['theme'], '');
  }

  // Update jQuery
  if (arg(0) != 'admin') {
    $scripts = drupal_add_js();
   
    // Use Google for jQuery
    $vars['head'] .= '<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>';
   
    unset($scripts['core']['misc/jquery.js']);
    $vars['scripts'] = drupal_get_js('header', $scripts);
  }

  // Unset  css
  $css = drupal_add_css();
  unset($css['all']['module']['modules/node/node.css']);
  //unset($css['all']['module']['modules/system/system.css']);
  unset($css['all']['module']['modules/system/defaults.css']);
  unset($css['all']['module']['modules/system/system-menus.css']);
  unset($css['all']['module']['modules/user/user.css']);
  $vars['styles'] = drupal_get_css($css);
  
  // Classes for body element. Allows advanced theming based on context
  // (home page, node of certain type, etc.)
  $classes = split(' ', $vars['body_classes']);
  // Remove the mostly useless page-ARG0 class.
  if ($index = array_search(preg_replace('![^abcdefghijklmnopqrstuvwxyz0-9-_]+!s', '', 'page-'. drupal_strtolower(arg(0))), $classes)) {
    unset($classes[$index]);
  }
  if (!$vars['is_front']) {
    // Add unique class for each page.
    $path = drupal_get_path_alias($_GET['q']);
    $classes[] = bootstrap_id_safe('page-' . $path);
    
    if (arg(0) == 'node') {
      if (arg(1) == 'add') {
        $section = 'node-add';
        $classes[] = bootstrap_id_safe('section-' . $section);
      }
      elseif (is_numeric(arg(1)) && (arg(2) == 'edit' || arg(2) == 'delete')) {
        $section = 'node-' . arg(2);
        $classes[] = bootstrap_id_safe('section-' . $section);
      }
      
    }

    // Add unique class for each website section.
    $sections = explode('/', $path);
    for ($i = 0; $i < count($sections); $i++) {
      $count = 0;
      $class = 'section-';
      for ($count; $count <= $i; $count++) {
        $class .= $sections[$count];
        if ($count < $i) {
          $class .= '-';
        }
      }

      $classes[] = bootstrap_id_safe($class); 
    }
  }
  $vars['body_classes_array'] = $classes;
  $vars['body_classes'] = implode(' ', $classes); // Concatenate with spaces.
  
  
  
  // Theme form variables
  $vars['mobile_breadcrumb_btn_text'] = theme_get_setting('mobile_breadcrumb_btn_text');
  $vars['mobile_offcanvas_btn_text'] = theme_get_setting('mobile_offcanvas_btn_text');
  
  
  // Theme form variables
  $layout_front_offcanvas_sidebar = theme_get_setting('layout_front_offcanvas_sidebar');
  
  $vars['layout_front_display_page_title'] = theme_get_setting('layout_front_display_page_title');
  $vars['layout_front_display_page_content'] = theme_get_setting('layout_front_display_page_content');
  $vars['layout_front_offcanvas_sidebar'] = $layout_front_offcanvas_sidebar;
  
  // Create column classes  
  if ($vars['left'] && $vars['right'] && $vars['is_front'] && $layout_front_offcanvas_sidebar == TRUE) {
    $vars['css_main'] = ' class="row"';
    $vars['css_content'] = ' class="col-xs-12 col-sm-8 col-md-6 col-lg-6 col-md-push-3 col-lg-push-3"';
    $vars['css_right'] = ' class="col-xs-12 col-sm-4 col-md-3 col-lg-3 col-md-push-3 col-lg-push-3"';
    $vars['css_left'] = ' class="col-xs-6 col-sm-12 col-md-3 col-lg-3 col-md-pull-9 col-lg-pull-9"';
  } elseif ($vars['left'] && $vars['is_front'] && $layout_front_offcanvas_sidebar == TRUE) {
    $vars['css_main'] = ' class="row"';
    $vars['css_content'] = ' class="col-xs-12 col-sm-9 col-md-9 col-lg-9 col-sm-push-3 col-md-push-3 col-lg-push-3"';
    $vars['css_right'] = '';
    $vars['css_left'] = ' class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-sm-pull-9 col-md-pull-9 col-lg-pull-9"';
  }
  
  elseif ($vars['left'] && $vars['right']) {
    $vars['css_main'] = ' class="row row-offcanvas row-offcanvas-left"';
    $vars['css_content'] = ' class="col-xs-12 col-sm-8 col-md-6 col-lg-6 col-md-push-3 col-lg-push-3"';
    $vars['css_right'] = ' class="col-xs-12 col-sm-4 col-md-3 col-lg-3 col-md-push-3 col-lg-push-3"';
    $vars['css_left'] = ' class="col-xs-6 col-sm-12 col-md-3 col-lg-3 col-md-pull-9 col-lg-pull-9 sidebar-offcanvas"';
  } elseif ($vars['left']) {
    $vars['css_main'] = ' class="row row-offcanvas row-offcanvas-left"';
    $vars['css_content'] = ' class="col-xs-12 col-sm-9 col-md-9 col-lg-9 col-sm-push-3 col-md-push-3 col-lg-push-3"';
    $vars['css_right'] = '';
    $vars['css_left'] = ' class="col-xs-6 col-sm-3 col-md-3 col-lg-3 col-sm-pull-9 col-md-pull-9 col-lg-pull-9 sidebar-offcanvas"';
  } elseif ($vars['right']) {
    $vars['css_main'] = ' class="row"';
    $vars['css_content'] = ' class="col-xs-12 col-sm-9 col-md-9 col-lg-9"';
    $vars['css_right'] = ' class="col-xs-12 col-sm-3 col-md-3 col-lg-3"';
    $vars['css_left'] = '';
  };
  
  
  // Theme form variables
  $mobile_nav_bg = theme_get_setting('mobile_nav_bg');
  $mobile_logo_use = theme_get_setting('mobile_logo_use');
	$mobile_logo_path = theme_get_setting('mobile_logo_path');
  
  // Mobile nav bg color override
  if ($mobile_nav_bg != '') {
    $vars['mobile_nav_bg'] = ' style="background-color:' . $mobile_nav_bg . '"';
  }
  
  if ($mobile_logo_use == TRUE) {
    $vars['mobile_logo'] = TRUE;
    $vars['mobile_logo_path'] = $mobile_logo_path;
  }
  
  // Theme form variables
	$bootstrap_touch_icon_default_path = theme_get_setting('bootstrap_touch_icon_default_path');
	$bootstrap_touch_icon_default_use = theme_get_setting('bootstrap_touch_icon_default_use');
  
  $bootstrap_touch_icon_ipad_path = theme_get_setting('bootstrap_touch_icon_ipad_path');
	$bootstrap_touch_icon_ipad_use = theme_get_setting('bootstrap_touch_icon_ipad_use');
  
  $bootstrap_touch_icon_iphone_r_path = theme_get_setting('bootstrap_touch_icon_iphone_r_path');
	$bootstrap_touch_icon_iphone_r_use = theme_get_setting('bootstrap_touch_icon_iphone_r_use');
  
  $bootstrap_touch_icon_ipad_r_path = theme_get_setting('bootstrap_touch_icon_ipad_r_path');
	$bootstrap_touch_icon_ipad_r_use = theme_get_setting('bootstrap_touch_icon_ipad_r_use');
  
  $bootstrap_touch_icon_windows_metro_path = theme_get_setting('bootstrap_touch_icon_windows_metro_path');
	$bootstrap_touch_icon_windows_metro_use = theme_get_setting('bootstrap_touch_icon_windows_metro_use');
	$bootstrap_touch_icon_windows_metro_color = theme_get_setting('bootstrap_touch_icon_windows_metro_color');
  
  
  // Touch icons
  $bootstrap_touch_icons = '';
  
  if (!empty($bootstrap_touch_icon_default_path) && $bootstrap_touch_icon_default_use == true) {
    $bootstrap_touch_icons .= '<link rel="apple-touch-icon-precomposed" href="/' . $bootstrap_touch_icon_default_path . '" />';  
  }
  if (!empty($bootstrap_touch_icon_ipad_path) && $bootstrap_touch_icon_ipad_use == true) {
    $bootstrap_touch_icons .= '<link rel="apple-touch-icon-precomposed" sizes="72x72" href="/' . $bootstrap_touch_icon_ipad_path . '" />';  
  }
  if (!empty($bootstrap_touch_icon_iphone_r_path) && $bootstrap_touch_icon_iphone_r_use == true) {
    $bootstrap_touch_icons .= '<link rel="apple-touch-icon-precomposed" sizes="114x114" href="/' . $bootstrap_touch_icon_iphone_r_path . '" />';  
  }
  if (!empty($bootstrap_touch_icon_ipad_r_path) && $bootstrap_touch_icon_ipad_r_use == true) {
    $bootstrap_touch_icons .= '<link rel="apple-touch-icon-precomposed" sizes="144x144" href="/' . $bootstrap_touch_icon_ipad_r_path . '" />';  
  }
  if (!empty($bootstrap_touch_icon_windows_metro_path) && !empty($bootstrap_touch_icon_windows_metro_color) && $bootstrap_touch_icon_windows_metro_use == true) {
    $bootstrap_touch_icons .= '<meta name="msapplication-TileImage" content="' . $bootstrap_touch_icon_windows_metro_path . '" />';  
    $bootstrap_touch_icons .= '<meta name="msapplication-TileColor" content="#' . $bootstrap_touch_icon_windows_metro_color . '" />';  
  }
  
  $vars['bootstrap_touch_icons'] = $bootstrap_touch_icons;
  
  $active_trail = menu_get_active_trail();
  end($active_trail);
  $parent = prev($active_trail);
  if($parent['title'] != 'Home'){
    $vars['section_title'] = $parent['link_title'];
  } else {
    $vars['section_title'] = $vars['title'];
  }
  
  bootstrap_contact_buttons($vars);
}


/*
 * Creates HTML for mobile contact buttons
 */
function bootstrap_contact_buttons(&$vars) {
  // Theme form variables
  
  $company_phone = variable_get('company_phone', '');
  $site_mail = variable_get('site_mail', '');
  
  $vars['company_phone'] = $company_phone;
  $vars['site_mail'] = $site_mail;
  
  $vars['mobile_contact_btns_icons'] = theme_get_setting('mobile_contact_btns_icons');  
  
  if (!empty($company_phone) || !empty($site_mail)) {
    $mobile_contact_utilities = '<div class="container contact-details visible-xs">';
    
    if (!empty($company_phone)) {
      $mobile_contact_utilities .= '<a href="tel:' . $company_phone . '" class="btn btn-primary btn-xs">' . $company_phone;
      if ($vars['mobile_contact_btns_icons'] == TRUE) {
        $mobile_contact_utilities .= ' <span class="glyphicon glyphicon-phone"></span>';
      };
      $mobile_contact_utilities .= '</a>';
    };
    
    if (!empty($site_mail)) {
      $mobile_contact_utilities .= '<a href="mailto:' . $site_mail . '" class="btn btn-primary btn-xs">' . $site_mail;
      if ($vars['mobile_contact_btns_icons'] == TRUE) {
        $mobile_contact_utilities .= ' <span class="glyphicon glyphicon-comment"></span>';
      };
      $mobile_contact_utilities .= '</a>';
    };
    
    $mobile_contact_utilities .= '</div>';
    
    
    $vars['mobile_contact_utilities'] = $mobile_contact_utilities;
    return $vars;
  }
}


/**
 * Implementation of preprocess_node().
 */
function bootstrap_preprocess_node(&$vars, $hook) {
  if (($vars['type'] == 'shared_news') || ($vars['type'] == 'pw_news') || ($vars['type'] == 'pw_blog_post')) {
    $vars['template_files'][] = 'node-news';
  }
  $vars['layout_staff_directory_layout'] = theme_get_setting('layout_staff_directory_layout');
}


/**
 * Override theme_links().
 */
function bootstrap_links($links, $attributes, $config) {
  
  global $language;
  $output = '';
 
  if (($config == 'primary-links' || $config == 'secondary-links') && count($links) > 0) {
    $output = '<ul class="nav navbar-nav">';

    $num_links = count($links);
    $i = 1;

    foreach ($links as $key => $link) {
      $class = $key;

      // Add first, last and active classes to the list of links to help out themers.
      if ($i == 1) {
        $class .= ' first';
      }
      if ($i == $num_links) {
        $class .= ' last';
      }
      if (isset($link['href']) && ($link['href'] == $_GET['q'] || ($link['href'] == '<front>' && drupal_is_front_page())) && (empty($link['language']) || $link['language']->language == $language->language)) {
        $class .= ' active';
      }
      $output .= '<li' . drupal_attributes(array('class' => $class)) . '>';

      if (isset($link['href'])) {
        // Pass in $link as $options, they share the same keys.
        $output .= l($link['title'], $link['href'], $link);
      }
      else if (!empty($link['title'])) {
        // Some links are actually not links, but we wrap these in <span> for adding title and class attributes
        if (empty($link['html'])) {
          $link['title'] = check_plain($link['title']);
        }
        $span_attributes = '';
        if (isset($link['attributes'])) {
          $span_attributes = drupal_attributes($link['attributes']);
        }
        $output .= '<span' . $span_attributes . '>' . $link['title'] . '</span>';
      }

      $i++;
      $output .= "</li>\n";
    }

    $output .= '</ul>';
  }
  
  elseif ($config == 'button-group' && count($links) > 0) {
    $output = '<div class="btn-group">';
    
    $num_links = count($links);
    $i = 1;
    
    foreach ($links as $key => $link) {

      $class = $key;

      // Add first, last and active classes to the list of links to help out themers.
      if ($i == 1) {
        $class .= ' first';
      }
      if ($i == $num_links) {
        $class .= ' last';
      }
      if (isset($link['href']) && ($link['href'] == $_GET['q'] || ($link['href'] == '<front>' && drupal_is_front_page())) && (empty($link['language']) || $link['language']->language == $language->language)) {
        $class .= ' active';
      }
      $output .= '<li' . drupal_attributes(array('class' => $class)) . '>';

      if (isset($link['href'])) {
        // Pass in $link as $options, they share the same keys.
        $output .= l($link['title'], $link['href'], $link);
      }
      else if (!empty($link['title'])) {
        // Some links are actually not links, but we wrap these in <span> for adding title and class attributes
        if (empty($link['html'])) {
          $link['title'] = check_plain($link['title']);
        }
        $span_attributes = '';
        if (isset($link['attributes'])) {
          $span_attributes = drupal_attributes($link['attributes']);
        }
        $output .= '<span' . $span_attributes . '>' . $link['title'] . '</span>';
      }

      $i++;
      $output .= "</li>\n";
    }

    $output .= '</div>';
  }
  
  elseif ($config == 'comment-links' && count($links) > 0) {
    $output = '<div class="btn-group btn-group-xs pull-right">';
    
    $num_links = count($links);
    $i = 1;
    
    
    foreach ($links as $key => $link) {
    
    if ($link['title'] == 'delete') {
      $link['attributes']['class'] = 'btn btn-danger';
    }
    else {
      $link['attributes']['class'] = 'btn btn-default';
    }

      if (isset($link['href'])) {
        // Pass in $link as $options, they share the same keys.
        $output .= l($link['title'], $link['href'], $link);
      }

      $i++;
    }

    $output .= '</div>';
  }
  
  elseif ($attributes['class'] == 'links inline' && count($links) > 0) {
    $output = '<div class="btn-group btn-group-sm">';
    
    $num_links = count($links);
    $i = 1;
    
    foreach ($links as $key => $link) {
    
    if ($link['title'] == 'delete') {
      $link['attributes']['class'] = 'btn btn-danger';
    }
    else {
      $link['attributes']['class'] = 'btn btn-default';
    }

      if (isset($link['href'])) {
        // Pass in $link as $options, they share the same keys.
        $output .= l($link['title'], $link['href'], $link);
      }

      $i++;
    }

    $output .= '</div>';
  }
  
  elseif (count($links) > 0) {
    $output = '<ul' . drupal_attributes($attributes) . '>';

    $num_links = count($links);
    $i = 1;

    foreach ($links as $key => $link) {
      $class = $key;
      

      // Add first, last and active classes to the list of links to help out themers.
      if ($i == 1) {
        $class .= ' first';
      }
      if ($i == $num_links) {
        $class .= ' last';
      }
      if (isset($link['href']) && ($link['href'] == $_GET['q'] || ($link['href'] == '<front>' && drupal_is_front_page())) && (empty($link['language']) || $link['language']->language == $language->language)) {
        $class .= ' active';
      }
      $output .= '<li' . drupal_attributes(array('class' => $class)) . '>';

      if (isset($link['href'])) {
        // Pass in $link as $options, they share the same keys.
        $output .= l($link['title'], $link['href'], $link);
      }
      else if (!empty($link['title'])) {
        // Some links are actually not links, but we wrap these in <span> for adding title and class attributes
        if (empty($link['html'])) {
          $link['title'] = check_plain($link['title']);
        }
        $span_attributes = '';
        if (isset($link['attributes'])) {
          $span_attributes = drupal_attributes($link['attributes']);
        }
        $output .= '<span' . $span_attributes . '>' . $link['title'] . '</span>';
      }

      $i++;
      $output .= "</li>\n";
    }

    $output .= '</ul>';
  }

  return $output;
}


/**
 * Implementation of preprocess_comment().
 */
function bootstrap_preprocess_comment(&$vars) {

  // break down the string
  preg_match_all('|<li class="(.+?)(?: first)?(?: last)?"><a +href="(?:/(\w\w))?/(.*?)(?:\?(.+?))?(?:#(.+?))?"' .
               '(?: +(\w+)="(.*?)")?(?: +(\w+)="(.*?)")?(?: +(\w+)="(.*?)")? *>(.*?)(?:<span class=".*?">.*?</span>)?</a></li>|s',
  $vars['links'], $lines, PREG_SET_ORDER);

  $links = array();
  $languages = language_list();

  foreach($lines as $li) {
    $links[$li[1]] = Array('language' => $languages[$li[2]], 'href' => $li[3], 'query' => $li[4], 'fragment' => $li[5],
                         'attributes' => Array($li[6] => $li[7], $li[8] => $li[9], $li[10] => $li[11]),
                         'title' => $li[12]);

    unset($links[$li[1]]['attributes']['']);  // get ride of any nonexistent attribute
  }
  $vars['links'] = preg_replace('|amp;|', '', theme('links', $links, array('class' => 'links inline'), 'comment-links'));

  $comment = $vars['comment'];
  $node = $vars['node'];
  $vars['author'] = theme('username', $comment);
  $vars['content'] = $comment->comment;
  $vars['date'] = format_date($comment->timestamp , 'custom', 'jS M Y');
 
  $vars['new'] = $comment->new ? t('new') : '';
  $vars['picture'] = theme_get_setting('toggle_comment_user_picture') ? theme('user_picture', $comment) : '';
  $vars['signature'] = $comment->signature;
  $vars['submitted'] = theme('comment_submitted', $comment);
  $vars['title'] = l($comment->subject, $_GET['q'], array('fragment' => "comment-$comment->cid"));
  $vars['template_files'][] = 'comment-' . $node->type;
  // set status to a string representation of comment->status.
  if (isset($comment->preview)) {
    $vars['status'] = 'comment-preview';
  }
  else {
    $vars['status'] = ($comment->status == COMMENT_NOT_PUBLISHED) ? 'comment-unpublished' : 'comment-published';
  }
}

/**
 * Override theme_breadrumb().
 *
 * Print breadcrumbs as an ordered list.
 */
function bootstrap_breadcrumb($breadcrumb) {

  if (!empty($breadcrumb)) {
    $title = '<li class="active">' . drupal_get_title() . '</li>';
    $breadcrumbs = '<ol class="breadcrumb">';
    $count = count($breadcrumb) - 1;
    foreach ($breadcrumb as $key => $value) {
      if ($count != $key) {
        $breadcrumbs .= '<li>' . $value . '</li>';
      }
      else{
        $breadcrumbs .= '<li>' . $value . '</li>';
      }
    }
    $breadcrumbs .= $title;
    $breadcrumbs .= '</ol>';
    return $breadcrumbs;
  }
}


/**
 * Override theme_menu_local_tasks().
 *
 * Format tabs wrapper to match Bootstrap.
 */
function bootstrap_menu_local_tasks() {
  $output = '';

  if ($primary = menu_primary_local_tasks()) {
    $output .= '<ul class="nav nav-tabs primary">' . $primary . '</ul>';
  }
  if ($secondary = menu_secondary_local_tasks()) {
    $output .= '<ul class="nav nav-pills secondary">' . $secondary . '</ul>';
  }

  return $output;
}

/**
 * Override theme_menu_local_task().
 *
 * Format tab to match Bootstrap.
 */
function bootstrap_menu_local_task($link, $active = FALSE) {
  return '<li ' . ($active ? 'class="active" ' : '') . '>' . $link . "</li>\n";
}


/**
 * Adds the search form's submit button right after the input element.
 *
 * @ingroup themable
 */
function bootstrap_bootstrap_search_form_wrapper(&$variables) {
  $output = '<div class="input-append">';
  $output .= $variables['element']['#children'];
  $output .= '<button type="submit" class="btn">';
  $output .= '<i class="icon-search"></i>';
  $output .= '<span class="element-invisible">' . t('Search') . '</span>';
  $output .= '</button>';
  $output .= '</div>';
  return $output;
}
 
 
/**
 * Converts a string to a suitable html ID attribute.
 *
 * http://www.w3.org/TR/html4/struct/global.html#h-7.5.2 specifies what makes a
 * valid ID attribute in HTML. This function:
 *
 * - Ensure an ID starts with an alpha character by optionally adding an 'id'.
 * - Replaces any character except alphanumeric characters with dashes.
 * - Converts entire string to lowercase.
 *
 * @param $string
 *   The string
 * @return
 *   The converted string
 */
function bootstrap_id_safe($string) {
  // Replace with dashes anything that isn't A-Z, numbers, dashes, or underscores.
  $string = strtolower(preg_replace('/[^a-zA-Z0-9-]+/', '-', $string));
  // If the first character is not a-z, add 'id' in front.
  if (!ctype_lower($string{0})) { // Don't use ctype_alpha since its locale aware.
    $string = 'id' . $string;
  }
  return $string;
}


/**
 * Override or insert variables into the block templates.
 */
function bootstrap_preprocess_block(&$vars, $hook) {

  $block = $vars['block'];
  
  // Special classes for blocks.
  $classes = array('block');
  $classes[] = 'block-' . $block->module;
  $classes[] .= block_class($block);
  
  $vars['edit_links_array'] = array();
  
  $vars['edit_links'] = '';
  if (user_access('administer blocks')) {
    include_once drupal_get_path('theme', 'bootstrap') . '/includes/blockediting.inc';
    bootstrap_preprocess_block_editing($vars, $hook);
  }
  
  $vars['classes'] = implode(' ', $classes);
  
}
