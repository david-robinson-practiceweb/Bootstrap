<?php


/**
 * bootstrap_preprocess_search_theme_form().
 */
function bootstrap_preprocess_search_theme_form(&$vars, $hook) {
  // Amend the label of the search form (setting it blank removes it)
  $vars['form']['search_theme_form']['#title'] = t('');
 
  // Add a custom class and placeholder to the search box
  $vars['form']['search_theme_form']['#attributes'] = array('class' => 'form-control','placeholder' => 'Search');
 
  // Uncomment the line below to change the text on the submit button, changing Go as required.
  //$vars['form']['submit']['#value'] = t('Go');

  // Rebuild the rendered version (search form only, rest remains unchanged)
  unset($vars['form']['search_theme_form']['#printed']);
  $vars['search']['search_theme_form'] = drupal_render($vars['form']['search_theme_form']);

  // uncomment the two lines below to change the button to an image, custom the path and name of the image as required.
  //$vars['form']['submit']['#type'] = 'image_button';
  //$vars['form']['submit']['#attributes'] = array('alt' => t('Search'));
  //$vars['form']['submit']['#src'] = path_to_theme() . '/images/search.jpg';

  // Rebuild the rendered version (submit button, rest remains unchanged)
  unset($vars['form']['submit']['#printed']);
  $vars['search']['submit'] = drupal_render($vars['form']['submit']);

  // Collect all form elements to make it easier to print the whole form.
  $vars['search_form'] = implode($vars['search']);
}


/**
 * Override form_element().
 */
function bootstrap_form_element($element, $value) {
  // This is also used in the installer, pre-database setup.
  $t = get_t();
  
  if ($element['#type'] == 'checkbox') {
    $output = '<div class="checkbox">';
  } elseif ($element['#type'] == 'radio') {
    $output = '<div class="radio">';
  } else {
    $output = '<div class="form-group">';
  };
  $required = !empty($element['#required']) ? '<span class="form-required" title="' . $t('This field is required.') . '">*</span>' : '';

  if (!empty($element['#title'])) {
    $title = $element['#title'];
    if (!empty($element['#id'])) {
      $output .= ' <label for="' . $element['#id'] . '">' . $t('!title: !required', array('!title' => filter_xss_admin($title), '!required' => $required)) . "</label>\n";
    }
    else {
      $output .= ' <label>' . $t('!title: !required', array('!title' => filter_xss_admin($title), '!required' => $required)) . "</label>\n";
    }
  }

  $output .= " $value\n";

  if (!empty($element['#description'])) {
    $output .= ' <div class="description">' . $element['#description'] . "</div>\n";
  }

  $output .= "</div>\n";
  return $output;
  
}






/**
 * Override theme_textfield().
 */
function bootstrap_textfield($element) {
  
  if (array_key_exists('class', $element['#attributes'])) {
    $element['#attributes']['class'] .= ' form-control';
  } else {
    $element['#attributes']['class'] = 'form-control';
  }
  
  // create variable using textfield value
  $placeholder = check_plain($element['#value']);  
  
  $output = '<input type="text"' . $maxlength . ' name="' . $element['#name'] . '" id="' . $element['#id'] . '"' . $size . ' value="' . $placeholder . '"' . drupal_attributes($element['#attributes']) . ' />';
  
  return theme('form_element', $element, $output);
}



/**
 * Override theme_textfield().
 */
function bootstrap_webform_email($element) {
  
  if (array_key_exists('class', $element['#attributes'])) {
    $element['#attributes']['class'] .= ' form-control';
  } else {
    $element['#attributes']['class'] = 'form-control';
  }
  
  // create variable using textfield value
  $placeholder = check_plain($element['#value']);  
  
  $output = '<input type="text"' . $maxlength . ' name="' . $element['#name'] . '" id="' . $element['#id'] . '"' . $size . ' value="' . $placeholder . '"' . drupal_attributes($element['#attributes']) . ' />';
  
  return theme('form_element', $element, $output);
}





/**
 * Override theme_textarea().
 */
