<div class="megamenu-modal__menu">
	<# if ( data.depth == 0 ) { #>
		<a href="#" class="media-menu-item {{ data.current === 'mega' ? 'active' : '' }}" data-panel="mega" data-title="<?php esc_attr_e( 'Mega Menu', 'motta-addons' ) ?>"><?php esc_html_e( 'Mega Menu', 'motta-addons' ) ?></a>
		<a href="#" class="media-menu-item {{ data.current === 'design' ? 'active' : '' }}" data-panel="design" data-title="<?php esc_attr_e( 'Mega Menu Design', 'motta-addons' ) ?>"><?php esc_html_e( 'Design', 'motta-addons' ) ?></a>
	<# } else if ( data.depth == 1 ) { #>
		<# if ( data.in_mega && 'tabs' === data.in_mega_mode ) { #>
			<a href="#" class="media-menu-item {{ data.current === 'tab-content' ? 'active' : '' }}" data-panel="tab-content" data-title="<?php esc_attr_e( 'Tab Content', 'motta-addons' ) ?>"><?php esc_html_e( 'Tab Content', 'motta-addons' ) ?></a>
		<# } else { #>
			<a href="#" class="media-menu-item {{ data.current === 'settings' ? 'active' : '' }}" data-panel="settings" data-title="<?php esc_attr_e( 'Menu Setting', 'motta-addons' ) ?>"><?php esc_html_e( 'Settings', 'motta-addons' ) ?></a>
			<a href="#" class="media-menu-item {{ data.current === 'content' ? 'active' : '' }}" data-panel="content" data-title="<?php esc_attr_e( 'Menu Content', 'motta-addons' ) ?>"><?php esc_html_e( 'Content', 'motta-addons' ) ?></a>
		<# } #>
		<a href="#" class="media-menu-item {{ data.current === 'design' ? 'active' : '' }}" data-panel="design" data-title="<?php esc_attr_e( 'Mega Column Design', 'motta-addons' ) ?>"><?php esc_html_e( 'Design', 'motta-addons' ) ?></a>
	<# } else { #>
		<a href="#" class="media-menu-item {{ data.current === 'content' ? 'active' : '' }}" data-panel="content" data-title="<?php esc_attr_e( 'Menu Content', 'motta-addons' ) ?>"><?php esc_html_e( 'Content', 'motta-addons' ) ?></a>
	<# } #>
	<a href="#" class="media-menu-item {{ data.current === 'icon' ? 'active' : '' }}" data-panel="icon" data-title="<?php esc_attr_e( 'Menu Icon', 'motta-addons' ) ?>"><?php esc_html_e( 'Icon', 'motta-addons' ) ?></a>
</div>