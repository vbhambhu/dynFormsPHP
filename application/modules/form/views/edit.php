
<div ng-controller="formEditCtrl">
{{form}}

<input type="hidden" value="<?php echo $form->id; ?>" id="form_id">



<div class="row">
  <div class="col-md-2">

  <p><button class="btn btn-primary btn-block" ng-click="addItem('text')">Single text box</button></p>
  <p><button class="btn btn-primary btn-block" ng-click="addItem('textarea')">Multiline text box</button></p>
  <p><button class="btn btn-primary btn-block" ng-click="addItem('multiple')">Multiple choice</button></p>
  <p><button class="btn btn-primary btn-block" ng-click="addItem('check')">Checkbox</button></p>
  <p><button class="btn btn-primary btn-block" ng-click="addItem('select')">Dropdown list</button></p>
  <p><button class="btn btn-primary btn-block" ng-click="addItem('upload')">File upload</button></p>
  <p><button class="btn btn-primary btn-block" ng-click="addItem('content')">Content block</button></p>
  <p><button class="btn btn-primary btn-block" ng-click="addItem('content')">Cube relationship</button></p>
  

</div>
  <div class="col-md-6">
<h3>Design</h3>
<hr>

      <div id="form_details" data-toggle="modal" data-target="#myModal">
      <h1 id="titleLabel">{{form.name}}</h1>
      <p id="descriptionLabel">{{form.description}}</p>
      </div>

      <div class="well" ng-repeat="field in form.fields">
        <field-directive field="field"></field-directive>
      </div>
</div>
<div class="col-md-4">

<h3>Preview</h3>
<hr>


<h3 id="titleLabel">{{form.name}}</h3>
        <p id="descriptionLabel">{{form.description}}</p>
        <hr>
        <div ng-repeat="field in form.fields">
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
          <label for="proejctTitle">From title</label>
          <input type="text" class="form-control" ng-model="form.name">
        </div>

        <div class="form-group">
          <label for="proejctDescription">Cube description</label>
          <textarea class="form-control" rows="3" ng-model="form.description">Descriptiton</textarea>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal" ng-click="saveForm()">Done</button>
      </div>
    </div>
  </div>
</div>






</div>















</div>

	
</div>
