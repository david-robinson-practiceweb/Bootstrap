<?php

function bootstrap_settings($saved_settings) {
	$defaults = array(
		'layout_front_display_page_title' => 1,
    'layout_front_display_page_content' => 1,
    'layout_front_offcanvas_sidebar' => 1,
    'mobile_contact_btns_icons' => 1,
    'layout_staff_directory_layout' => 'grid',
    'mobile_breadcrumb_btn_text' => 'Where am I?',
    'mobile_offcanvas_btn_text' => 'What else is in this section?',
  );
	$settings = array_merge($defaults, $saved_settings);

  // Staff directory layout
	$staff_directory_layout = array(
		'rows' => t('rows'),
		'grid' => t('grid'),
	);
  
  // Form processing
	if ($file = file_save_upload('bootstrap_touch_icon_default_upload',
	array('file_validate_is_image' => array()))) {
		$parts = pathinfo($file->filename);
		$filename = $file->filename;
		if (file_copy($file, $filename, FILE_EXISTS_REPLACE)) {
			$settings['bootstrap_touch_icon_default_path'] = $file->filepath;
		}else{
			dsm('Failed to upload the default touch icon.');
		}
	}
  
  if ($file = file_save_upload('bootstrap_touch_icon_ipad_upload',
	array('file_validate_is_image' => array()))) {
		$parts = pathinfo($file->filename);
		$filename = $file->filename;
		if (file_copy($file, $filename, FILE_EXISTS_REPLACE)) {
			$settings['bootstrap_touch_icon_ipad_path'] = $file->filepath;
		}else{
			dsm('Failed to upload the iPad touch icon.');
		}
	}
  
  if ($file = file_save_upload('bootstrap_touch_icon_iphone_r_upload',
	array('file_validate_is_image' => array()))) {
		$parts = pathinfo($file->filename);
		$filename = $file->filename;
		if (file_copy($file, $filename, FILE_EXISTS_REPLACE)) {
			$settings['bootstrap_touch_icon_iphone_r_path'] = $file->filepath;
		}else{
			dsm('Failed to upload the iPhone Retina touch icon.');
		}
	}
  
  if ($file = file_save_upload('bootstrap_touch_icon_ipad_r_upload',
	array('file_validate_is_image' => array()))) {
		$parts = pathinfo($file->filename);
		$filename = $file->filename;
		if (file_copy($file, $filename, FILE_EXISTS_REPLACE)) {
			$settings['bootstrap_touch_icon_ipad_r_path'] = $file->filepath;
		}else{
			dsm('Failed to upload the iPad Retina touch icon.');
		}
	}
  
  if ($file = file_save_upload('bootstrap_touch_icon_windows_metro_upload',
	array('file_validate_is_image' => array()))) {
		$parts = pathinfo($file->filename);
		$filename = $file->filename;
		if (file_copy($file, $filename, FILE_EXISTS_REPLACE)) {
			$settings['bootstrap_touch_icon_windows_metro_path'] = $file->filepath;
		}else{
			dsm('Failed to upload the iPad Retina touch icon.');
		}
	}
  
  if ($file = file_save_upload('mobile_logo_upload',
	array('file_validate_is_image' => array()))) {
		$parts = pathinfo($file->filename);
		$filename = $file->filename;
		if (file_copy($file, $filename, FILE_EXISTS_REPLACE)) {
			$settings['mobile_logo_path'] = $file->filepath;
		}else{
			dsm('Failed to upload the mobile logo.');
		}
	}
    
  // Mobile
	$form['mobile'] = array(
		'#type' => 'fieldset', 
		'#title' => t('Mobile'),
		'#collapsible' => TRUE,
		'#collapsed' => TRUE,
  );
  
  // Mobile header
  $form['mobile']['mobile_nav_bg'] = array(
		'#type' => 'textfield',
		'#title' => t('Override header background color'),
    '#size' => 6, 
    '#description' => t('The 6 digit hexidecimal color value. Do not include hash (#).'), 
		'#default_value' => $settings['mobile_nav_bg'],
  );
  
  
  // Mobile breadcrumb button text
  $form['mobile']['mobile_breadcrumb_btn_text'] = array(
		'#type' => 'textfield',
		'#title' => t('Breadcumb toggle text'),
    '#description' => t('Change the text on the breadcrumb toggle button.'), 
		'#default_value' => $settings['mobile_breadcrumb_btn_text'],
  );
  
  // Mobile offcanvas toggle button text
  $form['mobile']['mobile_offcanvas_btn_text'] = array(
		'#type' => 'textfield',
		'#title' => t('Sidebar toggle text'),
    '#description' => t('Change the text on the sidebar toggle button.'), 
		'#default_value' => $settings['mobile_offcanvas_btn_text'],
  );
  
  
  // Custom contact action buttons
	$form['mobile']['mobile_contact_btns'] = array(
		'#type' => 'fieldset', 
		'#title' => t('Mobile contact buttons'),
		'#collapsible' => TRUE,
		'#collapsed' => TRUE,
  );
  
  $form['mobile']['mobile_contact_btns']['mobile_contact_btns_icons'] = array(
		'#type' => 'checkbox',
		'#title' => t('Display icons?'),
		'#default_value' => $settings['mobile_contact_btns_icons'],
  );  
  

  // Custom mobile logo
	$form['mobile']['mobile_logo'] = array(
		'#type' => 'fieldset', 
		'#title' => t('Mobile logo'),
		'#collapsible' => TRUE,
		'#collapsed' => TRUE,
  );
    
  $form['mobile']['mobile_logo']['mobile_logo_markup'] = array(
    '#type' => 'markup',
		'#value' => t('<p>Optional. Upload a logo to replace the default on small devices.</p>'),
  );
	 
	$form['mobile']['mobile_logo']['mobile_logo_use'] = array(
		'#type' => 'checkbox',
		'#title' => t('Replace logo with mobile logo on small devices?'),
		'#default_value' => $settings['mobile_logo_use'],
  );
	
	$form['mobile']['mobile_logo']['mobile_logo_path'] = array(
		'#type' => 'textfield',
		'#title' => t('Path to logo'),
		'#value' => $settings['mobile_logo_path'],
		'#default_value' => '',
	);
	
	$form['mobile']['mobile_logo']['mobile_logo_upload'] = array(
		'#type' => 'file',
		'#title' => t('Upload image'),
	);
		
	// If file exists then show image
	if (!empty($settings['mobile_logo_path'])) {
		$form['mobile']['mobile_logo']['mobile_logo_preview'] = array(
			'#type' => 'markup',
			'#value' => !empty($settings['mobile_logo_path']) ? 
				theme('image', $settings['mobile_logo_path']) : '',
		);
	};
  
	
	/*
	 * Form components
	 */
  // Touch icons fieldset
	$form['touch_icons'] = array(
		'#type' => 'fieldset', 
		'#title' => t('Touch icons'),
		'#collapsible' => TRUE,
		'#collapsed' => TRUE,
    );

    
  // Default touch icon fieldset
	$form['touch_icons']['default_touch_icon'] = array(
		'#type' => 'fieldset', 
		'#title' => t('Default icon'),
		'#collapsible' => FALSE,
		'#collapsed' => FALSE,
  );
    
  $form['touch_icons']['default_touch_icon']['bootstrap_touch_icon_default_description'] = array(
    '#type' => 'markup',
		'#value' => t('The default touch icon used for older Apple and Android devices.<br />Size: 57x57px.'),
  );
	 
	 // Use  apple touch icon
	$form['touch_icons']['default_touch_icon']['bootstrap_touch_icon_default_use'] = array(
		'#type' => 'checkbox',
		'#title' => t('Use default  touch icon?'),
		'#default_value' => $settings['bootstrap_touch_icon_default_use'],
  );
	
	// Default  touch icon path
	$form['touch_icons']['default_touch_icon']['bootstrap_touch_icon_default_path'] = array(
		'#type' => 'textfield',
		'#title' => t('Path to touch icon'),
		'#value' => $settings['bootstrap_touch_icon_default_path'],
		'#default_value' => '',
	);
	
	// Default touch icon file upload
	$form['touch_icons']['default_touch_icon']['bootstrap_touch_icon_default_upload'] = array(
		'#type' => 'file',
		'#title' => t('Upload image'),
	);
	
	// Default  touch icon preview
	
	// If file exists then show image
	if (!empty($settings['bootstrap_touch_icon_default_path'])) {
		$form['touch_icons']['default_touch_icon']['bootstrap_touch_icon_default_preview'] = array(
			'#type' => 'markup',
			'#value' => !empty($settings['bootstrap_touch_icon_default_path']) ? 
				theme('image', $settings['bootstrap_touch_icon_default_path']) : '',
		);
	};
  
  
  // ipad touch icon fieldset
	$form['touch_icons']['ipad_touch_icon'] = array(
		'#type' => 'fieldset', 
		'#title' => t('iPad icon'),
		'#collapsible' => FALSE,
		'#collapsed' => FALSE,
    );
	 
	 // Use ipad touch icon
	$form['touch_icons']['ipad_touch_icon']['bootstrap_touch_icon_ipad_use'] = array(
		'#type' => 'checkbox',
		'#title' => t('Use iPad touch icon?'),
		'#default_value' => $settings['bootstrap_touch_icon_ipad_use'],
    );
	
	// ipad icon path
	$form['touch_icons']['ipad_touch_icon']['bootstrap_touch_icon_ipad_path'] = array(
		'#type' => 'textfield',
		'#title' => t('Path to iPad touch icon'),
		'#value' => $settings['bootstrap_touch_icon_ipad_path'],
		'#default_value' => '',
	);
	
	// ipad icon file upload
	$form['touch_icons']['ipad_touch_icon']['bootstrap_touch_icon_ipad_upload'] = array(
		'#type' => 'file',
		'#title' => t('Upload image'),
	);
	
	// ipad icon preview
	
	// If file exists then show image
	if (!empty($settings['bootstrap_touch_icon_ipad_path'])) {
		$form['touch_icons']['ipad_touch_icon']['bootstrap_apple_touch_icon_ipad_preview'] = array(
			'#type' => 'markup',
			'#value' => !empty($settings['bootstrap_touch_icon_ipad_path']) ? 
				theme('image', $settings['bootstrap_touch_icon_ipad_path']) : '',
		);
	};
  
  
  // iPhone Retina touch icon fieldset
	$form['touch_icons']['iphone_r_touch_icon'] = array(
		'#type' => 'fieldset', 
		'#title' => t('iPhone Retina icon'),
		'#collapsible' => FALSE,
		'#collapsed' => FALSE,
    );
	 
	 // Use iPhone Retina touch icon
	$form['touch_icons']['iphone_r_touch_icon']['bootstrap_touch_icon_iphone_r_use'] = array(
		'#type' => 'checkbox',
		'#title' => t('Use iPhone retina touch icon?'),
		'#default_value' => $settings['bootstrap_touch_icon_iphone_r_use'],
    );
	
	// iPhone Retina icon path
	$form['touch_icons']['iphone_r_touch_icon']['bootstrap_touch_icon_iphone_r_path'] = array(
		'#type' => 'textfield',
		'#title' => t('Path to iPhone Retina touch icon'),
		'#value' => $settings['bootstrap_touch_icon_iphone_r_path'],
		'#default_value' => '',
	);
	
	// iPhone Retina icon file upload
	$form['touch_icons']['iphone_r_touch_icon']['bootstrap_touch_icon_iphone_r_upload'] = array(
		'#type' => 'file',
		'#title' => t('Upload image'),
	);
	
	// iPhone Retina icon preview
	
	// If file exists then show image
	if (!empty($settings['bootstrap_touch_icon_iphone_r_path'])) {
		$form['touch_icons']['iphone_r_touch_icon']['bootstrap_apple_touch_icon_iphone_r_preview'] = array(
			'#type' => 'markup',
			'#value' => !empty($settings['bootstrap_touch_icon_iphone_r_path']) ? 
				theme('image', $settings['bootstrap_touch_icon_iphone_r_path']) : '',
		);
	};
  
  
  // iPad Retina touch icon fieldset
	$form['touch_icons']['ipad_r_touch_icon'] = array(
		'#type' => 'fieldset', 
		'#title' => t('iPad Retina icon'),
		'#collapsible' => FALSE,
		'#collapsed' => FALSE,
    );
	 
	 // Use iPad Retina touch icon
	$form['touch_icons']['ipad_r_touch_icon']['bootstrap_touch_icon_ipad_r_use'] = array(
		'#type' => 'checkbox',
		'#title' => t('Use iPad retina touch icon?'),
		'#default_value' => $settings['bootstrap_touch_icon_ipad_r_use'],
    );
	
	// iPad Retina icon path
	$form['touch_icons']['ipad_r_touch_icon']['bootstrap_touch_icon_ipad_r_path'] = array(
		'#type' => 'textfield',
		'#title' => t('Path to iPad Retina touch icon'),
		'#value' => $settings['bootstrap_touch_icon_ipad_r_path'],
		'#default_value' => '',
	);
	
	// iPad Retina icon file upload
	$form['touch_icons']['ipad_r_touch_icon']['bootstrap_touch_icon_ipad_r_upload'] = array(
		'#type' => 'file',
		'#title' => t('Upload image'),
	);
	
	// iPad Retina icon preview
	
	// If file exists then show image
	if (!empty($settings['bootstrap_touch_icon_ipad_r_path'])) {
		$form['touch_icons']['ipad_r_touch_icon']['bootstrap_apple_touch_icon_ipad_r_preview'] = array(
			'#type' => 'markup',
			'#value' => !empty($settings['bootstrap_touch_icon_ipad_r_path']) ? 
				theme('image', $settings['bootstrap_touch_icon_ipad_r_path']) : '',
		);
	};
  

  // Windows metro touch icon fieldset
	$form['touch_icons']['windows_metro_touch_icon'] = array(
		'#type' => 'fieldset', 
		'#title' => t('Windows metro tile'),
		'#collapsible' => FALSE,
		'#collapsed' => FALSE,
    );
	 
	 // Use Windows metro touch icon
	$form['touch_icons']['windows_metro_touch_icon']['bootstrap_touch_icon_windows_metro_use'] = array(
		'#type' => 'checkbox',
		'#title' => t('Use Windows metro touch icon?'),
		'#default_value' => $settings['bootstrap_touch_icon_windows_metro_use'],
    );
    
   // Use Windows metro touch color
	$form['touch_icons']['windows_metro_touch_icon']['bootstrap_touch_icon_windows_metro_color'] = array(
		'#type' => 'textfield',
		'#title' => t('Hex color value'),
    '#size' => 6, 
    '#description' => t('The 6 digit hexidecimal color value. Do not include hash (#).'), 
		'#default_value' => $settings['bootstrap_touch_icon_windows_metro_color'],
    );
	
	// Windows metro icon path
	$form['touch_icons']['windows_metro_touch_icon']['bootstrap_touch_icon_windows_metro_path'] = array(
		'#type' => 'textfield',
		'#title' => t('Path to Windows metro icon'),
		'#value' => $settings['bootstrap_touch_icon_windows_metro_path'],
		'#default_value' => '',
	);
	
	// Windows metro icon file upload
	$form['touch_icons']['windows_metro_touch_icon']['bootstrap_touch_icon_windows_metro_upload'] = array(
		'#type' => 'file',
		'#title' => t('Upload image'),
	);
	
	// Windows metro icon preview
	
	// If file exists then show image
  $windows_metro_icon = '<div style="width:144px; height: 144px; padding: 20px 20px; background-color: #' . $settings['bootstrap_touch_icon_windows_metro_color'] . ';"><img src="/' . $settings['bootstrap_touch_icon_windows_metro_path'] . '"></div>';
	if (!empty($settings['bootstrap_touch_icon_windows_metro_path'])) {
		$form['touch_icons']['windows_metro_touch_icon']['bootstrap_apple_touch_icon_windows_metro_preview'] = array(
			'#type' => 'markup',
			'#value' => $windows_metro_icon,
		);
	};

  // Layout
  $form['layout'] = array(
    '#type' => 'fieldset', 
		'#title' => t('Layout'),
		'#collapsible' => TRUE,
		'#collapsed' => TRUE,
  );
  
  // Layout - Front
	$form['layout']['layout_front'] = array(
    '#type' => 'fieldset', 
		'#title' => t('Front'),
		'#collapsible' => TRUE,
		'#collapsed' => TRUE,
  );
  
  $form['layout']['layout_front']['layout_front_display_page_title'] = array(
		'#type' => 'checkbox',
		'#title' => t('Show page title?'),
    '#description' => t('Some designs dont require the page title to be visible. Uncheck if its not needed.'),
		'#default_value' => $settings['layout_front_display_page_title'],
  );
  $form['layout']['layout_front']['layout_front_display_page_content'] = array(
		'#type' => 'checkbox',
		'#title' => t('Show page content?'),
    '#description' => t('Some designs dont require the page content. Uncheck if its not needed.'),
		'#default_value' => $settings['layout_front_display_page_content'],
  );
  $form['layout']['layout_front']['layout_front_offcanvas_sidebar'] = array(
		'#type' => 'checkbox',
		'#title' => t('Stop off canvas sidebar?'),
    '#description' => t('By default, the left sidebar will hide off the screen on small devices. Check box to stop this on the front page.'),
		'#default_value' => $settings['layout_front_offcanvas_sidebar'],
  );
  

  // Layout - Staff directory
	$form['layout']['layout_staff_directory'] = array(
    '#type' => 'fieldset', 
		'#title' => t('Staff directory'),
		'#collapsible' => TRUE,
		'#collapsed' => TRUE,
  );
  
  $form['layout']['layout_staff_directory']['layout_staff_directory_layout'] = array(
		'#type' => 'select',
		'#title' => t('Layout'),
		'#default_value' => $settings['layout_staff_directory_layout'],
		 '#options'       => $staff_directory_layout,
	);
  
	return $form;

}
