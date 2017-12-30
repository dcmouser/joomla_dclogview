<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_dcadmin
 */

// No direct access to this file
defined('_JEXEC') or die;


class DcLogviewController extends JControllerLegacy
{
	// fall back on just displaying this view
	protected $default_view = 'default';


	public function display($cachable = false, $urlparams = array()) {

		// Access check: is this user allowed to access the backend of this component?
		if (!JFactory::getUser()->authorise('core.manage', 'com_dclogview')) {
			throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
		}


		if (!$this->setupcontroller())
			return false;

		// create log viewer helper class
		require_once('helpers/dclogviewer.php');
		$logbrowser = new DcLogViewer();
		// init with options
		$logbrowser->set_baseUrl('index.php?option=com_dclogview');

		parent::display($cachable, $urlparams);
	}







	//---------------------------------------------------------------------------
	protected function setupcontroller() {
		$this->setupcontroller_addToolBar();
		if (!$this->setupcontroller_displayErrors())
			return false;
		return true;
	}


	protected function setupcontroller_displayErrors() {
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		return true;
	}
	

	protected function setupcontroller_addToolBar() {
		$title = 'DC LogView';
		JToolBarHelper::title($title, 'DcLogview');
		JToolBarHelper::preferences('com_dclogview');
		JToolbarHelper::help( 'COM_DCLOGVIEW_HELP_VIEW_TYPE1', true );
		return true;
	}

	//---------------------------------------------------------------------------

}
