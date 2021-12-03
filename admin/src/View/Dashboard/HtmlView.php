<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_apimanagers
 *
 * @copyright   (C) 2008 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Component\Apimanagers\Administrator\View\Dashboard;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;

/**
 * View to edit a apimanager.
 *
 * @since  1.6
 */
class HtmlView extends BaseHtmlView
{
	/**
	 * The item object for the apimanager
	 *
	 * @var    \JObject
	 * @since  1.6
	 */
	protected $item;

	/**
	 * The form object for the apimanager
	 *
	 * @var    \JForm
	 * @since  1.6
	 */
	protected $form;

	/**
	 * The model state of the apimanager
	 *
	 * @var    \JObject
	 * @since  1.6
	 */
	protected $state;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 *
	 * @since   1.6
	 */
	public function display($tpl = null)
	{
		$this->state = $this->get('State');
		$this->item  = $this->get('Item');
		$this->form  = $this->get('Form');
        Factory::getApplication()->input->set('hidemainmenu', true);
        $title = Text::_('APIMANAGER');
		ToolbarHelper::title($title, 'rss apimanagers');
		parent::display($tpl);
        
	}

}