function bootstrap_textarea($element) {
  
  $class = array('form-control');

  // Add teaser behavior (must come before resizable)
  if (!empty($element['#teaser'])) {
    drupal_add_js('misc/teaser.js');
    // Note: arrays are merged in drupal_get_js().
    drupal_add_js(array('teaserCheckbox' => array($element['#id'] => $element['#teaser_checkbox'])), 'setting');
    drupal_add_js(array('teaser' => array($element['#id'] => $element['#teaser'])), 'setting');
    $class[] = 'teaser';
  }

  _form_set_class($element, $class);
  return theme('form_element', $element, '<textarea cols="' . $element['#cols'] . '" rows="' . $element['#rows'] . '" name="' . $element['#name'] . '" id="' . $element['#id'] . '" ' . drupal_attributes($element['#attributes']) . '>' . check_plain($element['#value']) . '</textarea>');
}


/**
 * Override theme_password().
 */
function bootstrap_password($element) {

  if (array_key_exists('class', $element['#attributes'])) {
    $element['#attributes']['class'] .= ' form-control';
  } else {
    $element['#attributes']['class'] = 'form-control';
  }
  
  $size = $element['#size'] ? ' size="' . $element['#size'] . '" ' : '';
  $maxlength = $element['#maxlength'] ? ' maxlength="' . $element['#maxlength'] . '" ' : '';

  _form_set_class($element, array('form-text'));
  $output = '<input type="password" name="' . $element['#name'] . '" id="' . $element['#id'] . '" ' . $maxlength . $size . drupal_attributes($element['#attributes']) . ' />';
  return theme('form_element', $element, $output);
}


/**
 * Override theme_select().
 */
function bootstrap_select($element) {

  if (array_key_exists('class', $element['#attributes'])) {
    $element['#attributes']['class'] .= ' form-control';
  } else {
    $element['#attributes']['class'] = 'form-control';
  }
  $select = '';
  $size = $element['#size'] ? ' size="' . $element['#size'] . '"' : '';
  _form_set_class($element, array('form-select'));
  $multiple = $element['#multiple'];
  return theme('form_element', $element, '<select name="' . $element['#name'] . '' . ($multiple ? '[]' : '') . '"' . ($multiple ? ' multiple="multiple" ' : '') . drupal_attributes($element['#attributes']) . ' id="' . $element['#id'] . '" ' . $size . '>' . form_select_options($element) . '</select>');
}


/**
 * Override theme_checkbox().
 */
function bootstrap_checkbox($element) {
  _form_set_class($element, array('form-checkbox'));
  $checkbox = '<input ';
  $checkbox .= 'type="checkbox" ';
  $checkbox .= 'name="' . $element['#name'] . '" ';
  $checkbox .= 'id="' . $element['#id'] . '" ';
  $checkbox .= 'value="' . $element['#return_value'] . '" ';
  $checkbox .= $element['#value'] ? ' checked="checked" ' : ' ';
  $checkbox .= drupal_attributes($element['#attributes']) . ' />';

  if (!is_null($element['#title'])) {
    $checkbox = '<label class="option" for="' . $element['#id'] . '">' . $checkbox . ' ' . $element['#title'] . '</label>';
  }

  unset($element['#title']);
  return theme('form_element', $element, $checkbox);
}



/**
 * Override theme_button().
 */
function bootstrap_button($element) {
  if (check_plain($element['#value']) == 'Delete' || check_plain($element['#value']) == 'Reset') {
    // Not sure how else to target specific buttons
    $bootstrap_btn_class = 'btn-danger';
  }
  else {
    $bootstrap_btn_class = 'btn-primary';
  }
  // Make sure not to overwrite classes.
  if (isset($element['#attributes']['class'])) {
    $element['#attributes']['class'] = 'btn ' . $bootstrap_btn_class . ' form-' . $element['#button_type'] . ' ' . $element['#attributes']['class'];
  }
  else {
    $element['#attributes']['class'] = 'btn ' . $bootstrap_btn_class . ' form-' . $element['#button_type'];
  }

  return '<input type="submit" ' . (empty($element['#name']) ? '' : 'name="' . $element['#name'] . '" ') . 'id="' . $element['#id'] . '" value="' . check_plain($element['#value']) . '" ' . drupal_attributes($element['#attributes']) . " />\n";
}



