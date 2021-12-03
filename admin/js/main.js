var app = angular.module('Admin', ['ui.tinymce']);
app.controller('Admin', function($scope,$http) {
  $scope.tinymceOptions = {
    plugins: 'link image code',
    toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | code'
  };
 $scope.tinymceModel ="<p>CREATE TABLE IF NOT EXISTS `ggurw_apimanagers_tailieunguon` (</p><p>&nbsp; `id` int unsigned NOT NULL AUTO_INCREMENT,</p><p>&nbsp; `idChude` int(11) NOT NULL DEFAULT '0',</p><p>&nbsp; `idTG` int(11) NOT NULL DEFAULT '0',</p><p>&nbsp; `idGTG` varchar(200) NOT NULL DEFAULT '0',</p><p>&nbsp; `Tentailieu` varchar(250) NULL,</p><p>&nbsp; `Mota` text NULL,</p><p>&nbsp; `Lienket` text NULL,</p><p>&nbsp; `Ghichu` text NULL,</p><p>&nbsp; `Trangthai` tinyint NOT NULL DEFAULT 0,</p><p>&nbsp; `published` tinyint NOT NULL DEFAULT 0,</p><p>&nbsp; `ordering` int NOT NULL DEFAULT 0,</p><p>&nbsp; `Ngaytao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP;,</p><p>&nbsp; `idTao` int unsigned NOT NULL DEFAULT 0,</p><p>&nbsp; PRIMARY KEY (`id`)</p><p>) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;</p><p>&nbsp;</p>";   
    $scope.inputs = [
        {"field":"Trangthai","type":"tinyint","value":"4","Null":true},
        {"field":"published","type":"tinyint","value":"4","Null":true},
        {"field":"ordering","type":"int","value":"10","Null":true},
        {"field":"Ngaytao","type":"datetime","value":"99","Null":true},
        {"field":"idTao","type":"int","value":"10","Null":true},
    ];
 $scope.QSQL = ""; 
    
$scope.XoaTB = function () {
 $scope.QSQL = "DROP TABLE IF EXISTS `ggurw_apimanagers_"+$scope.TenAPI+"`;";
  }     
$scope.loadsql = function () {
$scope.QSQL = ""; 
 $scope.QSQL += "CREATE TABLE IF NOT EXISTS `ggurw_apimanagers_"+$scope.TenAPI+"` (`id` int unsigned NOT NULL AUTO_INCREMENT, ";   
    angular.forEach($scope.inputs, function (v) { 
        var x = (v.Null==true) ? "NOT NULL DEFAULT '0'":"NULL";
        var y = (v.value==0) ? "":"("+v.value+")";
        if(v.value==99){
            x=" NOT NULL DEFAULT current_timestamp()";
            y="";
        }
          $scope.QSQL+= "`"+v.field+"` "+v.type+""+y+" "+x+",";
       console.log(v);
     }); 
  $scope.QSQL += " PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;";   
  }   
  
  $scope.addinput = function () {
    var newinput = {};
    $scope.inputs.unshift(newinput);
  }
  $scope.resetinput = function () {
    $scope.inputs = [
        {"field":"Trangthai","type":"tinyint","value":"4","Null":true},
        {"field":"published","type":"tinyint","value":"4","Null":true},
        {"field":"ordering","type":"int","value":"10","Null":true},
        {"field":"Ngaytao","type":"datetime","value":"99","Null":true},
        {"field":"idTao","type":"int","value":"10","Null":true},
    ];
  }
  $scope.delinput = function (input) {
    var index = $scope.inputs.indexOf(input);
    $scope.inputs.splice(index, 1);
  }    
  $scope.CreateAPI = function(x,y)
 {
      if(x===undefined)
          {
              alert('Nhập Tên API');
          }
      else
          {
     var data = {"x": x,"y": y,"dulieu":$scope.inputs};   
        $http.post("https://tazagroup.vn/administrator/index.php?option=com_apimanagers&task=apimanager.CreateApi&format=raw",data)
        .then(function(res) {
            console.log(res);
            $scope.resetinput();
            $scope.QSQL = "";  
            alert("Thành Công");
        }, function (res) {
      console.log(res);
    }
              
             );
          }
 }    
});