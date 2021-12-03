<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_apimanagers
 *
 * @copyright   (C) 2008 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined( '_JEXEC' )or die;

use Joomla\ CMS\ Factory;
use Joomla\ CMS\ HTML\ HTMLHelper;
use Joomla\ CMS\ Language\ Associations;
use Joomla\ CMS\ Language\ Multilanguage;
use Joomla\ CMS\ Language\ Text;
use Joomla\ CMS\ Layout\ LayoutHelper;
use Joomla\ CMS\ Router\ Route;
use Joomla\ CMS\ Session\ Session;

HTMLHelper::_( 'script', 'https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js', array( 'version' => 'auto' ), array( 'defer' => 'true' ) );
HTMLHelper::_( 'script', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js', array( 'version' => 'auto' ), array( 'defer' => 'true' ) );
HTMLHelper::_( 'script', 'https://ajax.googleapis.com/ajax/libs/angularjs/1.8.2/angular.min.js', array( 'version' => 'auto' ), array( 'defer' => 'true' ) );
HTMLHelper::_( 'script', 'https://cdnjs.cloudflare.com/ajax/libs/mouse0270-bootstrap-notify/3.1.3/bootstrap-notify.min.js', array( 'version' => 'auto' ), array( 'defer' => 'true' ) );
HTMLHelper::_( 'script', 'https://tazagroup.vn/media/vendor/tinymce/tinymce.js', array( 'version' => 'auto' ), array( 'defer' => 'true' ) );
HTMLHelper::_( 'script', 'administrator/components/com_apimanagers/js/angular-ui-tinymce.js', array( 'version' => 'auto' ), array( 'defer' => 'true' ) );
HTMLHelper::_( 'script', 'administrator/components/com_apimanagers/js/main.js', array( 'version' => 'auto' ), array( 'defer' => 'true' ) );
HTMLHelper::_( 'stylesheet', 'administrator/components/com_apimanagers/css/main.css', array( 'version' => 'auto' ), array( 'defer' => 'true' ) );
$user = Factory::getUser();
?>
<div class="container">
  <div class="row mt-3" ng-app="Admin" ng-controller="Admin">
    <div class="col-sm-12">
      <div class="mb-3 row">
<div class="col-4">        
        <input type="text" class="form-control mb-3" placeholder="Tên API" ng-model="TenAPI">
        <textarea class="form-control mb-3" ng-model="QSQL" rows="20"></textarea>
          </div>    
<div class="col-8">       
    <div ng-repeat="input in inputs" class="row mb-3">
         <div class="col-1 d-flex"> 
         <span class="m-auto">{{$index+1}}</span> <input type="checkbox" class="mx-2 m-auto" ng-model="input.Null" > <span class="m-auto">  Null</span>
         </div>
         <div class="col-4"><input type="text" class="form-control" ng-model="input.field" placeholder="Trường {{$index}}"></div>
         <div class="col-4"> <input type="text" class="form-control" ng-model="input.type" placeholder="Kiểu {{$index}}"></div>
         <div class="col-3"> <input type="text" class="form-control" ng-model="input.value" placeholder="Giá trị {{$index}}"></div>

        </div>
        <button class="btn btn-info" ng-click="addinput()">Thêm</button>
        <button class="btn btn-info" ng-click="loadsql()">Load</button>
        <button class="btn btn-info" ng-click="XoaTB()">Xóa</button>
       <div class="btn btn-info" ng-click="CreateAPI(TenAPI,QSQL)"><i class="fas fa-save"></i> Lưu</div> 
          </div>
<!--
        <div class="p-4"> {{TenAPI}} - {{SQL}}    -       {{inputs}}sađá 
          <textarea ui-tinymce="tinymceOptions" ng-model="tinymceModel"></textarea>
        </div>
-->
      </div>
    </div>
  </div>
</div>
