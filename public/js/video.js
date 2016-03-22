'use strict'

var VideoTable = $('#video-table').DataTable({
	serverSide: true,
	ajax: {
		url: '/video/getVideoList',
		type: 'POST'
	},
	columns : [
        {data : 'title'},
        {data : 'status_detail'},
        {data : 'id'}
    ],
    columnDefs : [
        {
            targets: 1,
            render: function(status_detail) {
                var delimiter = "{{=<% %>=}}";
                var template = $("#status-template").html();
                var html = Mustache.render(delimiter + template, status_detail);
                return html;
            }
        },
    	{
            targets: -1,
            orderable: false,
            width: '225px',
            render: function(id) {
                
                var delimiter = "{{=<% %>=}}";
                var template = $("#action-template").html();
                var html = Mustache.render(delimiter + template, {id : id});
                return html;
            }
    	},
        {
            targets: -2,
            width: '100px'
        }
    ]
});

var Video = (function() {
    
    var wrapperTable = $(".wrapper-table");
    var body = $('body');
    var delimter = "{{=<% %>=}}";
    var createModal = $("#create-video-modal");
    var deleteModal = $("#delete-video-modal");
    var encodeModal = $("#encode-video-modal");
    var uploadModal = $("#upload-video-modal");
    
    var videoLogArray = [];
    
    body.on('click', '.wrapper-table .create-video-modal-button', createVideo);
    body.on('click', '#create-video-modal .btn-upload-images', uploadImage);
    body.on('click', '#create-video-modal .btn-upload-audio', uploadAudio);
    body.on('click', '.remove', removeImage);
    body.on('click', '.item .remove-audio', removeAudio);
    body.on('click', '#create-video-modal #save-video', saveVideo);
    body.on('click', '#create-video-modal .dailog-audio', addYoutubeLink);
   
    body.on('click', '.wrapper-table .delete-modal-video-button', deleteVideoModal);
    body.on('click', '#delete-video-modal .delete-video', deleteVideo)
    
    body.on('click', '.wrapper-table .encode-modal-video-button', encodeVideoModal);
    body.on('click', '#encode-video-modal .encode-video', encodeVideo);
    
    body.on('click', '.wrapper-table .upload-modal-video-button', uploadVideoModal);
    body.on('click', '#upload-video-modal .upload-video', uploadVideo);
    
    
    function uploadImage() {
        $(".images-upload").click();
    }
    
    function uploadAudio() {
        $(".audio-upload").click();
    }
    
    
    function createVideo() {
        var html = $("#create-form-template").html();
        var template = Mustache.render(html);
        var form = createModal.find(".create-form");
        form.html(template);
        
        bindImagesUpload();
        bindAudioUpload();
    }
    
    
    function bindImagesUpload() {
        $(".images-upload").fileupload({
            dataType: 'json',
            done: function (e, data) {
                var res = data.result;
                
                if (res.status === 1) {
                    var html = $("#image-item").html();
                    var template = Mustache.render(delimter + html, res.fileInfo);
                    var container = $(".upload-image-zone").find(".row");
                    container.append(template);
                    
                    checkNoData(container, $(".item"));
                }
                
                if (res.status === 0) {
                    
                }
            },
            progressall: function (e, data) {
                
            }
        });
    }
    
    function bindAudioUpload() {
        $(".audio-upload").fileupload({
            dataType: 'json',
            done: function (e, data) {
                var res = data.result;
                
                if (res.status === 1) {
                    var html = $("#audio-item").html();
                    var template = Mustache.render(delimter + html, res.fileInfo);
                    
                    var container = $(".upload-audio-zone").find(".row");
                    container.append(template);
                    
                    checkNoData(container, $(".item"));
                }
                
                if (res.status === 0) {
                    
                }
            },
            progressall: function (e, data) {
                
            }
        });
    }
    
    function removeImage() {
        var fileName = $(this).attr('fileName');
        var self = $(this);
        var container = $(".upload-image-zone").find(".row");
        $.ajax({
            url: '/media/remove',
            type: 'POST',
            data: {fileName: fileName, type : 'image'},
            beforeSend: function() {
                
            },
            success: function(res) {
                if (res.status === 1) {
                    
                    self.closest('.item').remove();
                    checkNoData(container, $(".item"));
                }
                
                if (res.status === 0) {
                    
                }
            }
            
        })
    }
    
    function checkNoData(container, item) {
        if ( container.find(item).length == 0 ) {
            container.empty().append("<div class='no-data'>No data</div>");
        } else {
            container.find('.no-data').remove();
        }
    }
    
    function removeAudio() {
        var fileName = $(this).attr("fileName");
        var self = $(this);
        var container = $(".upload-audio-zone").find(".row");
        
        $.ajax({
            url: '/media/remove',
            type: 'POST',
            data: {fileName: fileName, type : 'audio'},
            beforeSend: function() {
                
            },
            success: function(res) {
                if (res.status === 1) {
                    
                    self.closest('.item').remove();
                    checkNoData(container, $(".item"));
                }
            }
            
        })
    }
    
    function saveVideo() {
        var form = createModal.find(".create-form");
        
        $.ajax({
            url: '/video/saveVideo',
            type: 'POST',
            data: form.serialize(),
            beforeSend: function() {
                createModal.find('.modal-content').addClass('ajax-load');
            },
            success: function(res) {
                form.find(".error-msg").text('');
                form.find(".has-error").removeClass('has-error');
                
                if (res.status === 1) {
                    createModal.modal('hide');
                    VideoTable.ajax.reload(null, false);
                }
                
                if (res.status === 0) {
                    if (res.hasOwnProperty('errors')) {
                        var errors = res.errors;
                        for (var key in errors) {
                            
                            var ele = form.find(".error-" + key);
                            ele.text(errors[key][0]);
                            $("#" + key).closest(".form-group").addClass('has-error');
                        }
                        _focusErrorInput(form);
                    }
                }
            },
            
            complete: function() {
                createModal.find('.modal-content').removeClass('ajax-load');
            }
        })
    }
    
    function encodeVideoModal() {
        var id = $(this).attr('id');
        
        encodeModal.attr("vid", id);
    }
    
    function encodeVideo() {
        var id = encodeModal.attr("vid");
        var actionGroup = $(".action[id="+ id +"]").closest('.action-group');
        
        $.ajax({
            url: '/video/encode',
            type: "GET",
            data: {id: id},
            beforeSend: function() {
                actionGroup.addClass('ajax-dot-load');
                actionGroup.find('.action').css('visibility', 'hidden');
                encodeModal.modal('hide');
            },
            success: function(res) {
                VideoTable.ajax.reload(null, false);
                
            },
            complete: function() {
                actionGroup.removeClass('ajax-dot-load');
                actionGroup.find('.action').css('visibility', 'visible');
            }
        })
    }
    function uploadVideoModal() {
        var id = $(this).attr('id');
        
        uploadModal.attr("vid", id);
    }
    
    function uploadVideo() {
        var id = uploadModal.attr('vid');
        var actionGroup = $(".action[id="+ id +"]").closest('.action-group');
        $.ajax({
            url: '/video/upload',
            type: 'GET',
            data: {
                id: id
            },
            beforeSend: function() {
                actionGroup.addClass('ajax-dot-load');
                actionGroup.find('.action').css('visibility', 'hidden');
                uploadModal.modal('hide');
            },
            success: function(res) {
                VideoTable.ajax.reload(null, false);
            },
            complete: function() {
                actionGroup.removeClass('ajax-dot-load');
                actionGroup.find('.action').css('visibility', 'visible');
            },
            error: function() {
                
            }
        })
    }
    
    function _focusErrorInput(form) {
        form.find(".has-error").find("input, text-area").each(function () {
            $(this).focus();
            return false;
        })
    }
    
    function addYoutubeLink() {
        
        $("#dialog").dialog({
            autoOpen: false,
            appendTo: ".upload-audio-zone"
        });
        
        $("#dialog").dialog('open');
        
        $("#extract-youtube").ajaxForm({
            beforeSend: function() {
                createModal.find('.modal-content').addClass('ajax-load');
                $("#dialog").dialog('close');
            },

            success: function(res) {
                if (res.status === 1) {
                    var html = $("#audio-item").html();
                    var template = Mustache.render(delimter + html, res.fileInfo);
                    
                    var container = $(".upload-audio-zone").find(".row");
                    container.append(template);
                    
                    checkNoData(container, $(".item"));
                }
                
                if (res.status === 0) {
                    
                }
            },
            complete: function() {
                createModal.find('.modal-content').removeClass('ajax-load');
            },
            error: function() {
                
            }
        })
    }   
    
    function getNotification(lastTimeOpened) {
        var logProcess = $(".log-process");
        
        $.ajax({
            url: '/log/getLogs',
            type: 'POST',
            data: {
                lastTimeOpened: lastTimeOpened
            },
            success: function(res) {
                if (res.status === 1) {
                    var logs = res.logs;
                    for (var index in logs) {
//                        console.log(videoLogArray.indexOf(logs[index].video_id))
                        if ( videoLogArray.indexOf(logs[index].video_id) == -1) {
                            var html = $("#progress-item").html();
                            var template = Mustache.render(delimter + html, logs[index]);
                            
                            logProcess.find('.panel-body').append(template);
                            videoLogArray.push(logs[index].video_id);
                        } else {
                            $(".progress[vid="+ logs[index].video_id +"]")
                                    .find('.progress-bar').addClass('done');
                            $(".progress[vid="+ logs[index].video_id +"]").find(".text").text(logs[index].content);
                        }
                    }
                    getNotification(res.newLastTimeOpened);
                }
            }
        })
    }
    function deleteVideoModal() {
        var id = $(this).attr('id');

        deleteModal.attr("vid", id);
    }
    
    function deleteVideo() {
        var id = deleteModal.attr('vid');
        $.ajax({
            url: '/video/'+ id +'/remove',
            type: 'Get',
            beforeSend: function() {
                deleteModal.find('.modal-content').addClass('ajax-load');
            },
            success: function(res) {
                if (res.status === 1) {
                    VideoTable.ajax.reload(null, false);
                    deleteModal.modal('hide');
                }
                if (res.status === 0) {
                    deleteModal.modal('hide');
                }
             },
            complete: function() {
                deleteModal.find('.modal-content').removeClass('ajax-load');
            },
            error: function() {
                alert('Server Error');
            }
        })
    }
    
    
    
    return {
        getNotification : getNotification
    };
    
})();


$(document).ready(function() {
    
    var lastTimeOpened = $("#last-time-opened");
    
    Video.getNotification(lastTimeOpened.val());
    
});