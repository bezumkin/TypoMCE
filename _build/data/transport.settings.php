<?php

$settings = array();

$tmp = array(
	'base_url' => array(
		'xtype' => 'textfield'
		,'value' => true
		,'area' => 'general'
	)
	,'custom_plugins' => array(
		'xtype' => 'textfield'
		,'value' => 'style,advimage,advlink,modxlink,searchreplace,print,contextmenu,paste,fullscreen,noneditable,nonbreaking,xhtmlxtras,visualchars,media'
		,'area' => 'general'
	)
	,'editor_theme' => array(
		'xtype' => 'textfield'
		,'value' => 'advanced'
		,'area' => 'general'
	)
	,'path_options' => array(
		'xtype' => 'textfield'
		,'value' => ''
		,'area' => 'general'
	)
	,'skin' => array(
		'xtype' => 'textfield'
		,'value' => 'cirkuit'
		,'area' => 'general'
	)
	,'skin_variant' => array(
		'xtype' => 'textfield'
		,'value' => ''
		,'area' => 'general'
	)
	,'table_inline_editing' => array(
		'xtype' => 'combo-boolean'
		,'value' => false
		,'area' => 'general'
	)
	,'template_list' => array(
		'xtype' => 'textarea'
		,'value' => ''
		,'area' => 'general'
	)
	,'template_list_snippet' => array(
		'xtype' => 'textarea'
		,'value' => ''
		,'area' => 'general'
	)
	,'template_selected_content_classes' => array(
		'xtype' => 'textarea'
		,'value' => ''
		,'area' => 'general'
	)
	,'use_uncompressed_library' => array(
		'xtype' => 'combo-boolean'
		,'value' => false
		,'area' => 'general'
	)


	,'convert_fonts_to_spans' => array(
		'xtype' => 'combo-boolean'
		,'value' => true
		,'area' => 'cleanup-output'
	)
	,'convert_newlines_to_brs' => array(
		'xtype' => 'combo-boolean'
		,'value' => false
		,'area' => 'cleanup-output'
	)
	,'element_format' => array(
		'xtype' => 'textfield'
		,'value' => 'xhtml'
		,'area' => 'cleanup-output'
	)
	,'entity_encoding' => array(
		'xtype' => 'textfield'
		,'value' => 'named'
		,'area' => 'cleanup-output'
	)
	,'fix_nesting' => array(
		'xtype' => 'combo-boolean'
		,'value' => false
		,'area' => 'cleanup-output'
	)
	,'fix_table_elements' => array(
		'xtype' => 'combo-boolean'
		,'value' => false
		,'area' => 'cleanup-output'
	)
	,'font_size_classes' => array(
		'xtype' => 'textfield'
		,'value' => ''
		,'area' => 'cleanup-output'
	)
	,'font_size_style_values' => array(
		'xtype' => 'textfield'
		,'value' => 'xx-small,x-small,small,medium,large,x-large,xx-large'
		,'area' => 'cleanup-output'
	)
	,'forced_root_block' => array(
		'xtype' => 'textfield'
		,'value' => 'p'
		,'area' => 'cleanup-output'
	)
	,'indentation' => array(
		'xtype' => 'textfield'
		,'value' => '30px'
		,'area' => 'cleanup-output'
	)
	,'invalid_elements' => array(
		'xtype' => 'textfield'
		,'value' => ''
		,'area' => 'cleanup-output'
	)
	,'nowrap' => array(
		'xtype' => 'combo-boolean'
		,'value' => false
		,'area' => 'cleanup-output'
	)
	,'object_resizing' => array(
		'xtype' => 'combo-boolean'
		,'value' => true
		,'area' => 'cleanup-output'
	)
	,'remove_linebreaks' => array(
		'xtype' => 'combo-boolean'
		,'value' => false
		,'area' => 'cleanup-output'
	)
	,'remove_redundant_brs' => array(
		'xtype' => 'combo-boolean'
		,'value' => true
		,'area' => 'cleanup-output'
	)
	,'removeformat_selector' => array(
		'xtype' => 'textfield'
		,'value' => 'b,strong,em,i,span,ins'
		,'area' => 'cleanup-output'
	)


	,'css_selectors' => array(
		'xtype' => 'textfield'
		,'value' => ''
		,'area' => 'advanced-theme'
	)
	,'theme_advanced_blockformats' => array(
		'xtype' => 'textarea'
		,'value' => 'p,h1,h2,h3,h4,h5,h6,div,blockquote,code,pre,address'
		,'area' => 'advanced-theme'
	)
	,'theme_advanced_font_sizes' => array(
		'xtype' => 'textarea'
		,'value' => '80%,90%,100%,120%,140%,160%,180%,220%,260%,320%,400%,500%,700%'
		,'area' => 'advanced-theme'
	)


	,'custom_buttons1' => array(
		'xtype' => 'textfield'
		,'value' => 'undo,redo,selectall,separator,pastetext,pasteword,separator,search,replace,separator,nonbreaking,hr,charmap,separator,image,modxlink,unlink,anchor,media,separator,cleanup,removeformat,separator,fullscreen,print,code,help'
		,'area' => 'custom-buttons'
	)
	,'custom_buttons2' => array(
		'xtype' => 'textfield'
		,'value' => 'bold,italic,underline,strikethrough,sub,sup,separator,bullist,numlist,outdent,indent,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,styleselect,formatselect,separator,styleprops'
		,'area' => 'custom-buttons'
	)
	,'custom_buttons3' => array(
		'xtype' => 'textfield'
		,'value' => ''
		,'area' => 'custom-buttons'
	)
	,'custom_buttons4' => array(
		'xtype' => 'textfield'
		,'value' => ''
		,'area' => 'custom-buttons'
	)
	,'custom_buttons5' => array(
		'xtype' => 'textfield'
		,'value' => ''
		,'area' => 'custom-buttons'
	)
);

foreach ($tmp as $k => $v) {
	/* @var modSystemSetting $setting */
	$setting = $modx->newObject('modSystemSetting');
	$setting->fromArray(array_merge(
		array(
			'key' => 'typo.'.$k
			,'namespace' => 'typomce'
		), $v
	),'',true,true);

	$settings[] = $setting;
}

unset($tmp);
return $settings;
