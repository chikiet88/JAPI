<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_apimanagers
 *
 * @copyright   (C) 2009 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Component\Apimanagers\Administrator\Controller;

\defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Factory;
use Joomla\CMS\Versioning\VersionableControllerTrait;
use Joomla\Utilities\ArrayHelper;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Filesystem\Path;
/**
 * Apimanager controller class.
 *
 * @since  1.6
 */
class ApimanagerController extends FormController
{
    public function CreateApi()
    {
        $data = json_decode(file_get_contents("php://input"));
        $TenAPI = $data->x;
		$SQL = $data->y;
        $html1 ='';
        $html2 ='';
        $html3 ="\$db->quoteName('a.id'),
        ";
		$dulieu = $data->dulieu;
        foreach($dulieu as $dulieu)
        {
        $html1 .=" ,
           '".$dulieu->field."'"; 
            
        if($dulieu->type=='text')    
        {
        $html2 .='
         <field name="'.$dulieu->field.'" type="'.$dulieu->type.'" filter="JComponentHelper::filterText"/>';
        }
        else
         {
         $html2 .='
         <field name="'.$dulieu->field.'" type="'.$dulieu->type.'"/>';        
            }

          	$html3 .=" \$db->quoteName('a.".$dulieu->field."'),
            ";  
        }        
        if(!empty($SQL)){$sql = $this->SQL($SQL);}
        $this->Folder($TenAPI);
        $this->File($TenAPI,$html1,$html2,$html3);
        print_r($sql.' - '.$TenAPI);
        //print_r($data);
       // print_r($html1);
        
    }
    public function SQL($SQL)
    {
    $db = Factory::getDbo();
	$query = $db->getQuery(true);      
    $db->setQuery($SQL);
    $result = $db->execute();
    return $result;      
    }  

