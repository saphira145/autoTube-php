'use strict'

var VideoTable = $('#video-table').DataTable({
	serverSide: true,
	ajax: {
		url: '/video/getVideoList',
		type: 'POST'
	},
	columns : [
        {data : 'title'},
        {data : 'store_at'},
        {data : 'status'},
        {data : 'id'}
    ],
    columnDefs : [
    	{
    		targets: -1,
    		orderable: false,
            width: '150px',
    		render: function(id) {
    			var template = $("#action-template").html();
                var html = Mustache.render(template, id);
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
    
    body.on('click', '.wrapper-table .create-video-modal-button', createVideo);
    body.on('click', '#create-video-modal .btn-upload-images', uploadImage);
    body.on('click', '#create-video-modal .btn-upload-audio', uploadAudio);
    body.on('click', '.remove', removeImage);
    body.on('click', '.item .remove-audio', removeAudio);
    body.on('click', '#create-video-modal #save-video', saveVideo);
    body.on('click', '#create-video-modal .dailog-audio', addYoutubeLink);
    
    
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
                    console.log(template)
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
                createModal.find('.moda-content').addClass('ajax-load');
            },
            success: function(res) {
                form.find(".error-msg").text('');
                form.find(".has-error").removeClass('has-error');
                
                if (res.status === 1) {
                    
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
                createModal.find('.moda-content').removeClass('ajax-load');
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
                console.log('bef');
            },

            success: function(res) {

            },
            complete: function() {

            },
            error: function() {

            }
        })
    }   
    
})();


$(document).ready(function() {
    
    
});