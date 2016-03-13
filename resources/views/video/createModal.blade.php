<!-- Modal -->
<div id="create-video-modal" tabindex='-1' class="modal fade" role="dialog">
  <div class="modal-dialog" style="width:80%">

    <!-- Modal content-->
    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Add video</h4>
		</div>
		<div class="modal-body" style="min-height:400px">
			<form class="form create-form">
				
			</form>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
			<button type="button" class="btn btn-main" id="save-video"><i class="fa fa-floppy-o"></i> Save</button>
		</div>
    </div>

  </div>
</div>

<script type="x-tmpl-mustache" id="create-form-template">
	 
    <div class="row">
        <div class="col-xs-8">
            <div class="form-group">
                <label class="control-label">Title</label>
                <input class="form-control" name="title" id="title" placeholder="Post title">
                <p class="error-msg error-title"></p>
            </div>
        </div>

        <div class="col-xs-4">
            <div class="form-group">
                <label class="control-label">Loop</label>
                <select class="form-control" name="loop" id="loop">
                    <option>1 Second</option>
                    <option>2 Second</option>
                </select>
                <p class="error-msg error-loop"></p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-8">
            <div class="form-group">
                <label class="control-label">Description</label>
                <textarea style="height:100px" placeholder="Post Description" class="form-control" name="description" id="description"></textarea>
                <p class="error-msg error-description"></p>
            </div>
        </div>

        <div class="col-xs-4">
            <div class="form-group">
                <label class="control-label">Fade</label>
                <select class="form-control" name="fade" id="fade">
                    <option>1 second</option>
                    <option>2 second</option>
                </select>
                <p class="error-msg error-fade"></p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-danger video-panel">
            <!-- Default panel contents -->
            <div class="panel-heading"><i class="fa fa-cloud-upload"></i> Upload zone</div>
            <div class="panel-body">
                <div class="col-xs-8">
                    <input type="file" class="hidden images-upload" accept="image/*" name="files[]" data-url="/media/upload" multiple>
                    <div class="form-group">
                        <button type="button" class="btn btn-main btn-upload-images"><i class="fa fa-picture-o"></i> Upload</button>
                        <p class="error-msg error-imagesPath"></p>
                    </div>  
                    
                    <div class="upload-image-zone upload-zone form-group col-xs-12">
                        <div class="row">
                            <div class='no-data'>No data</div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-4">
                    <input type="file" name="files[]" class="hidden audio-upload" accept="audio/mp3" data-url="/media/upload" multiple>
                    <div class="form-group">
                        <button type="button" class="btn btn-main btn-upload-audio"><i class="fa fa-music"></i> Upload</button>
                        <p class="error-msg error-audioPath"></p>
                    </div>
                    <div class="upload-audio-zone upload-zone form-group col-xs-12">
                        <div class="row">
                            <div class='no-data'>No data</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  
</script>