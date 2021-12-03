<?php
namespace Joomla\Component\Apimanagers\Administrator\Table;
\defined('_JEXEC') or die;
use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\String\PunycodeHelper;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Tag\TaggableTableInterface;
use Joomla\CMS\Tag\TaggableTableTrait;
use Joomla\CMS\Versioning\VersionableTableInterface;
use Joomla\Database\DatabaseDriver;
use Joomla\String\StringHelper;

class YeucaudaotaoTable extends Table implements VersionableTableInterface, TaggableTableInterface
{
	use TaggableTableTrait;
	protected $_supportNullValue = true;
	protected $_jsonEncode = array('params', 'metadata', 'images');
	public function __construct(DatabaseDriver $db)
	{
		$this->typeAlias = 'com_apimanagers.yeucaudaotaos';
		parent::__construct('#__apimanagers_yeucaudaotao', 'id', $db);
	}
	public function check()
	{
		try
		{
			parent::check();
		}
		catch (\Exception $e)
		{
			$this->setError($e->getMessage());

			return false;
		}
//		if (trim($this->name) == '')
//		{
//			$this->setError(Text::_('COM_APIMANAGERS_WARNING_PROVIDE_VALID_NAME'));
//
//			return false;
//		}
		return true;
	}
	public function store($updateNulls = true)
	{
		$date = Factory::getDate();
		$user = Factory::getUser();

		// Set created date if not set.
		if (!(int) $this->created)
		{
			$this->created = $date->toSql();
		}

		if ($this->id)
		{
			// Existing item
			$this->modified_by = $user->get('id');
			$this->modified    = $date->toSql();
		}
		else
		{
			// Field created_by can be set by the user, so we don't touch it if it's set.
			if (empty($this->created_by))
			{
				$this->created_by = $user->get('id');
			}

			if (!(int) $this->modified)
			{
				$this->modified = $this->created;
			}

			if (empty($this->modified_by))
			{
				$this->modified_by = $this->created_by;
			}
		}
		return parent::store($updateNulls);
	}
	public function getTypeAlias()
	{
		return $this->typeAlias;
	}
}