
<div ng-controller="cubeCreateCtrl">



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
  <div class="col-md-5">
<h3>Design</h3>
<hr>

      <div id="project_details" data-toggle="modal" data-target="#myModal">
      <h1 id="titleLabel">{{project.title}}</h1>
      <p id="descriptionLabel">{{project.description}}</p>
      </div>

      <div class="well" ng-repeat="field in project.fields">
        <field-directive field="field"></field-directive>
      </div>
</div>
<div class="col-md-5">

<h3>Preview</h3>
<hr>
<code>{{project}}</code>

<h3 id="titleLabel">{{project.title}}</h3>
        <p id="descriptionLabel">{{project.description}}</p>
        <hr>
        <div ng-repeat="field in project.fields">
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
          <label for="proejctTitle">Project title</label>
          <input type="text" class="form-control" ng-model="project.title" value="Hello, world!">
        </div>

        <div class="form-group">
          <label for="proejctDescription">Project description</label>
          <textarea class="form-control" rows="3" ng-model="project.description">Descriptiton</textarea>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">Done</button>
      </div>
    </div>
  </div>
</div>






</div>















</div>

	
</div>
