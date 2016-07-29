
<div ng-controller="cubeEditCtrl">


<input type="text" value="<?php echo $cube->id; ?>" id="cube_id">



<div class="row">
  <div class="col-md-2">

  <p><button class="btn btn-primary btn-block" ng-click="addItem('text')">Single text box</button></p>
  <p><button class="btn btn-primary btn-block" ng-click="addItem('textarea')">Multiline text box</button></p>
  <p><button class="btn btn-primary btn-block" ng-click="addItem('multiple')">Multiple choice</button></p>
  <p><button class="btn btn-primary btn-block" ng-click="addItem('check')">Checkbox</button></p>
  <p><button class="btn btn-primary btn-block" ng-click="addItem('select')">Dropdown list</button></p>
  <p><button class="btn btn-primary btn-block" ng-click="addItem('upload')">File upload</button></p>
  <p><button class="btn btn-primary btn-block" ng-click="addItem('content')">Content block</button></p>
  

</div>
  <div class="col-md-6">
<h3>Design</h3>
<hr>

      <div id="cube_details" data-toggle="modal" data-target="#myModal">
      <h1 id="titleLabel">{{cube.name}}</h1>
      <p id="descriptionLabel">{{cube.description}}</p>
      </div>

      <div class="well" ng-repeat="field in cube.attributes">
        <field-directive field="field"></field-directive>
      </div>
</div>
<div class="col-md-4">

<h3>Preview</h3>
<hr>
<code>{{cube}}</code>

<h3 id="titleLabel">{{cube.name}}</h3>
        <p id="descriptionLabel">{{cube.description}}</p>
        <hr>
        <div ng-repeat="field in cube.attributes">
        <preview-directive field="field"></preview-directive>
        </div>

</div>



</div>
















<!-- Project title and description -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">

        <div class="form-group">
          <label for="proejctTitle">Cube title</label>
          <input type="text" class="form-control" ng-model="cube.name" value="Hello, world!">
        </div>

        <div class="form-group">
          <label for="proejctDescription">Cube description</label>
          <textarea class="form-control" rows="3" ng-model="cube.description">Descriptiton</textarea>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal" ng-click="saveCube()">Done</button>
      </div>
    </div>
  </div>
</div>






</div>















</div>

	
</div>
