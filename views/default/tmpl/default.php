<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_helloworld
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die;

JHtml::_('formbehavior.chosen', 'select');
?>
<form action="index.php?option=com_dclogview" method="post" id="adminForm" name="adminForm">

<h1>DC LOG VIEWER</h1>

<?php
echo $this->html_filelist;
if (!empty($this->html_filecontents)) {
	echo '<hr/>';
	echo $this->html_filecontents;
}
?>

	<input type="hidden" name="task" value=""/>
	<?php echo JHtml::_('form.token'); ?>
</form>

