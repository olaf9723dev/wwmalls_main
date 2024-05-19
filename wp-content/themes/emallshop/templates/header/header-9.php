<?php
if(emallshop_get_option('show-topbar', 1)==1):?>
<div class="header-topbar">
	<div class="container">
		<div class="row">
			<div class="col-sm-4 text-left">
				<?php if( function_exists( 'emallshop_social_link' ) ) {
					emallshop_social_link();
				}?>
			</div>
			<div class="col-sm-8 text-right">
				<div class="topbar-right">
					<?php if( function_exists( 'emallshop_welcome_message' ) ) {
						emallshop_welcome_message();
					}
					if( function_exists( 'emallshop_dokan_header_user_menu' ) ) {
						emallshop_dokan_header_user_menu();
					}else{
						if( function_exists( 'emallshop_all_myaccount' ) ) {
							emallshop_all_myaccount();
						}
					}
					if( function_exists( 'emallshop_currency' ) ) {
						emallshop_currency();
					}
					if( function_exists( 'emallshop_language' ) ) {
						emallshop_language();
					}?>                          
				</div>
			</div>
		</div>
	</div>
</div>
<?php endif;?>
<div class="header-middle">
	<div class="container">
		<div class="row">
			<div class="col-xs-3 col-sm-3 hidden-md hidden-lg">
				<?php if( function_exists( 'emallshop_header_mobile_toggle' ) ) {
					emallshop_header_mobile_toggle();
				}?>
			</div>
			<div class="col-md-4 hidden-xs hidden-sm text-left">
				<div class="header-right">			
					<?php emallshop_customer_support();?>					
				</div>
			</div>
			<div class="col-xs-6 col-sm-6 col-md-3">
				<?php emallshop_header_logo();?>
			</div>
			<div class="col-md-5 hidden-xs hidden-sm">
				<?php emallshop_products_live_search_form();?>
			</div>
			<div class="col-xs-3 col-sm-3 hidden-md hidden-lg ">
				<div class="header-right">
					<?php emallshop_header_cart();?>				
				</div>
			</div>
			
		</div>
	</div>
</div>
<div class="header-navigation">
	<div class="container">
		<div class="row">
			<div class="col-xs-6 col-md-9">
				<?php emallshop_header_menu();?>
			</div>
			<div class="col-xs-6 col-md-3">
				<?php emallshop_header_cart();?>
			</div>
		</div>
	</div>
</div>