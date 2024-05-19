<?php

/**
 * @var $agent Es_Agent
 */

?>
<div class="ert-agent ert-agent__partial">
    <div class="ert-agent__image">
        <?php if ( $image = $agent->get_image_url() ) : ?>
            <img src="<?php echo $image; ?>" alt="<?php _e( 'Agent Photo', 'ert' ); ?>"/>
        <?php endif; ?>
    </div>
    <div class="ert-agent__content">
        <h4 class="ert-agent__name">
            <span><?php echo $agent->get_full_name(); ?></span>
        </h4>

        <?php if ( ! empty( $agent->company ) ) : ?>
            <span class="ert-agent__sub-name">
                <?php echo $agent->company; ?>
            </span>
        <?php endif; ?>

        <?php do_action( 'ert_social_block', $agent, true ); ?>

        <?php if ( $agent->tel ) : ?>
            <ul class="ert-agent__fields">
                <li><b><?php echo $agent->tel; ?></b></li>
            </ul>
        <?php endif; ?>
    </div>
</div>