/* Emallshop theme activation*/
jQuery( function ( $ ) {
	"use strict";
	var emallshop_activation = emallshop_activation || {};
	emallshop_activation.init = function() {
		var self = this;
		emallshop_activation.$doc          	= $(document)
		emallshop_activation.$html    		= $('html'),
		emallshop_activation.$body 			= $(document.body),
		emallshop_activation.$window 		= $(window),
		emallshop_activation.$windowWidth 	= $(window).width(),
		emallshop_activation.$windowHeight 	= $(window).height();
		self.activate_theme();
		self.deactivate_theme();
	};
	
	emallshop_activation.activate_theme = function() {
		// Activate theme
		$('body').on('click', '.emallshop-activate-btn', function() {
			var purchase_code = $(".purchase-code").val();
			var activate_btn = $(this);
			activate_btn.addClass('loading');
			if( $.trim(purchase_code) != ''){
				$(this).attr('disabled', 'true');
				var data = {
					action      	: 'activate_theme',
					purchase_code   : purchase_code,
					nonce   		: emallshop_admin_vars.nonce,
				};
				$.post(ajaxurl,data,function(response) {
					
					var data = $.parseJSON(response);
					
					if(data.success == '1'){
						alert(data.message);
						setTimeout(function(){location.reload();}, 5000);
					}else{
						alert(data.message);
						activate_btn.removeClass('loading');
						activate_btn.removeAttr('disabled');
					}			
				});
			} else {
				alert(emallshop_admin_vars.enter_purchase_code);
				activate_btn.removeClass('loading');
				activate_btn.removeAttr('disabled');
			}
			
			return false;
		});
	};
	
	
	emallshop_activation.deactivate_theme = function() {
		// deactivate theme
		$('body').on('click', '.emallshop-deactivate-btn', function() {
			var purchase_code = $(".purchase-code").val();
			var activate_btn = $(this);
			activate_btn.addClass('loading');
			if( $.trim(purchase_code) != ''){
				$(this).attr('disabled', 'true');
				var data = {
					action      	: 'deactivate_theme',
					purchase_code   : purchase_code,
					nonce   		: emallshop_admin_vars.nonce,
				};
				$.post(ajaxurl,data,function(response) {
					
					var data = $.parseJSON(response);
					console.log(data);
					if(data.success == '1'){
						alert(data.message);
						
						setTimeout(function(){location.reload();}, 5000);
					}else{
						alert(data.message);
						activate_btn.removeClass('loading');
						activate_btn.removeAttr('disabled');
					}
				});
			} else {
				alert(emallshop_admin_vars.empty_purchase_code);
				activate_btn.removeClass('loading');
						activate_btn.removeAttr('disabled');
			}
			//$(this).attr('disabled', 'true');
			return false;
		});
	};
	
	/**
	 * Document ready
	 */ 
	$(document).ready(function(){ 
		emallshop_activation.init();
    });
	
});
