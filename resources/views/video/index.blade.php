@extends('master')

@section('content')
    <div class="row wrapper-table">
        <div class="col-xs-12">
            <button class="btn btn-main create-modal-video-button">Create Video</button>
        </div>

        <div class="col-xs-12">
            <table id="video-table" class="stripe mt-10 dataTable no-footer" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Video</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@include('video.createModal')

@section('scripts')
    
@endsection
