<?php
/**
 * TypoMCE RichText Editor Plugin
 *
 * Events: OnRichTextEditorInit, OnRichTextEditorRegister,
 * OnBeforeManagerPageInit, OnRichTextBrowserInit
 *
 * @author Jeff Whitfield <jeff@collabpad.com>
 * @author Shaun McCormick <shaun@collabpad.com>
 * @author Vasiliy Naumkin <bezumkin@yandex.ru>
 *
 * @var modX $modx
 * @var array $scriptProperties
 *
 * @package typomce
 * @subpackage build
 */
if ($modx->event->name == 'OnRichTextEditorRegister') {
	$modx->event->output('TypoMCE');

	return;
}
require_once $modx->getOption('typo.core_path', null, MODX_CORE_PATH . 'components/typomce/typomce.class.php');
$typo = new TypoMCE($modx, $scriptProperties);

$useEditor = $typo->context->getOption('use_editor', false);
$whichEditor = $typo->context->getOption('which_editor', '');

/* Handle event */
switch ($modx->event->name) {
	case 'OnRichTextEditorInit':
		if ($useEditor && $whichEditor == 'TypoMCE') {
			unset($scriptProperties['chunk']);
			if (isset($forfrontend) || $modx->context->get('key') != 'mgr') {
				$def = $typo->context->getOption('cultureKey', $typo->context->getOption('manager_language', 'en'));
				$typo->properties['language'] = $modx->getOption('fe_editor_lang', array(), $def);
				$typo->properties['frontend'] = true;
				unset($def);
			}
			/* commenting these out as it causes problems with richtext tvs */
			//if (isset($scriptProperties['resource']) && !$resource->get('richtext')) return;
			//if (!isset($scriptProperties['resource']) && !$modx->getOption('richtext_default',null,false)) return;
			$typo->setProperties($scriptProperties);
			$html = $typo->initialize();
			$modx->event->output($html);
			unset($html);
		}
		break;

	case 'OnRichTextBrowserInit':
		if ($useEditor && $whichEditor == 'TypoMCE') {
			$inRevo20 = (boolean)version_compare($modx->version['full_version'], '2.1.0-rc1', '<');
			$modx->getVersionData();
			$source = $typo->context->getOption('default_media_source', null, 1);

			$modx->controller->addHtml('<script type="text/javascript">var inRevo20 = ' . ($inRevo20 ? 1 : 0) . ';MODx.source = "' . $source . '";</script>');

			$modx->controller->addJavascript($typo->config['assetsUrl'] . 'jscripts/tiny_mce/tiny_mce_popup.js');
			if (file_exists($typo->config['assetsPath'] . 'jscripts/tiny_mce/langs/' . $typo->properties['language'] . '.js')) {
				$modx->controller->addJavascript($typo->config['assetsUrl'] . 'jscripts/tiny_mce/langs/' . $typo->properties['language'] . '.js');
			}
			else {
				$modx->controller->addJavascript($typo->config['assetsUrl'] . 'jscripts/tiny_mce/langs/en.js');
			}
			$modx->controller->addJavascript($typo->config['assetsUrl'] . 'tiny.browser.js');
			$modx->event->output('Tiny.browserCallback');
		}

		return '';
		break;

	default:
		break;
}
return;