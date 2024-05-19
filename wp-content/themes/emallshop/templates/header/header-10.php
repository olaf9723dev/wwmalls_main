<?php
if(emallshop_get_option('show-topbar', 1)==1):?>
<div class="header-topbar">
	<div class="container">
		<div class="row">
			<div class="col-sm-4 text-left">
				<?php 
				emallshop_currency();
				emallshop_language();?>
				
			</div>
			<div class="col-sm-8 text-right">
				<div class="topbar-right">
					<?php if( function_exists( 'emallshop_dokan_header_user_menu' ) ) {
						emallshop_dokan_header_user_menu();
					}else{					
						if( function_exists( 'emallshop_myaccount' ) ) {
							emallshop_myaccount();
						}
						if( function_exists( 'emallshop_checkout' ) ) {
							emallshop_checkout();
						}
					}
					if( function_exists( 'emallshop_wishlist' ) ) {
						emallshop_wishlist();
					}
					if( function_exists( 'emallshop_campare' ) ) {
						emallshop_campare();
					}
					if( function_exists( 'emallshop_cart' ) ) {
						emallshop_cart();
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
			<div class="col-xs-6 col-sm-6 col-md-3">
				<?php if( function_exists( 'emallshop_header_logo' ) ) {
					emallshop_header_logo();
				}?>
			</div>
			<div class="col-md-9 hidden-xs hidden-sm">
				<?php if( function_exists( 'emallshop_header_menu' ) ) {
					emallshop_header_menu();
				}?>	
			</div>
			<div class="col-xs-3 col-sm-3 hidden-md hidden-lg ">
				<div class="header-right">
					<?php emallshop_header_cart();?>				
				</div>
			</div>
		</div>
	</div>
</div>
