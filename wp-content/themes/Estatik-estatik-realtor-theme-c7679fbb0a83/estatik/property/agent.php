<?php

/**
 * @var Es_Agent $agent
 */

/** @var $es_settings Es_Settings_Container */
global $es_settings;
$user = $agent->get_entity();
$listings_page = $es_settings->listing_agent_page_id;

if ( $listings_page ) {
	$listings_page = get_permalink( $listings_page );
}

$grid_num = is_singular( 'properties' ) ? 4 : 3; ?>

<div class="ert-agent">
	<div class="row">
		<div class="col-sm-auto ert-agent__image">
            <div class="ert-agent__image-bg" style="height: 100%; background-image: url(<?php echo es_get_agent_thumbnail( $agent ); ?>); background-size: cover;"></div>
        </div>
		<div class="col-sm ert-agent__content">

            <div class="ert-agent__content-inner">
                <div class="row">
                    <div class="col">
                        <div class="ert-agent__head">
                            <h4 class="ert-agent__name">
                                <span><?php echo $agent->get_full_name(); ?></span>
                            </h4>

                            <?php if ( ! empty( $agent->company ) ) : ?>
                                <span class="ert-agent__sub-name">
                            <?php echo $agent->company; ?>
                        </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if ( ! $es_settings->hide_agent_rating ) : ?>
                        <div class="col-auto">
                            <?php do_action( 'ert_rating', $agent->rating, true ); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <?php do_action( 'ert_social_block', $agent, true ); ?>

                <ul class="ert-agent__fields">
                    <?php if ( $agent->tel ) : ?>
                        <li><b><?php echo $agent->tel; ?></b></li>
                    <?php endif; ?>

                    <?php if ( $user->user_email ) : ?>
                        <li><b><?php echo $user->user_email; ?></b></li>
                    <?php endif; ?>

                    <?php if ( $user->user_url ) : ?>
                        <li><b><a href="<?php echo esc_url( $user->user_url ); ?>"><?php echo esc_url( $user->user_url ); ?></a></b></li>
                    <?php endif; ?>
                </ul>

                <?php if ( $user->description ) : ?>
                    <p><?php echo $user->description; ?></p>
                <?php endif; ?>
            </div>

            <?php if ( $listings_page ) : ?>
                <div class="text-right ert-agent__bottom">
                    <a href="<?php echo $listings_page; ?>" target="_blank" class="btn btn-light"><?php _e( 'View properties', 'ert' ); ?></a>
                </div>
            <?php endif; ?>

        </div>
	</div>
</div>
