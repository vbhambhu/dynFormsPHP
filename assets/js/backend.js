
//Anguar app
var app = angular.module('dynForm', []);



app.controller('cubeEditCtrl', function($scope,$http) {

  var cubeId = document.getElementById('cube_id').value;

    $scope.cube = {};

    $http.get('/api/cube_by_id', 
    { params: { id : cubeId }}).then(function successCallback(response) {

   // console.log(response);

      $scope.cube.id = response.data.id;
      $scope.cube.name = response.data.name;
      $scope.cube.description = response.data.description;
      $scope.cube.attributes = response.data.attributes;

      $scope.lastAddedID = response.data.attributes.length;

    }, function errorCallback(response) {
    // called asynchronously if an error occurs
    // or server returns response with an error status.
    });


    $scope.saveCube = function () {

      console.log(angular.copy($scope.cube));

      $http({
        method: 'POST',
        url: '/api/save_cube',
        data: $.param({cube: angular.copy($scope.cube) }),
        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function(response) {
       console.log(response);

    });


    }




    

    $scope.addItem = function (iType) {

      $scope.lastAddedID++;

      var newField = {
            "id" : $scope.lastAddedID,
            "identifier" : guid(),
            "label" : "New field - " + $scope.lastAddedID,
            "type" : iType,
            "is_required" : true,
            "default_value" : null,
            "help_text" :null,
            "validation" :false,
            "validation_rule" :null,
            "validation_rule" : $scope.cube.id
      };

      if(iType == "multiple" || iType == "check" || iType == "select"){
        newField.options = [{id:1,title:"Option 1", pos:"1"}, {id:2,title:"Option 2", pos:"2"}, {id:3,title:"Option 3", pos:"3"}];
      }

      $scope.cube.attributes.push(newField);

      $scope.saveCube();
    }


    $scope.updateValidation = function (field){

      if(field.validation.status == false){
        delete field.validation;
        field.validation = {status: false};
      }

    }


  //deletes particular field on button click
    $scope.deleteField = function (field){

      att_id = field.id;

      //alert(field_id); return;
        for(var i = 0; i < $scope.cube.attributes.length; i++){
            if($scope.cube.attributes[i].id == att_id){
                $scope.cube.attributes.splice(i, 1);
                break;
            }
        }

      $scope.saveCube();

    }



    // delete particular option
    $scope.deleteOption = function (field,option){
      //console.log(field);return;
        for(var i = 0; i < field.options.length; i++){
            if(field.options[i].id == option.id){
                field.options.splice(i, 1);
                break;
            }
        }
    }


    // add new option to the field
    $scope.addOption = function (field){

     console.log(field);
        if(!field.options)
            field.options = new Array();

        var lastOptionID = 0;

        if(field.options[field.options.length-1])
            lastOptionID = field.options[field.options.length-1].id;

        // new option's id
        var option_id = lastOptionID + 1;

        var newOption = {
            "id" : option_id,
            "title" : "Option " + option_id,
            "pos" : option_id
        };

        // put new option into field_options array
        field.options.push(newOption);
    }




});



app.directive('fieldDirective', function($http, $compile) {

   
    var getTemplateUrl = function(field) {
      return '/forms/design/'+field.type+'.html';
    }

    var linker = function(scope, element, attrs) {
      // GET template content from path

      var templateUrl = getTemplateUrl(scope.field);
      $http.get(templateUrl).success(function(data) {
            element.html(data);
            $compile(element.contents())(scope);
      });
    }

    return {
        restrict: "EA",
        scope: false,
        link: linker,
    };

});






app.directive('previewDirective', function($http, $compile) {
   
    var getTemplateUrl = function(field) {
      return '/forms/preview/'+field.type+'.html';
    }

    var linker = function(scope, element, attrs) {
      // GET template content from path

      var templateUrl = getTemplateUrl(scope.field);
      $http.get(templateUrl).success(function(data) {
            element.html(data);
            $compile(element.contents())(scope);
      });
    }

    return {
        restrict: "EA",
        scope: false,
        link: linker,
    };

});


function guid() {
  function s4() {
    return Math.floor((1 + Math.random()) * 0x10000)
      .toString(16)
      .substring(1);
  }
  return s4() + s4() + '-' + s4() + '-' + s4() + '-' + s4();
}
