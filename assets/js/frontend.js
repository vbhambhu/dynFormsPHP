$.fn.exists = function(callback) {
  var args = [].slice.call(arguments, 1);

  if (this.length) {
    callback.call(this, args);
  }
  return this;
};



$('[data-toggle="tooltip"]').exists(function() {
  $('[data-toggle="tooltip"]').tooltip()
});

$('.datatable').exists(function() {
  $(".datatable").DataTable();
});

$('.selectpicker').exists(function() {
$(".selectpicker").select2();
});

$( ".confirm" ).click(function(e) {
  e.preventDefault();
  var $link = $(this);
  bootbox.confirm( $link.data('msg'), function (confirmation) {
      confirmation && document.location.assign($link.attr('href'));
  });
});


//Anguar app
var app = angular.module('fileManager', ['ngFileUpload']);

app.controller('appCtrl', ['$scope', 'Upload', '$http', '$timeout',function ($scope, Upload ,$http, $timeout) {

  $scope.token = document.getElementsByName("csrf_kir_token")[0].value;
  $scope.dir_id = document.getElementsByName("dir_id")[0].value;


  $scope.uploadFiles = function(files, errFiles) {

      $('#fileUploader').modal();
        $scope.files = files;
        $scope.errFiles = errFiles;
        angular.forEach(files, function(file) {

          file.upload = Upload.upload({
              url: 'docs/upload',
              data: {csrf_kir_token: $scope.token, dir_id : $scope.dir_id,  file: file}
          });

          file.upload.then(function (response) {
                $timeout(function () {
                  console.log(response);
                  //location.reload();
                    file.result = response.data;
                });
            }, function (response) {
                if (response.status > 0)
                    $scope.errorMsg = response.status + ': ' + response.data;
            }, function (evt) {
                file.progress = Math.min(100, parseInt(100.0 * 
                                         evt.loaded / evt.total));
            });
        });
    }
}]);