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


$('#users_datatable').exists(function() {
  $('#users_datatable').DataTable({
      "aoColumnDefs": [
          { 'bSortable': false, 'aTargets': [ 6 ] }
       ]
});
});


$('#users_datatable').on("click", ".confirm", function (e) {

e.preventDefault();
var $link = $(this);
bootbox.confirm("Are you Sure want to delete!", function (confirmation) {
    confirmation && document.location.assign($link.attr('href'));
});

});

$('#group_datatable').exists(function() {
  $('#group_datatable').DataTable({
      "aoColumnDefs": [
          { 'bSortable': false, 'aTargets': [ 2 ] }
       ]
});
});


$('#group_datatable').on("click", ".confirm", function (e) {

e.preventDefault();
var $link = $(this);
bootbox.confirm("Are you Sure want to delete!", function (confirmation) {
    confirmation && document.location.assign($link.attr('href'));
});

});






var t = $('#fileGrid').DataTable({
	"order": [[ 1, "desc" ]],
  "sFilter": "dataTables_filter form-control-sm",
  "aoColumnDefs": [
          { 'bSortable': false, 'aTargets': [ 3] }
       ]
});



$('#fileGrid').on("click", ".confirm", function (e) {

e.preventDefault();
var $link = $(this);
bootbox.confirm("Are you Sure want to delete? Please note if you delete any folder it will delete all sub files and folders within that folder.", function (confirmation) {
    confirmation && document.location.assign($link.attr('href'));
});

});
// $.extend($.fn.dataTableExt.oStdClasses, {
//     "sFilterInput": "form-control-sm",
//     "sLengthSelect": "form-control yourClass"
// });


// $('.input-sm').addClass('form-control-sm');
// $('.pagination').addClass('pagination-sm');



var app = angular.module('fileManager', ['ngFileUpload']);

app.controller('appCtrl', ['$scope', 'Upload', '$http', '$timeout',function ($scope, Upload ,$http, $timeout) {

	$scope.myVar = false;

	$scope.toggleFolderInput = function() {
    $scope.myVar = !$scope.myVar;
  };

  $scope.token = document.getElementsByName("csrf_kir_token")[0].value;

  $scope.dir_id = document.getElementsByName("dir_id")[0].value;


  $scope.addFolder = function() {

    //alert($scope.folderName);

    //


    $http({
        method: 'POST',
        url: '/docs/api/create_folder',
        data: $.param({csrf_kir_token: $scope.token , folder_name: angular.copy($scope.folderName), parent_folder_id: angular.copy($scope.dir_id)  }),
        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
    }).then(function(response) {
       console.log(response);

       $scope.folderName = "";

       if(response.data.status == 0){
       }

    });














  }


  

	$scope.uploadFiles = function(files, errFiles) {
        $scope.files = files;
        $scope.errFiles = errFiles;
        angular.forEach(files, function(file) {


			

			file.upload = Upload.upload({
                url: 'docs/upload',
                data: {csrf_kir_token: $scope.token, dir_id : $scope.dir_id,  file: file}
            });



            // file.upload = Upload.upload({
            //     url: 'https://angular-file-upload-cors-srv.appspot.com/upload',
            //     data: {file: file}
            // });

            file.upload.then(function (response) {
                $timeout(function () {


				//Add row to datatable
				// t.row.add( [ '<i class="fa fa-file fa-lg" aria-hidden="true"></i> '+ file.name ,
    //       get_time(),'file','action'] ).draw(false);


       // t.fnSortListener( document.getElementById('sorter'), 1 );


       location.reload();


			console.log(t);




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


var grpapp = angular.module('userGroup', []);



grpapp.controller('appCtrl', function($scope) {


    //$scope.permissions = null;

    $scope.folders = [];





     $scope.setPermission = function (pid){

      var fid = $('#fid').text();
      var folder = {"id" : fid};
      var can_add = true;

      for (var i = 0; i < $scope.folders.length; i++) {
        if ($scope.folders[i].id === fid) {
          can_add = false;
          break;
        }
      }

      if(can_add){
        $scope.folders.push(folder);
      }
     




      //var tt = fid+'_'+pid;
      //if(!$.inArray(tt, $scope.permissions)){
      
      //}
      

      

    }


    $scope.pushIfNew =  function(id) {

      console.log(id);
      for (var i = 0; i < array.length; i++) {
        if (array[i].id === id) {
          return false;
        }
      }
      return true;
    }



    
});







$('#jstree_demo_div').jstree();

$('#jstree_demo_div').on("changed.jstree", function (e, data) {
    
    //console.log(data.selected[0]);
   
   $('#fid').text(data.selected[0]);

   // $.get( "/docs/api/get_folder_permission", { fid: data.selected[0] }, function( status ) {
   //  // $( ".result" ).html( data );
   //  // alert( "Load was performed." );

       //console.log($scope.permissions);



   //  });

    $('#permissionWindow').modal();


});
















function get_time(){
 now = new Date();
  year = "" + now.getFullYear();
  month = "" + (now.getMonth() + 1); if (month.length == 1) { month = "0" + month; }
  day = "" + now.getDate(); if (day.length == 1) { day = "0" + day; }
  hour = "" + now.getHours(); if (hour.length == 1) { hour = "0" + hour; }
  minute = "" + now.getMinutes(); if (minute.length == 1) { minute = "0" + minute; }
  second = "" + now.getSeconds(); if (second.length == 1) { second = "0" + second; }
  return year + "-" + month + "-" + day + " " + hour + ":" + minute + ":" + second;
}
