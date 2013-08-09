<?php
/**
 * @package typomce
 */
class TypoMCE {
	public $config = array();
	public $properties = array();
	public $jsLoaded = false;

	/** @var modContext $context */
	public $context;


	/**
	 * The TypoMCE constructor.
	 *
	 * @param modX $modx A reference to the modX constructor.
	 * @param array $config An array of configuration properties
	 */
	function __construct(modX &$modx, array $config = array()) {
		$this->modx =& $modx;

		$assetsUrl = $this->modx->getOption('typo.assets_url', $config, $this->modx->getOption('assets_url', null, MODX_ASSETS_URL) . 'components/typomce/');
		$assetsPath = $this->modx->getOption('typo.assets_path', $config, $this->modx->getOption('assets_path', null, MODX_ASSETS_PATH) . 'components/typomce/');
		$corePath = $this->modx->getOption('typo.core_path', $config, $this->modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/typomce/');
		$baseUrl = $this->modx->getOption('typo.base_url', $config, '');
		if (empty($baseUrl)) {
			$baseUrl = $this->modx->getOption('assets_url', null, MODX_ASSETS_URL) . 'components/typomce/';
		}

		$this->config = array_merge(array(
			'assetsPath' => $assetsPath,
			'assetsUrl' => $assetsUrl,
			'path' => $assetsPath,
			'corePath' => $corePath,
			'baseUrl' => $baseUrl,
		), $config);
		$this->getEditingContext();
	}


	/**
	 * Set the properties for this instance
	 *
	 * @param array $properties
	 *
	 * @return void
	 */
	public function setProperties(array $properties = array()) {
		$browserAction = $this->_getBrowserAction();
		$this->properties = array_merge(array(
			'accessibility_warnings' => false,
			'browserUrl' => $browserAction ? $this->modx->getOption('manager_url', null, MODX_MANAGER_URL) . 'index.php?a=' . $browserAction : null,
			'cleanup' => true,
			'cleanup_on_startup' => false,
			'compressor' => '',
			'content_css' => $this->context->getOption('editor_css_path'),
			'element_list' => '',
			'entities' => '',
			'execcommand_callback' => 'Tiny.onExecCommand',
			'file_browser_callback' => 'Tiny.loadBrowser',
			'force_p_newlines' => true,
			'force_br_newlines' => false,
			'formats' => array(
				'alignleft' => array(
					'selector' => 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img',
					'classes' => 'justifyleft'
				),
				'alignright' => array(
					'selector' => 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img',
					'classes' => 'justifyright'
				),
				'alignfull' => array(
					'selector' => 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img',
					'classes' => 'justifyfull'
				),
			),
			'frontend' => false,
			'height' => '400px',
			'plugin_insertdate_dateFormat' => '%Y-%m-%d',
			'plugin_insertdate_timeFormat' => '%H:%M:%S',
			'preformatted' => false,
			'resizable' => true,
			'relative_urls' => true,
			'remove_script_host' => true,
			'resource_browser_path' => $this->modx->getOption('manager_url', null, MODX_MANAGER_URL) . 'controllers/browser/index.php?',
			'template_external_list_url' => $this->config['assetsUrl'] . 'template.list.php',
			'theme_advanced_disable' => '',
			'theme_advanced_resizing' => true,
			'theme_advanced_resize_horizontal' => true,
			'theme_advanced_statusbar_location' => 'bottom',
			'theme_advanced_toolbar_align' => 'left',
			'theme_advanced_toolbar_location' => 'top',
			'width' => '100%',
		), $properties);

		/* now do user/context/system setting overrides - these must override properties */
		$this->properties = array_merge($this->properties, array(
			'buttons1' => $this->context->getOption('typo.custom_buttons1', 'undo,redo,selectall,separator,pastetext,pasteword,separator,search,replace,separator,nonbreaking,hr,charmap,separator,image,modxlink,unlink,anchor,media,separator,cleanup,removeformat,separator,fullscreen,print,code,help', $this->properties),
			'buttons2' => $this->context->getOption('typo.custom_buttons2', 'bold,italic,underline,strikethrough,sub,sup,separator,bullist,numlist,outdent,indent,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,styleselect,formatselect,separator,styleprops', $this->properties),
			'buttons3' => $this->context->getOption('typo.custom_buttons3', '', $this->properties),
			'buttons4' => $this->context->getOption('typo.custom_buttons4', '', $this->properties),
			'buttons5' => $this->context->getOption('typo.custom_buttons5', '', $this->properties),
			'convert_fonts_to_spans' => $this->context->getOption('typo.convert_fonts_to_spans', true, $this->properties),
			'convert_newlines_to_brs' => $this->context->getOption('typo.convert_newlines_to_brs', false, $this->properties),
			'css_path' => $this->context->getOption('editor_css_path', '', $this->properties),
			'directionality' => $this->context->getOption('manager_direction', 'ltr', $this->properties),
			'element_format' => $this->context->getOption('typo.element_format', 'xhtml', $this->properties),
			'entity_encoding' => $this->context->getOption('typo.element_format', 'named', $this->properties),
			'fix_nesting' => $this->context->getOption('typo.fix_nesting', false, $this->properties),
			'fix_table_elements' => $this->context->getOption('typo.fix_table_elements', false, $this->properties),
			'font_size_classes' => $this->context->getOption('typo.font_size_classes', '', $this->properties),
			'font_size_style_values' => $this->context->getOption('typo.font_size_style_values', 'xx-small,x-small,small,medium,large,x-large,xx-large', $this->properties),
			'forced_root_block' => $this->context->getOption('typo.forced_root_block', 'p', $this->properties),
			'indentation' => $this->context->getOption('typo.indentation', '30px', $this->properties),
			'invalid_elements' => $this->context->getOption('typo.invalid_elements', '', $this->properties),
			'language' => $this->context->getOption('manager_language', $this->context->getOption('cultureKey', 'en', $this->properties), $this->properties),
			'nowrap' => $this->context->getOption('typo.nowrap', false, $this->properties),
			'object_resizing' => $this->context->getOption('typo.object_resizing', true, $this->properties),
			'path_options' => $this->context->getOption('typo.path_options', '', $this->properties),
			'plugins' => $this->context->getOption('typo.custom_plugins', 'style,advimage,advlink,modxlink,searchreplace,print,contextmenu,paste,fullscreen,noneditable,nonbreaking,xhtmlxtras,visualchars,media', $this->properties),
			'remove_linebreaks' => $this->context->getOption('typo.remove_linebreaks', false, $this->properties),
			'remove_redundant_brs' => $this->context->getOption('typo.remove_redundant_brs', true, $this->properties),
			'removeformat_selector' => $this->context->getOption('typo.removeformat_selector', 'b,strong,em,i,span,ins', $this->properties),
			'skin' => $this->context->getOption('typo.skin', 'cirkuit', $this->properties),
			'skin_variant' => $this->context->getOption('typo.skin_variant', '', $this->properties),
			'table_inline_editing' => $this->context->getOption('typo.table_inline_editing', false, $this->properties),
			'theme' => $this->context->getOption('typo.editor_theme', 'advanced', $this->properties),
			'theme_advanced_blockformats' => $this->context->getOption('typo.theme_advanced_blockformats', 'p,h1,h2,h3,h4,h5,h6,div,blockquote,code,pre,address', $this->properties),
			'theme_advanced_buttons1' => $this->context->getOption('typo.custom_buttons1', 'undo,redo,selectall,separator,pastetext,pasteword,separator,search,replace,separator,nonbreaking,hr,charmap,separator,image,modxlink,unlink,anchor,media,separator,cleanup,removeformat,separator,fullscreen,print,code,help', $this->properties),
			'theme_advanced_buttons2' => $this->context->getOption('typo.custom_buttons2', 'bold,italic,underline,strikethrough,sub,sup,separator,bullist,numlist,outdent,indent,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,styleselect,formatselect,separator,styleprops', $this->properties),
			'theme_advanced_buttons3' => $this->context->getOption('typo.custom_buttons3', '', $this->properties),
			'theme_advanced_buttons4' => $this->context->getOption('typo.custom_buttons4', '', $this->properties),
			'theme_advanced_buttons5' => $this->context->getOption('typo.custom_buttons5', '', $this->properties),
			'theme_advanced_font_sizes' => $this->context->getOption('typo.theme_advanced_font_sizes', '80%,90%,100%,120%,140%,160%,180%,220%,260%,320%,400%,500%,700%', $this->properties),
			'theme_advanced_styles' => $this->context->getOption('typo.css_selectors', '', $this->properties),
			'use_browser' => $this->context->getOption('use_browser', true, $this->properties),
		));

		/* add properties that only have a value, due to TypoMCE bug with empty value here */
		$tp = $this->context->getOption('typo.template_selected_content_classes', '', $this->properties);
		if (!empty($tp)) {
			$this->properties['template_selected_content_classes'] = $tp;
		}

	}


	/**
	 * Initialize TypoMCE
	 * @return string
	 */
	public function initialize() {
		if (!$this->jsLoaded) {
			$scriptFile = ((!$this->properties['frontend'] && $this->properties['compressor'] == 'enabled') ? 'tiny_mce_gzip.php' : 'tiny_mce.js');
			if ($this->context->getOption('typo.use_uncompressed_library', false)) {
				$scriptFile = 'tiny_mce_src.js';
			}
			$this->modx->lexicon->load('typomce:default');
			$lang = $this->modx->lexicon->fetch('typo.', true);
			$compressJs = $this->context->getOption('typo.compress_js', false);
			$this->modx->getVersionData();
			$inRevo20 = (boolean)version_compare($this->modx->version['full_version'], '2.1.0-rc1', '<');

			$this->modx->regClientStartupScript($this->config['assetsUrl'] . 'jscripts/tiny_mce/' . $scriptFile);
			$this->modx->regClientStartupScript($this->config['assetsUrl'] . 'xconfig.js');
			if ($compressJs) {
				$this->modx->regClientStartupScript($this->config['assetsUrl'] . 'typo.min.js');
			}
			else {
				$this->modx->regClientStartupScript($this->config['assetsUrl'] . 'typo.js');
			}

			$source = $this->context->getOption('default_media_source', 1);
			$this->modx->regClientStartupHTMLBlock('<script type="text/javascript">' . "\n//<![CDATA[" . "\nvar inRevo20 = " . ($inRevo20 ? 1 : 0) . ";MODx.source = '" . $source . "';Tiny.lang = " . $this->modx->toJSON($lang) . ';' . "\n//]]>" . "\n</script>");
			if (!$compressJs) {
				$this->modx->regClientStartupScript($this->config['assetsUrl'] . 'tinymce.panel.js');
			}


			$this->jsLoaded = true;
		}

		return $this->getScript();
	}


	public function getEditingContext() {
		if ($this->modx->context->get('key') == 'mgr') {
			/** @var modResource $resource */
			$resource = !empty($this->config['resource']) ? $this->config['resource'] : '';
			if ($resource and $resource instanceof modResource) {
				$this->context = $this->modx->getObject('modContext', $resource->get('context_key'));
				if ($this->context) {
					$this->context->prepare();
				}
			}
		}
		if (empty($this->context)) {
			$this->context =& $this->modx->context;
		}

		return $this->context;
	}


	/**
	 * Renders the TypoMCE script code.
	 *
	 * @return string
	 */
	public function getScript() {
		/* backwards compat */
		if ($this->properties['theme'] == 'editor' || $this->properties['theme'] == 'custom') {
			$this->properties['theme'] = 'advanced';
		}
		$this->properties['document_base_url'] = $this->config['baseUrl'];

		/* Set relative URL options */
		switch ($this->properties['path_options']) {
			default:
			case 'docrelative':
				$this->properties['relative_urls'] = true;
				$this->properties['remove_script_host'] = true;

				$baseUrl = $this->modx->context->getOption('base_url', MODX_BASE_URL);
				if (!empty($this->config['resource']) && $this->context) {
					$baseUrl = $this->context->getOption('base_url', $baseUrl);
				}
				$this->properties['document_base_url'] = $baseUrl;
				break;

			case 'rootrelative':
				$this->properties['relative_urls'] = false;
				$this->properties['remove_script_host'] = true;
				break;

			case 'fullpathurl':
				$this->properties['relative_urls'] = false;
				$this->properties['remove_script_host'] = false;
				break;
		}

		$richtextResource = true;
		if (!empty($this->config['resource'])) {
			if (!$this->config['resource']->get('richtext')) {
				unset($this->properties['elements']);
				$richtextResource = false; /* workaround for modx ui bug with rte tvs */
			}
			$this->config['resource'] = $this->config['resource']->toArray();
		}
		$templates = $this->getTemplateList();

		/* get formats */
		//$this->properties['formats'] = $this->getFormats();
		/* get JS */
		unset($this->properties['resource']);
		ob_start();
		include_once dirname(__FILE__) . '/templates/script.tpl';
		$script = ob_get_contents();
		ob_end_clean();

		/* will need to do $this->modx->controller->addHtml() for Revo 2.2+ */
		$this->modx->regClientStartupHTMLBlock($script);

		return '';
	}


	/**
	 * Allows for custom templates
	 *
	 * @return array
	 */
	public function getTemplateList() {
		$list = array();

		$templateListSnippet = $this->context->getOption('typo.template_list_snippet', '', $this->properties);
		if (!empty($templateListSnippet)) {
			$templateList = $this->modx->runSnippet($templateListSnippet);
		}
		else {
			$templateList = $this->context->getOption('typo.template_list', '', $this->properties);
		}

		if (empty($templateList)) {
			return $list;
		}

		$templateList = explode(',', $templateList);
		foreach ($templateList as $template) {
			if (empty($template)) {
				continue;
			}
			$templateParams = explode(':', $template);
			if (count($templateParams) < 2) {
				continue;
			}

			$t = array(
				$templateParams[0],
				$templateParams[1]
			);
			if (!empty($templateParams[2])) {
				array_push($t, $templateParams[2]);
			}
			$list[] = $t;
		}

		return $list;
	}


	/**
	 * Gets the MODx modAction for the rte browser.
	 * @return modAction
	 */
	private function _getBrowserAction() {
		if (intval($_REQUEST['a']) > 0 || empty($_REQUEST['a'])) {
			/** @var modAction $actionObj */
			$actionObj = $this->modx->getObject('modAction', array('controller' => 'browser'));
			$action = $actionObj ? $actionObj->get('id') : 1;
		}
		else {
			$action = 'browser';
		}

		return $action;
	}


	/**
	 * Allows for custom formats.
	 *
	 * TODO: Figure out proprietary storage format to have this work. Currently
	 * ignored.
	 *
	 * @return string
	 */
	public function getFormats() {
		$formats = explode(',', $this->properties['formats']);
		$fs = array();
		foreach ($formats as $format) {
			$fs[$format] = new stdClass();
		}
		$formats = json_encode($fs);
		unset($this->properties['formats']);

		return '';
	}
}