jQuery( function ( $ ){
	"use strict";
	
	var emallshop_import_percent 			= 0,
        emallshop_import_percent_increase 	= 0,
        emallshop_import_index_request 		= 0,
        emallshop_import_request_data 		= [],
        emallshop_import_demo_name 			= '';
	
	
	/* Import Demo*/
	$(document).on('click', '.emallshop-import-data .theme', function(e) {
		var content_wrp = $(this);
		var template_part = $('#emallshop-popup-content');
		content_wrp.find('.theme-screenshot').addClass('loading');
		var template                = wp.template('emallshop-popup-data');
		var demo_name,demo_deails,modalcontainer;
		demo_name = $(this).attr('data-name');
		emallshop_import_demo_name = $(this).attr('data-name');
		modalcontainer = $(this).closest('.emallshop-import-demo-popup');
		
		var data = {
						action	: 'get_demo_data',
						demo   	: demo_name
					};
					
		$.post(ajaxurl,data,function(response) {
			
			var data = $.parseJSON(response);
			
			if( !data.status){
				alert(data.message);
				content_wrp.find('.theme-screenshot').removeClass('loading');
				return;
			}
			
			template_part.append( template({
				title : data.title,
				demo_key : data.slug,
				preview_image : data.preview_image,
				preview_demo_link : data.preview_demo_link,
			}));
			$.magnificPopup.open({
				items			: {
					src	: '.emallshop-import-demo-popup'
				},
				type			: 'inline',
				mainClass		: 'mfp-with-zoom',
				closeOnBgClick	: false,
				enableEscapeKey	: false,
				zoom			: {
					enabled	: true,
					duration: 300
				},
				callbacks		: {
					open	: function () {
						content_wrp.find('.theme-screenshot').removeClass('loading');
					},	
					close	:function(){
						template_part.html('');
					}
				},
			});
		});	
	});
	
	/* Process to import*/
	$(document).on('click', '.install-demo', function(e) {
		var import_btn = $(this);
		if (import_btn.hasClass('processing')) {
			return false;
		}
		if (import_btn.hasClass('disabled')) {
			return false;
		}
		if (import_btn.hasClass('import-completed')) {
			return false;
		}
		
		var c = confirm('Are you sure you want to import this demo?');
		if (!c) {
			return false;
		}
		
		import_btn.addClass('processing');
		import_btn.addClass('loading');
		$('.install-demo.processing').text('Importing...');
		$('.progress-percent').html('1%');
		$('.progress-bar').css('width','1%');
		$('.import-process').show();
		emallshop_import_request_data = [],
		emallshop_import_demo_name = $(this).attr('data-demo');
		
		var import_full_content = false,
		import_content 			= false,
		import_menu 			= false,
		import_widget 			= false,
		import_revslider 		= false,
		import_theme_options 	= false,
		import_attachments 		= false;
		var demo_name 			= emallshop_import_demo_name;
		
        if ($('#import_content_' + demo_name).is(':checked')) {
            import_content = true;
        } else {
            import_content = false;
        }
		if ($('#import_widget_' + demo_name).is(':checked')) {
            import_widget = true;
        } else {
            import_widget = false;
        }
        if ($('#import_revslider_' + demo_name).is(':checked')) {
            import_revslider = true;
        } else {
            import_revslider = false;
        }
        if ($('#import_attachments_' + demo_name).is(':checked')) {
            import_attachments = true;
        } else {
            import_attachments = false;
        }
        if ($('#import_menu_' + demo_name).is(':checked')) {
            import_menu = true;
        } else {
            import_menu = false;
        }
        if ($('#import_theme_options_' + demo_name).is(':checked')) {
            import_theme_options = true;
        } else {
            import_theme_options = false;
        }
        if ($('#import_full_content_' + demo_name).is(':checked')) {
            import_full_content 	= true;
            import_widget 			= true;
            import_revslider 		= true;
            import_menu 			= true;
            import_content 			= true;
            import_attachments 		= true;
            import_theme_options 	= true;
        }
		
        /* Import content */
        if ( import_content ) {			
			var condent_no;
			for (condent_no = 1; condent_no <= 1; condent_no++) {
				var data = {
					'action'		: 'import_content',
					'count'			: condent_no,
					'attachments'	: import_attachments,
				}
				
				emallshop_import_request_data.push(data);
			}		
        }
		
		/* Import Menu */
		if ( import_menu ) {
            emallshop_import_request_data.push({
                'action'	: 'import_menu',
                'demo_name'	: demo_name,
            });
        }
		
		/* Import Theme Options */
        if ( import_theme_options ) {
            emallshop_import_request_data.push({
                'action'	: 'import_theme_options',
                'demo_name'	: demo_name,
            });
        }
		
		/* Import Widget */
        if ( import_widget ) {
            emallshop_import_request_data.push({'action': 'import_widget', 'demo_name': demo_name});
        }
		
		/* Import Slider */
        if ( import_revslider ) {
            emallshop_import_request_data.push({'action': 'import_revslider', 'demo_name': demo_name});
        }
        
		/* Import Configuration */
        emallshop_import_request_data.push({
            'action': 'import_config',
            'demo_name': demo_name,
        });
        
        var total_ajaxs = emallshop_import_request_data.length;
        
        if (total_ajaxs == 0) {
            import_btn.removeClass('processing');
            import_btn.removeClass('loading');
			import_btn.addClass('import-completed');
            return;
        }
        
        emallshop_import_percent_increase = (100 / total_ajaxs);
       
        emallshop_import_ajax_call();
        
        e.preventDefault();
		
	});
	
	function emallshop_import_ajax_call() {
        if (emallshop_import_index_request == emallshop_import_request_data.length) {
			alert('Import proceess done');
			location.reload();
            return;
        }
       $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: emallshop_import_request_data[emallshop_import_index_request],
            complete: function (jqXHR, textStatus) {
                emallshop_import_percent += emallshop_import_percent_increase;
                emallshop_import_progress_bar();
                emallshop_import_index_request++;
                setTimeout(function () {
                    emallshop_import_ajax_call();
                }, 200);
            }
        });
    }
	function emallshop_import_progress_bar(){
		if (emallshop_import_percent > 100) {
            emallshop_import_percent = 100;
        }
        
        if (emallshop_import_percent == 100) {
            $('.install-demo.processing').text('Import Completed');
			$('.emallshop-complete-action').show();
            $('.install-demo.processing').removeClass('loading');
            $('.install-demo.processing').removeClass('processing');
            
        }
        
        var progress_bar_wrap = $('[data-demo="' + emallshop_import_demo_name + '"]').closest('.emallshop-import-demo-popup').find('.import-process');
        progress_bar_wrap.find('.progress-percent').html(parseInt(emallshop_import_percent)+'%');  
        progress_bar_wrap.find('.progress-bar').css('width',parseInt(emallshop_import_percent)+'%');
	}
	
	function full_content_change() {
        $('.import_full_content').each(function () {
            var _this = $(this);
            if (_this.is(':checked')) {
                _this.closest('.import-options').find('input[type="checkbox"]').not(_this).attr('checked', false);
                _this.closest('.import-options').find('label').not(_this.parent()).css({
                    'pointer-events': 'none',
                    'opacity': '0.4'
                });
            } else {
                _this.closest('.import-options').find('label').not(_this.parent()).css({
                    'pointer-events': 'initial',
                    'opacity': '1'
                });
            }
        })
		if ($(".import-options input:checkbox:checked").length > 0)
		{
			$('.import-options').closest('.emallshop-box-body').find('.install-demo').removeClass('disabled');
		}
		else
		{
		   $('.import-options').closest('.emallshop-box-body').find('.install-demo').addClass('disabled');
		}
    }
    
    full_content_change();
    
    $(document).on('change', function () {
        full_content_change()
    });
	

} );



jQuery(function($) {
	
	$(document).on('click', '.emallshop-mask-overaly', function(e) {
		$.magnificPopup.close();
	});
	
});