<?php
namespace Joomla\Component\Hrms\Api\Controller;
\defined('_JEXEC') or die;
use Joomla\CMS\MVC\Controller\ApiController;
use Joomla\CMS\Filter\InputFilter;
class LophocsController extends ApiController
{
	protected $contentType = 'lophocs';
	protected $default_view = 'lophocs';
    public function displayList()
	{
		$apiFilterInfo = $this->input->get('filter', [], 'array');
		$filter        = InputFilter::getInstance();		
        if (array_key_exists('published', $apiFilterInfo))
		{
			$this->modelState->set('filter.published', $filter->clean($apiFilterInfo['published'], 'STR'));
		}        
        if (array_key_exists('MaLop', $apiFilterInfo))
		{
			$this->modelState->set('filter.MaLop', $filter->clean($apiFilterInfo['MaLop'], 'STR'));
		}   
        if (array_key_exists('LinkLop', $apiFilterInfo))
		{
			$this->modelState->set('filter.LinkLop', $filter->clean($apiFilterInfo['LinkLop'], 'STR'));
		}           
		return parent::displayList();
	} 
    
}
