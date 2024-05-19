<form role="search" method="get" id="searchform" action="<?php echo home_url( '/' ) ?>" >
	<input type="text" placeholder="<?php _e( 'Search here', 'ert' ); ?>" value="<?php echo get_search_query() ?>" name="s" id="s" />
	<input type="submit" id="searchsubmit" value="<?php _e( 'Search', 'ert' ); ?>" />
</form>