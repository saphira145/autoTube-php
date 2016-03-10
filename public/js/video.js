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
    
    var formFacility = $(".form-facility");
    var body = $('body');

    body.on('click', '.form-facility .save-new', saveFacility);
    body.on('click', '#facility-table .delete-facility', showDeleteFacility);
    body.on('click', '#delete-modal .delete-facility-modal', deleteFacility);
    body.on('click', '.form-facility .save-update', updateFacility);
    
    function saveFacility() {
        var data = formFacility.find('.add-facility-form').serialize();
        var url = '/facility/saveFacility';

        $.ajax({
            url: url,
            data: data,
            type: 'POST',
            success: function(response) {
                if (response.status === 1 && response.hasOwnProperty('redirectUrl')) {
                    window.location.replace(response.redirectUrl);
                }

                if (response.status === 0) {
                    var errors = response.errors;

                    formFacility.find('.error-msg').text('');
                    $.each(errors, function(key, error) {
                        var selectorError = formFacility.find('.' + key + '-error');
                        selectorError.text(error[0].message);
                    })
                }
            }
        })
    }


    function showDeleteFacility() {
        var id = $(this).attr("rid");
        console.log(id)
        $("#delete-modal").attr('rid', id);
    }

    function deleteFacility() {
        var id = $("#delete-modal").attr('rid');
        
        $.ajax({
            url : '/facility/'+ id +'/destroy',
            type: 'DELETE',
            
            success: function(response) {
                if (response.status === 1) {
                    FacilityTable.ajax.reload(null, false);
                    displayMessage(1, 'Facility is deleted successfully');
                }
                if (response.status === 0) {
                    displayMessage(0, 'Something went wrong');
                }
            }
        })

    }

    function updateFacility() {
        
        var id = $(this).attr('rid');
        var url = '/facility/'+ id +'/update';
        var data = formFacility.find('.edit-facility-form').serialize();
        
        $.ajax({
            url: url,
            type: 'PUT',
            data: data,
            success: function(response) {
                if (response.status === 1) {
                    window.location.replace(response.redirectUrl);
                }

                if (response.status === 0) {
                    if (response.hasOwnProperty('errors')) {
                        $(".error-msg").text('');
                        $.each(response.errors, function(key, error) {
                            var selectorError = $('.' + key + '-error');
                            selectorError.text(error[0].message);
                        });
                    }
                }
            }
        })
    }

})()