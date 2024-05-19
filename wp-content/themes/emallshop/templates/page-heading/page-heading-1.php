<?php
$show_title = emallshop_getShowTitle( get_post_meta ( get_the_ID(), '_emallshop_show_title', true ) );
$show_breadsrumb = emallshop_getShowBreadsrumb( get_post_meta ( get_the_ID(), '_emallshop_show_breadsrumb', true ) );

if( emallshop_get_option('show-page-breadcrumb', 1) ):
	if( $show_breadsrumb == 'yes' ):?>
		<div class="page-heading page-heading-1">
			<div class="container">
				<div class="row">
					<div class="col-xs-12 ">
						<?php if( emallshop_get_option('show-page-breadcrumb', 1)  ):
							if( $show_breadsrumb == 'yes' ):								
								echo emallshop_breadcrumbs(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							endif;
						endif;?>
					</div>			
				</div>
			</div>
		</div>
<?php endif;
endif;?>
