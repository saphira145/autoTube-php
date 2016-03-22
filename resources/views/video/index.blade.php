@extends('master')
    
@section('content')
<div class="row">
    
    <div class="col-sm-9" style="border-right: 1px solid #ddd">
        <div class="row wrapper-table">
            <div class="col-xs-12" style="margin-bottom: 15px">
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <h3 class="no-margin"><i class="fa fa-tasks" style="font-size: 20px"></i> Manage Video</h3>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <button class="btn btn-main pull-right create-video-modal-button" data-toggle="modal" data-target="#create-video-modal">
                            <i class="fa fa-plus"></i> Create Video
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-xs-12">
                <table id="video-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <input type="hidden" id="last-time-opened" value="{{$lastTimeOpened}}">
    <div class="col-sm-3 log-process">
        <h3 class="no-margin" style="margin-bottom: 15px"><i class="fa fa-hourglass-1" style="font-size: 20px"></i> Processing data</h3>
        <div class="panel-body">
            
        </div>
    </div>
</div>
@endsection

@include('video.createModal')
@include('video.deleteModal')
@include('video.encodeModal')
@include('video.uploadModal')
    
<script type="x-tmpl-mustache" id="action-template">
    <div class="action-group">
        <a href="javascript:void(0)" class="encode-modal-video-button action"  id="<%id%>" data-toggle="modal" data-target="#encode-video-modal"><i class="fa fa-video-camera"></i> Encode</a>
        <a href="javascript:void(0)" class="upload-modal-video-button action"  id="<%id%>" data-toggle="modal" data-target="#upload-video-modal"><i class="fa fa-upload"></i> Upload</a>
        <a href="javascript:void(0)" class="delete-modal-video-button action" id="<%id%>" data-toggle="modal" data-target="#delete-video-modal"><i class="fa fa-trash"></i> Delete</a>
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

<script type="x-tmp-mustache" id="progress-item">
    <div class="progress" vid="<%video_id%>">
        <div class="progress-bar progress-bar-striped active" role="progressbar"
            aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:100%">
            <div class="<%type%> text"><%content%></div>
        </div>
    </div>
</script>

<script type="x-tmp-mustache" id="status-template">
    <div class="<%class%>-status status">
        <span class=""><%name%></span>
    </div>
</script>
