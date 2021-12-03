<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_apimanagers
 *
 * @copyright   (C) 2021 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Layout\LayoutHelper;

$displayData = [
	'textPrefix' => 'COM_APIMANAGERS',
	'formURL'    => 'index.php?option=com_apimanagers&view=apimanagers',
	'helpURL'    => 'https://docs.joomla.org/Special:MyLanguage/Help4.x:News_Feeds',
	'icon'       => 'icon-rss apimanagers',
];

$user = Factory::getApplication()->getIdentity();

if ($user->authorise('core.create', 'com_apimanagers') || count($user->getAuthorisedCategories('com_apimanagers', 'core.create')) > 0)
{
	$displayData['createURL'] = 'index.php?option=com_apimanagers&task=apimanager.add';
}

echo LayoutHelper::render('joomla.content.emptystate', $displayData);
