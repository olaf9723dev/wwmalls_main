jQuery(document).ready(function ($) {

    if (typeof elementor === undefined) {
        return;
    }

    elementor.settings.page.addChangeCallback('motta_hide_header_section', function ( newValue ) {
        var $header = $('#elementor-preview-iframe').contents().find('#site-header');
        if( $header.length ) {
            if( newValue === '1' ) {
                $header.addClass('hidden');
            } else {
                $header.removeClass('hidden');
            }
        } else {
            $e.run('document/save/update', {}).then(function () {
                elementor.reloadPreview();
            });
        }
    });

    elementor.settings.page.addChangeCallback('motta_hide_topbar', function ( newValue ) {
        var $topbar = $('#elementor-preview-iframe').contents().find('#topbar');
        if( $topbar.length ) {
            if( newValue === '1' ) {
                $topbar.addClass('hidden');
            } else {
                $topbar.removeClass('hidden');
            }
        } else {
            $e.run('document/save/update', {}).then(function () {
                elementor.reloadPreview();
            });
        }
    });

    elementor.settings.page.addChangeCallback('motta_hide_campain_bar', function ( newValue ) {
        var $campaign_bar = $('#elementor-preview-iframe').contents().find('#campaign-bar');
        if( $campaign_bar.length ) {
            if( newValue === '1' ) {
                $campaign_bar.addClass('hidden');
            } else {
                $campaign_bar.removeClass('hidden');
            }
        } else {
            $e.run('document/save/update', {}).then(function () {
                elementor.reloadPreview();
            });
        }
    });

    elementor.settings.page.addChangeCallback('header_layout', function () {
        $e.run('document/save/update', {}).then(function () {
            elementor.reloadPreview();
        });
    });

    elementor.settings.page.addChangeCallback('header_logo_image', function () {
        $e.run('document/save/update', {}).then(function () {
            elementor.reloadPreview();
        });
    });

    elementor.settings.page.addChangeCallback('header_logo_text', function () {
        $e.run('document/save/update', {}).then(function () {
            elementor.reloadPreview();
        });
    });

    elementor.settings.page.addChangeCallback('header_logo_svg', function () {
        $e.run('document/save/update', {}).then(function () {
            elementor.reloadPreview();
        });
    });

    elementor.settings.page.addChangeCallback('header_category_menu_display', function (newValue) {
        $('#elementor-preview-iframe').contents().find('.header-category-menu').removeClass('header-category--open motta-open');
        if (newValue == 'onpageload') {
            $('#elementor-preview-iframe').contents().find('.header-category-menu').addClass('header-category--open motta-open');
        }
    });

    elementor.settings.page.addChangeCallback('motta_hide_page_header', function (newValue) {
        var $page_header = $('#elementor-preview-iframe').contents().find('#page-header');
        if( $page_header.length ) {
            if( newValue === '1' ) {
                $page_header.addClass('hidden');
            } else {
                $page_header.removeClass('hidden');
            }
        } else {
            $e.run('document/save/update', {}).then(function () {
                elementor.reloadPreview();
            });
        }
    });

    elementor.settings.page.addChangeCallback('motta_hide_title', function (newValue) {
        var $page_title = $('#elementor-preview-iframe').contents().find('#page-header .page-header__title');
        if( $page_title.length ) {
            if( newValue === '1' ) {
                $page_title.addClass('hidden');
            } else {
                $page_title.removeClass('hidden');
            }
        } else {
            $e.run('document/save/update', {}).then(function () {
                elementor.reloadPreview();
            });
        }
    });

    elementor.settings.page.addChangeCallback('motta_hide_breadcrumb', function (newValue) {
        var $breadcrumb = $('#elementor-preview-iframe').contents().find('#page-header .site-breadcrumb');
        if( $breadcrumb.length ) {
            if( newValue === '1' ) {
                $breadcrumb.addClass('hidden');
            } else {
                $breadcrumb.removeClass('hidden');
            }
        } else {
            $e.run('document/save/update', {}).then(function () {
                elementor.reloadPreview();
            });
        }
    });

    elementor.settings.page.addChangeCallback('motta_content_top_spacing', function (newValue) {
        $('#elementor-preview-iframe').contents().find('body').removeClass('no-top-spacing custom-top-spacing');
        if (newValue !== 'default') {
            $('#elementor-preview-iframe').contents().find('body').addClass(newValue + '-top-spacing');
        }
    });

    elementor.settings.page.addChangeCallback('motta_content_bottom_spacing', function (newValue) {
        $('#elementor-preview-iframe').contents().find('body').removeClass('no-bottom-spacing custom-bottom-spacing');
        if (newValue !== 'default') {
            $('#elementor-preview-iframe').contents().find('body').addClass(newValue + '-bottom-spacing');
        }
    });

    // Footer
    elementor.settings.page.addChangeCallback('motta_hide_footer_section', function ( newValue ) {
        var $footer = $('#elementor-preview-iframe').contents().find('#site-footer');
        if( $footer.length ) {
            if( newValue === '1' ) {
                $footer.addClass('hidden');
            } else {
                $footer.removeClass('hidden');
            }
        } else {
            $e.run('document/save/update', {}).then(function () {
                elementor.reloadPreview();
            });
        }
    });

    elementor.settings.page.addChangeCallback('footer_layout', function () {
        $e.run('document/save/update', {}).then(function () {
            elementor.reloadPreview();
        });
    });

    elementor.settings.page.addChangeCallback('footer_mobile_layout', function () {
        $e.run('document/save/update', {}).then(function () {
            elementor.reloadPreview();
        });
    });

      // Navigation bar
      elementor.settings.page.addChangeCallback('motta_hide_navigation_bar', function ( newValue ) {
        var $footer = $('#elementor-preview-iframe').contents().find('#motta-mobile-navigation-bar');
        if( $footer.length ) {
            if( newValue === '1' ) {
                $footer.addClass('hidden');
            } else {
                $footer.removeClass('hidden');
            }
        } else {
            $e.run('document/save/update', {}).then(function () {
                elementor.reloadPreview();
            });
        }
    });

});