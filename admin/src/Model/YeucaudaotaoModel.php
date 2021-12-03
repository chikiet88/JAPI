<?php
namespace Joomla\Component\Apimanagers\Administrator\Model;
\defined('_JEXEC') or die;
use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Helper\TagsHelper;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Language\LanguageHelper;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Versioning\VersionableModelTrait;
use Joomla\Component\Categories\Administrator\Helper\CategoriesHelper;
use Joomla\Registry\Registry;
class YeucaudaotaoModel extends AdminModel
{
	use VersionableModelTrait;
	public $typeAlias = 'com_apimanagers.yeucaudaotao';
	protected $text_prefix = 'COM_APIMANAGERS';
	protected function canDelete($record)
	{
		if (empty($record->id) || $record->published != -2)
		{
			return false;
		}

		if (!empty($record->catid))
		{
			return Factory::getUser()->authorise('core.delete', 'com_apimanager.category.' . (int) $record->catid);
		}

		return parent::canDelete($record);
	}
	protected function canEditState($record)
	{
		if (!empty($record->catid))
		{
			return Factory::getUser()->authorise('core.edit.state', 'com_apimanagers.category.' . (int) $record->catid);
		}

		return parent::canEditState($record);
	}
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_apimanagers.yeucaudaotaos', 'yeucaudaotaos', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}
		return $form;
	}
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = Factory::getApplication()->getUserState('com_apimanagers.edit.yeucaudaotaos.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		$this->preprocessData('com_apimanagers.yeucaudaotaos', $data);

		return $data;
	}
	public function save($data)
	{
		$input = Factory::getApplication()->input;
		return parent::save($data);
	}
    
  	public function delete(&$pks)
	{
		$return = parent::delete($pk);

		if ($return)
		{
			$db = $this->getDbo();
			$query = $db->getQuery(true)
				->delete($db->quoteName('#__apimanagers_yeucaudaotao'))
				->where($db->quoteName('id').'='. $pks);
			$db->setQuery($query);
			$db->execute();
		}

		return $return;
	}  
	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk))
		{
		}
		return $item;
	}
	protected function prepareTable($table)
	{
		$date = Factory::getDate();
		$user = Factory::getUser();
		$table->name = htmlspecialchars_decode($table->name, ENT_QUOTES);
		$table->alias = ApplicationHelper::stringURLSafe($table->alias, $table->language);
		if (empty($table->alias))
		{
			$table->alias = ApplicationHelper::stringURLSafe($table->name, $table->language);
		}
		if (empty($table->id))
		{
            // Set the values
			$table->created = $date->toSql();
			// Set ordering to the last item if not set
			if (empty($table->ordering))
			{
				$db = $this->getDbo();
				$query = $db->getQuery(true)
                ->select('MAX(' . $db->quoteName('ordering') . ')')
				->from($db->quoteName('#__apimanagers_yeucaudaotao'))
                ->where($db->quoteName('BPDDT')."=".$db->quote($table->BPDDT));
				$db->setQuery($query);
				$max = $db->loadResult();
				$table->ordering = $max + 1;
			}
		}
		else
		{
			// Set the values
			$table->modified = $date->toSql();
			$table->modified_by = $user->get('id');
		}

		// Increment the content version number.
		$table->version++;
	}
	public function publish(&$pks, $value = 1)
	{
		$result = parent::publish($pks, $value);
		// Clean extra cache for apimanagers
		$this->cleanCache('feed_parser');

		return $result;
	}
	protected function getReorderConditions($table)
	{
		return [
			$this->_db->quoteName('catid') . ' = ' . (int) $table->catid,
		];
	}
	protected function preprocessForm(Form $form, $data, $group = 'content')
	{
		parent::preprocessForm($form, $data, $group);
	}
	public function validate($form, $data, $group = null)
	{
		// Don't allow to change the users if not allowed to access com_users.
		if (!Factory::getUser()->authorise('core.manage', 'com_users'))
		{
			if (isset($data['created_by']))
			{
				unset($data['created_by']);
			}
		}
		return parent::validate($form, $data, $group);
	}
}