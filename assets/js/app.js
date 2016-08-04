$.fn.exists = function(callback) {
  var args = [].slice.call(arguments, 1);

  if (this.length) {
    callback.call(this, args);
  }
  return this;
};

$('#datatabssle').exists(function() {
//var controller = $(this).attr("src");

var nosort = $(this).attr("nosort").split("-");
var def = new Array;
for (i = 0; i < nosort.length; i++) { 
  var jsn = { orderable: false, targets: parseInt(nosort[i]) };
    def.push(jsn);
}

$('#datatable').dataTable({
"processing": true,
"serverSide": true,
"ajax": "/form/datatable",
//"columnDefs": def
//"columnDefs": [{ orderable: false, targets: -1 }]
});
});




//Anguar app
var app = angular.module('dynForm', []);

app.controller('formEditCtrl', function($scope,$http) {

  var fromId = document.getElementById('form_id').value;

    $scope.form = {};

    $http.get('/api/form/searchById', 
    { params: { id : fromId }}).then(function successCallback(response) {

    console.log(response);

      $scope.form.id = response.data.id;
      $scope.form.name = response.data.name;
      $scope.form.description = response.data.description;
      $scope.form.fields = response.data.fields;
      $scope.lastAddedID = response.data.fields.length;
      

      $scope.createValidationArray();

    }, function errorCallback(response) {
    // called asynchronously if an error occurs
    // or server returns response with an error status.
    });


    $scope.createValidationArray = function () {


     // if(!field.validation_array)

      for(var i = 0; i < $scope.form.fields.length; i++){

          var validation_array = new Array();
          var rule = $scope.form.fields[i].validation_rule;
          var ruleArr = rule.split("|");

          $scope.form.fields[i].validation = [];

          for(key in ruleArr){
             if(ruleArr[key].includes("[")){
              
              var key1 = ruleArr[key].substring(0,ruleArr[key].lastIndexOf("["));
              var val = ruleArr[key].substring(ruleArr[key].lastIndexOf("[")+1,ruleArr[key].lastIndexOf("]"));
              validation_array.push({"rule": key1, 'value': val});
              $scope.form.fields[i].validation[key1] = true;

              //console.log(ruleArr[key].lastIndexOf("["));
                
             } else {
                if(ruleArr[key] != "required" ){
                  validation_array.push({"rule": ruleArr[key]});
                  var tt = ruleArr[key];
                   $scope.form.fields[i].validation[tt] = true;
                }
                
             }
          }

          console.log(validation_array);


          //$scope.form.fields[i].validation = {};
          //$scope.form.validation = ruleArr[key];


          $scope.form.fields[i].validation_array = validation_array;

         
      }


    }


    $scope.saveForm = function () {

      console.log(angular.copy($scope.form));

      $http({
        method: 'POST',
        url: '/api/form/save',
        data: $.param({form: angular.copy($scope.form) }),
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
            "validation_rule" : $scope.form.id
      };

      if(iType == "multiple" || iType == "check" || iType == "select"){
        newField.options = [{id:1,title:"Option 1", pos:"1"}, {id:2,title:"Option 2", pos:"2"}, {id:3,title:"Option 3", pos:"3"}];
      }

      $scope.form.fields.push(newField);

      $scope.saveForm();
    }


    // $scope.updateValidation = function (field){

    //   if(field.validation.status == false){
    //     delete field.validation;
    //     field.validation = {status: false};
    //   }

    // }


  //deletes particular field on button click
    $scope.deleteField = function (field){

      att_id = field.id;

      //alert(field_id); return;
        for(var i = 0; i < $scope.form.fields.length; i++){
            if($scope.form.fields[i].id == att_id){
                $scope.form.fields.splice(i, 1);
                break;
            }
        }

      $scope.saveForm();

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

    $scope.updateValidation = function (field){

       if(!field.validation_array)
            field.validation_array = new Array();

      if("min_length" in field.validation){

         var newField = {
            "rule" : "min_length",
            "value" : 50
        };

        if(field.validation.min_length){
          field.validation_array.push(newField);
        } else{

          //delete field.validation["min_length"];
          //field.validation_array.splice(i, 1);
        }

        
      }
      console.log(field.validation);
      

       for(var i = 0; i < field.validation.length; i++){

        console.log(field.validation[i]);
            
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
