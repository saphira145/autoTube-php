@extends('master')
    
@section('content')
    <div class="row wrapper-table">
        <div class="col-xs-12" style="margin-bottom: 15px">
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    <h3 class="no-margin">Manage Video</h3>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <button class="btn btn-main pull-right create-modal-video-button">Create Video</button>
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

<script type="x-tmpl-mustache" id="action-template">
    <div class="status-label">
        <span class="label label-default">Default</span>
    </div>
</script>
