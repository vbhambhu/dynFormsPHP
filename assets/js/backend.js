
//Anguar app
var app = angular.module('dynForm', []);


app.controller('cubeCreateCtrl', function($scope) {

    $scope.project = {title:"Cube title", description:"Cube description"};
    $scope.project.fields = [];

    $scope.addField = {};
    $scope.addField.lastAddedID = 0;

    $scope.addItem = function (iType) {

      $scope.addField.lastAddedID++;

      var newField = {
            "id" : $scope.addField.lastAddedID,
            "title" : "New field - " + $scope.addField.lastAddedID,
            "type" : iType,
            "value" : "",
            "required" : true
      };

      if(iType == "multiple" || iType == "check" || iType == "select"){
        newField.options = [{id:1,title:"Option 1", pos:"1"}, {id:2,title:"Option 2", pos:"2"}, {id:3,title:"Option 3", pos:"3"}];
      }

      $scope.project.fields.push(newField);
    }


    $scope.updateValidation = function (field){

      if(field.validation.status == false){
        delete field.validation;
        field.validation = {status: false};
      }

    }


  //deletes particular field on button click
    $scope.deleteField = function (field){

      field_id = field.id;

      //alert(field_id); return;
        for(var i = 0; i < $scope.project.fields.length; i++){
            if($scope.project.fields[i].id == field_id){
                $scope.project.fields.splice(i, 1);
                break;
            }
        }
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