    public function Folder($TenAPI)
    {
    $Tenhoa = ucfirst($TenAPI);  
    Folder::create(JPATH_ROOT.'/api/components/com_apimanagers/src/View/'.$Tenhoa.'s');             
    }
    public function File($TenAPI,$html1,$html2,$html3)
    { 
    $Tenhoa = ucfirst($TenAPI); 	
    $file1 ="<?php
namespace Joomla\Component\Apimanagers\Api\View\\".$Tenhoa."s;
\defined('_JEXEC') or die;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\MVC\View\JsonApiView as BaseApiView;
class JsonapiView extends BaseApiView
{ 
    protected \$fieldsToRenderItem = [
        'id'".$html1."
	];
protected \$fieldsToRenderList = [
        'id'".$html1."
	];

}"; 
//File 2
 $file2="<?php
namespace Joomla\Component\Apimanagers\Api\Controller;
\defined('_JEXEC') or die;
use Joomla\CMS\MVC\Controller\ApiController;
use Joomla\CMS\Filter\InputFilter;
class ".$Tenhoa."sController extends ApiController
{
	protected \$contentType = '".$TenAPI."s';
	protected \$default_view = '".$TenAPI."s';
    public function displayList()
	{
		\$apiFilterInfo = \$this->input->get('filter', [], 'array');
		\$filter        = InputFilter::getInstance();		
        if (array_key_exists('published', \$apiFilterInfo))
		{
			\$this->modelState->set('filter.published', \$filter->clean(\$apiFilterInfo['published'], 'STR'));
		}           
		return parent::displayList();
	} 
    
}
";
//File 3
$file3='<?xml version="1.0" encoding="utf-8"?>
<form addfieldprefix="Joomla\Component\Apimanagers\Administrator\Field">
	<fieldset>
		<field name="id" type="number"/>
        '.$html2.'
	</fieldset>
</form>';
//File4
$file4="<?php
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
class ".$Tenhoa."Model extends AdminModel
{
	use VersionableModelTrait;
	public \$typeAlias = 'com_apimanagers.".$TenAPI."';
	protected \$text_prefix = 'COM_APIMANAGERS';
	protected function canDelete(\$record)
	{
		if (empty(\$record->id) || \$record->published != -2)
		{
			return false;
		}

		if (!empty(\$record->catid))
		{
			return Factory::getUser()->authorise('core.delete', 'com_apimanager.category.' . (int) \$record->catid);
		}

		return parent::canDelete(\$record);
	}
	protected function canEditState(\$record)
	{
		if (!empty(\$record->catid))
		{
			return Factory::getUser()->authorise('core.edit.state', 'com_apimanagers.category.' . (int) \$record->catid);
		}

		return parent::canEditState(\$record);
	}
	public function getForm(\$data = array(), \$loadData = true)
	{
		// Get the form.
		\$form = \$this->loadForm('com_apimanagers.".$TenAPI."s', '".$TenAPI."s', array('control' => 'jform', 'load_data' => \$loadData));

		if (empty(\$form))
		{
			return false;
		}
		return \$form;
	}
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		\$data = Factory::getApplication()->getUserState('com_apimanagers.edit.".$TenAPI."s.data', array());

		if (empty(\$data))
		{
			\$data = \$this->getItem();
		}

		\$this->preprocessData('com_apimanagers.".$TenAPI."s', \$data);

		return \$data;
	}
	public function save(\$data)
	{
		\$input = Factory::getApplication()->input;
		return parent::save(\$data);
	}
    
  	public function delete(&\$pks)
	{
		\$return = parent::delete(\$pk);

		if (\$return)
		{
			\$db = \$this->getDbo();
			\$query = \$db->getQuery(true)
				->delete(\$db->quoteName('#__apimanagers_".$TenAPI."'))
				->where(\$db->quoteName('id').'='. \$pks);
			\$db->setQuery(\$query);
			\$db->execute();
		}

		return \$return;
	}  
	public function getItem(\$pk = null)
	{
		if (\$item = parent::getItem(\$pk))
		{
		}
		return \$item;
	}
	protected function prepareTable(\$table)
	{
		\$date = Factory::getDate();
		\$user = Factory::getUser();
		\$table->name = htmlspecialchars_decode(\$table->name, ENT_QUOTES);
		\$table->alias = ApplicationHelper::stringURLSafe(\$table->alias, \$table->language);
		if (empty(\$table->alias))
		{
			\$table->alias = ApplicationHelper::stringURLSafe(\$table->name, \$table->language);
		}
		if (empty(\$table->id))
		{
            // Set the values
			\$table->created = \$date->toSql();
			// Set ordering to the last item if not set
			if (empty(\$table->ordering))
			{
				\$db = \$this->getDbo();
				\$query = \$db->getQuery(true)
					->select('MAX(' . \$db->quoteName('ordering') . ')')
					->from(\$db->quoteName('#__apimanagers_".$TenAPI."'));
				\$db->setQuery(\$query);
				\$max = \$db->loadResult();

				\$table->ordering = \$max + 1;
			}
		}
		else
		{
			// Set the values
			\$table->modified = \$date->toSql();
			\$table->modified_by = \$user->get('id');
		}

		// Increment the content version number.
		\$table->version++;
	}
	public function publish(&\$pks, \$value = 1)
	{
		\$result = parent::publish(\$pks, \$value);
		// Clean extra cache for apimanagers
		\$this->cleanCache('feed_parser');

		return \$result;
	}
	protected function getReorderConditions(\$table)
	{
		return [
			\$this->_db->quoteName('catid') . ' = ' . (int) \$table->catid,
		];
	}
	protected function preprocessForm(Form \$form, \$data, \$group = 'content')
	{
		parent::preprocessForm(\$form, \$data, \$group);
	}
	public function validate(\$form, \$data, \$group = null)
	{
		// Don't allow to change the users if not allowed to access com_users.
		if (!Factory::getUser()->authorise('core.manage', 'com_users'))
		{
			if (isset(\$data['created_by']))
			{
				unset(\$data['created_by']);
			}
		}
		return parent::validate(\$form, \$data, \$group);
	}
}";
//File5
$file5 = "<?php
namespace Joomla\Component\Apimanagers\Administrator\Model;
\defined('_JEXEC') or die;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\Database\ParameterType;
use Joomla\Utilities\ArrayHelper;
class ".$Tenhoa."sModel extends ListModel
{
	public function __construct(\$config = array(), MVCFactoryInterface \$factory = null)
	{
		if (empty(\$config['filter_fields']))
		{
			\$config['filter_fields'] = array(
                
				'id', 'a.id',
				'published', 'a.published',
				'created', 'a.created',
				'created_by', 'a.created_by',
				'modified', 'a.modified',
				'modified_by', 'a.modified_by',
				'ordering', 'a.ordering',
			);
		}

		parent::__construct(\$config, \$factory);
	}
	protected function populateState(\$ordering = 'a.id', \$direction = 'asc')
	{
		\$app = Factory::getApplication();

		// Load the parameters.
		\$params = ComponentHelper::getParams('com_apimanagers');
		\$this->setState('params', \$params);
		// List state information.
		parent::populateState(\$ordering, \$direction);
	}
	protected function getStoreId(\$id = '')
	{
		// Compile the store id.
		\$id .= ':' . \$this->getState('filter.search');
		\$id .= ':' . \$this->getState('filter.published');
		\$id .= ':' . \$this->getState('filter.category_id');
		\$id .= ':' . \$this->getState('filter.access');
		\$id .= ':' . \$this->getState('filter.language');
		\$id .= ':' . \$this->getState('filter.level');
		\$id .= ':' . serialize(\$this->getState('filter.tag'));

		return parent::getStoreId(\$id);
	}
	protected function getListQuery()
	{
		// Create a new query object.
		\$db    = \$this->getDbo();
		\$query = \$db->getQuery(true);
		\$user  = Factory::getUser();

		// Select the required fields from the table.
		\$query->select(
			\$this->getState(
				'list.select',
				[
				".$html3."            
				]
			)
		)//->select([\$db->quoteName('u.name', 'Nguoigiao'),\$db->quoteName('u1.name', 'Nguoinhan')])
        ->from(\$db->quoteName('#__apimanagers_".$TenAPI."', 'a'));
       // ->join('LEFT', \$db->quoteName('#__users', 'u'), \$db->quoteName('u.id') . ' = ' . \$db->quoteName('a.idGiao'))
     // ->join('LEFT', \$db->quoteName('#__users', 'u1'), \$db->quoteName('u1.id') . ' = ' . \$db->quoteName('a.idNhan'));
		// Filter by access level.
		if (\$access = (int) \$this->getState('filter.access'))
		{
			\$query->where(\$db->quoteName('a.access') . ' = :access')
				->bind(':access', \$access, ParameterType::INTEGER);
		}
		// Filter by published state.
		\$published = (string) \$this->getState('filter.published');

		if (is_numeric(\$published))
		{
			\$published = (int) \$published;
			\$query->where(\$db->quoteName('a.published') . ' = :published')
				->bind(':published', \$published, ParameterType::INTEGER);
		}
		elseif (\$published === '')
		{
			\$query->where(\$db->quoteName('a.published') . ' IN (0, 1)');
		}

		// Filter by category.
		\$categoryId = \$this->getState('filter.category_id');

		if (is_numeric(\$categoryId))
		{
			\$categoryId = (int) \$categoryId;
			\$query->where(\$db->quoteName('a.catid') . ' = :categoryId')
				->bind(':categoryId', \$categoryId, ParameterType::INTEGER);
		}

		// Filter on the level.
		if (\$level = (int) \$this->getState('filter.level'))
		{
			\$query->where(\$db->quoteName('c.level') . ' <= :level')
				->bind(':level', \$level, ParameterType::INTEGER);
		}

		// Filter by search in title
		if (\$search = \$this->getState('filter.search'))
		{
			if (stripos(\$search, 'id:') === 0)
			{
				\$search = (int) substr(\$search, 3);
				\$query->where(\$db->quoteName('a.id') . ' = :search')
					->bind(':search', \$search, ParameterType::INTEGER);
			}
			else
			{
				\$search = '%' . str_replace(' ', '%', trim(\$search)) . '%';
				\$query->where('(' . \$db->quoteName('a.name') . ' LIKE :search1 OR ' . \$db->quoteName('a.alias') . ' LIKE :search2)')
					->bind([':search1', ':search2'], \$search);
			}
		}

		// Filter on the language.
		if (\$language = \$this->getState('filter.language'))
		{
			\$query->where(\$db->quoteName('a.language') . ' = :language')
				->bind(':language', \$language);
		}
		// Add the list ordering clause.
		\$orderCol  = \$this->state->get('list.ordering', 'a.id');
		\$orderDirn = \$this->state->get('list.direction', 'ASC');

		if (\$orderCol == 'a.ordering' || \$orderCol == 'category_title')
		{
			\$ordering = [
				\$db->quoteName('c.title') . ' ' . \$db->escape(\$orderDirn),
				\$db->quoteName('a.ordering') . ' ' . \$db->escape(\$orderDirn),
			];
		}
		else
		{
			\$ordering = \$db->escape(\$orderCol) . ' ' . \$db->escape(\$orderDirn);
		}

		\$query->order(\$ordering);

		return \$query;
	}
    public function getItems()
	{
		\$items = parent::getItems();

		foreach (\$items as \$item)
		{
			\$item->typeAlias = 'com_apimanagers.".$TenAPI."';

            //			if (isset(\$item->Dulieu))
            //			{
            //				\$registry = new Registry(\$item->Dulieu);
            //				\$item->Dulieu = \$registry->toArray();
            //			}
		}

		return \$items;
	}  
}
";
//File 6
$file6 = "<?php
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

class ".$Tenhoa."Table extends Table implements VersionableTableInterface, TaggableTableInterface
{
	use TaggableTableTrait;
	protected \$_supportNullValue = true;
	protected \$_jsonEncode = array('params', 'metadata', 'images');
	public function __construct(DatabaseDriver \$db)
	{
		\$this->typeAlias = 'com_apimanagers.".$TenAPI."s';
		parent::__construct('#__apimanagers_".$TenAPI."', 'id', \$db);
	}
	public function check()
	{
		try
		{
			parent::check();
		}
		catch (\Exception \$e)
		{
			\$this->setError(\$e->getMessage());

			return false;
		}
//		if (trim(\$this->name) == '')
//		{
//			\$this->setError(Text::_('COM_APIMANAGERS_WARNING_PROVIDE_VALID_NAME'));
//
//			return false;
//		}
		return true;
	}
	public function store(\$updateNulls = true)
	{
		\$date = Factory::getDate();
		\$user = Factory::getUser();

		// Set created date if not set.
		if (!(int) \$this->created)
		{
			\$this->created = \$date->toSql();
		}

		if (\$this->id)
		{
			// Existing item
			\$this->modified_by = \$user->get('id');
			\$this->modified    = \$date->toSql();
		}
		else
		{
			// Field created_by can be set by the user, so we don't touch it if it's set.
			if (empty(\$this->created_by))
			{
				\$this->created_by = \$user->get('id');
			}

			if (!(int) \$this->modified)
			{
				\$this->modified = \$this->created;
			}

			if (empty(\$this->modified_by))
			{
				\$this->modified_by = \$this->created_by;
			}
		}
		return parent::store(\$updateNulls);
	}
	public function getTypeAlias()
	{
		return \$this->typeAlias;
	}
}";
File::write(JPATH_ROOT.'/api/components/com_apimanagers/src/View/'.$Tenhoa.'s/JsonapiView.php', $file1);
File::write(JPATH_ROOT.'/api/components/com_apimanagers/src/Controller/'.$Tenhoa.'sController.php', $file2);
File::write(JPATH_ROOT.'/administrator/components/com_apimanagers/forms/'.$TenAPI.'s.xml', $file3);
File::write(JPATH_ROOT.'/administrator/components/com_apimanagers/src/Model/'.$Tenhoa.'Model.php', $file4);
File::write(JPATH_ROOT.'/administrator/components/com_apimanagers/src/Model/'.$Tenhoa.'sModel.php', $file5);
File::write(JPATH_ROOT.'/administrator/components/com_apimanagers/src/Table/'.$Tenhoa.'Table.php', $file6);
    }   
  
}
