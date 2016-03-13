@extends('master')
    
@section('content')
    <div class="row wrapper-table">
        <div class="col-xs-12" style="margin-bottom: 15px">
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    <h3 class="no-margin">Manage Video</h3>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <button class="btn btn-main pull-right create-video-modal-button" data-toggle="modal" data-target="#create-video-modal">
                        Create Video
                    </button>
                </div>
            </div>
        </div>
        
        <div class="col-xs-12">
            <table id="video-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Video</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@include('video.createModal')
    
<script type="x-tmpl-mustache" id="action-template">
    <div>
        <a href="javascript:void(0)" class="edit-modal-video-button action"  id="<%id%>"><span class="glyphicon glyphicon-upload" aria-hidden="true"></span> Upload</a>
        <a href="javascript:void(0)" class="delete-modal-video-button action" id="<%id%>"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Delete</a>
    </div>
</script>

<script type="x-tmpl-mustache" id="image-item">
    <div class="item col-xs-3">
        <input type="hidden" name="imagesPath[]" value="<%filePath%>">
        
        <div class="upload-img" style="background-image: url(<%filePath%>)">
            <a class="action-image remove"href="javascript:void(0)" fileName="<%fileName%>"><i class="fa fa-times-circle"></i></a>
            <a class="action-image view" href="<%filePath%>" target="_blank"><i class="fa fa-info-circle"></i></a>
        </div>
    </div>
</script>

<script type="x-tmp-mustache" id="audio-item">
    <div class="col-xs-12">
        <div class="item">
            <input type="hidden" name="audioPath[]" value="<%filePath%>" >
            <audio controls style="width:90%">
                <source src="<%filePath%>" type="<%mime%>">
                Your browser does not support the audio element.
            </audio>
            <a href="javascript:void(0)" class="remove-audio" fileName="<%fileName%>"><i class="fa fa-trash"></i></a>
        </div>
    </div>
</script>
