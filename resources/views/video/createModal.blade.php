<!-- Modal -->
<div id="create-video-modal" tabindex='-1' class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Add video</h4>
		</div>
		<div class="modal-body" style="min-height:400px">
			<form class="form-horizontal create-form">
				
			</form>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
			<button type="button" class="btn btn-success" id="save"><i class="fa fa-floppy-o"></i> Save</button>
		</div>
    </div>

  </div>
</div>

<script type="x-tmpl-mustache" id="create-form-template">
	<div class="form-group">
		<label class="form-label col-xs-3 required">First name:</label>
		<div class="col-xs-9">
			<input type="text" class="form-control required auto-focus" name="first_name" id="first_name" >
			<p class="error-msg first_name-error"></p>
		</div>
	</div>

	<div class="form-group">
		<label class="form-label col-xs-3 required">Last name:</label>
		<div class="col-xs-9">
			<input type="text" class="form-control required" name="last_name" id="last_name" >
			<p class="error-msg last_name-error"></p>
		</div>
	</div>
	<div class="form-group">
		<label class="form-label col-xs-3 required">Email:</label>
		<div class="col-xs-9">
			<input type="text" class="form-control required" name="email" id="email" >
			<p class="error-msg email-error"></p>
		</div>
	</div>
	<div class="form-group">
		<label class="form-label col-xs-3 required">Birthday:</label>
		<div class="col-xs-9">
			<div class='input-group date birthday-group' id='birthday-group'>
                <input type='text' class="form-control" id="birthday" name="birthday" placeholder="YYYY/MM/DD"/>
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
            <p class="error-msg birthday-error"></p>
		</div>
	</div>
	<div class="form-group">
		<label class="form-label col-xs-3 required">Gender:</label>
		<div class="col-xs-9">
			<%#genderSelection%>
				<label class="radio-inline"><input type="radio" name="gender" value="<%id%>"><%text%></label>
			<%/genderSelection%>
			<p class="error-msg gender-error"></p>
		</div>
	</div>
	<div class="form-group">
		<label class="form-label col-xs-3 required">Photo:</label>
		<input name="image" type="hidden" id="image">
		<div class="col-xs-9">
			<div class="row">
				<div class="col-xs-5">
					<a href="javascript:void(0)"><div class="image-preview" style="background-image:url('/images/default.jpg')"></div></a>
				</div>
				<div class="col-xs-7">

					<div class="type-allow">Allow jpg, png, pdf scan</div>
					<button class="btn btn-success btn-upload"><i class="fa fa-upload"></i> Upload</button>
					<input class="hide" id="fileupload" type="file" name="files" data-url="/media/upload">
				</div>
			</div>
			<p class="error error-msg image-error"></p>
		</div>
	</div>
	<div class="form-group">
		<label class="form-label col-xs-3 required">Status:</label>
		<div class="col-xs-9">
			<select class="form-control status" id="status" name="status">
				<%#statusSelection%>
					<option value="<%id%>"><%text%></option>
				<%/statusSelection%>
			</select>
		</div>
	</div>
</script>