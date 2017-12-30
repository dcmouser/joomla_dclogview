<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_dcadmin
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die;

class DcLogviewViewDefault extends JViewLegacy
{
	function display($tpl = null)
	{

		// add css
		$document = JFactory::getDocument();
		$csspath = JURI::root(true).'/administrator/components/com_dclogview/assets';
		//$document->addStyleSheet($csspath . '/pure-min.css');
		$document->addStyleSheet($csspath . '/logview.css');


		// create log viewer helper class
		require_once(__DIR__ . '/../../helpers/dclogviewer.php');
		$logbrowser = new DcLogViewer();
		// init with options
		$logbrowser->set_baseUrl('index.php?option=com_dclogview');
		//
		jimport('joomla.application.component.helper');
		$options = array();
		$params = JComponentHelper::getParams('com_dclogview');
		$logbrowser->addFileStringList($params->get('logfiles',''));
		$logbrowser->set_lineCount(intval($params->get('linecount','100')));
		// read file
		$filepath = JFactory::getApplication()->input->get('filepath','','STRING');
		if (!empty($filepath)) {
			$filepath = urldecode($filepath);
			$logbrowser->readFile($filepath);
			}


		// make file list
		$this->html_filelist = $logbrowser->makeFileListAsHtml();
		// current file contents
		$this->html_filecontents = $logbrowser->getFileContents();

		// Display the template
		parent::display($tpl);
	}
}
