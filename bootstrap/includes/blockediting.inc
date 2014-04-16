<?php
// $Id: template.block-editing.inc,v 1.1.2.2 2009/02/13 07:34:31 johnalbin Exp $

/**
 * @file
 * Contains functions only needed if the user has block editing permissions.
 */

/**
 * Add block editing variables into the block templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("block" in this case.)
 */
function bootstrap_preprocess_block_editing(&$vars, $hook) {
  $block = $vars['block'];
  
  // Display 'edit block' for custom blocks.
  if ($block->module == 'block') {
    $edit_links[] = l('<span>' . t('edit block') . '</span>', 'admin/build/block/configure/' . $block->module . '/' . $block->delta,
      array(
        'attributes' => array(
          'title' => t('edit the content of this block'),
          'class' => 'btn btn-default',
        ),
        'query' => drupal_get_destination(),
        'html' => TRUE,
      )
    );
  }
  // Display 'configure' for other blocks.
  else {
    $edit_links[] = l('<span>' . t('configure') . '</span>', 'admin/build/block/configure/' . $block->module . '/' . $block->delta,
      array(
        'attributes' => array(
          'title' => t('configure this block'),
          'class' => 'btn btn-default',
        ),
        'query' => drupal_get_destination(),
        'html' => TRUE,
      )
    );
  }

  // Display 'edit view' for Views blocks.
  if ($block->module == 'views' && user_access('administer views')) {
    list($view_name, $view_block) = explode('-block', $block->delta);
    $edit_links[] = l('<span>' . t('edit view') . '</span>', 'admin/build/views/edit/' . $view_name,
      array(
        'attributes' => array(
          'title' => t('edit the view that defines this block'),
          'class' => 'btn btn-default',
        ),
        'query' => drupal_get_destination(),
        'fragment' => 'views-tab-block' . $view_block,
        'html' => TRUE,
      )
    );
  }
  // Display 'edit menu' for Menu blocks.
  elseif (($block->module == 'menu' || ($block->module == 'user' && $block->delta == 1)) && user_access('administer menu')) {
    $menu_name = ($block->module == 'user') ? 'navigation' : $block->delta;
    $edit_links[] = l('<span>' . t('edit menu') . '</span>', 'admin/build/menu-customize/' . $menu_name,
      array(
        'attributes' => array(
          'title' => t('edit the menu that defines this block'),
          'class' => 'btn btn-default',
        ),
        'query' => drupal_get_destination(),
        'html' => TRUE,
      )
    );
  }
  // Display 'edit menu' for Menu block blocks.
  elseif ($block->module == 'menu_block' && user_access('administer menu')) {
    list($menu_name, ) = split(':', variable_get("menu_block_{$block->delta}_parent", 'navigation:0'));
    $edit_links[] = l('<span>' . t('edit menu') . '</span>', 'admin/build/menu-customize/' . $menu_name,
      array(
        'attributes' => array(
          'title' => t('edit the menu that defines this block'),
          'class' => 'btn btn-default',
        ),
        'query' => drupal_get_destination(),
        'html' => TRUE,
      )
    );
  }
  
  $vars['edit_links_array'] = $edit_links;
  $vars['edit_links'] = '<div class="btn-group btn-group-xs admin-links">' . implode(' ', $edit_links) . '</div>';
}
