<?php

namespace WeDevs\DokanPro\Announcement;

use WP_Post;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Announcement Mails
 *
 * @since 3.9.4
 */
class Mails {
    /**
     * Class constructor
     *
     * @since 2.8.2
     * @since 3.7.25 moved this method from includes/functions.php to this class
     * @since 3.9.4 moved this method from Announcement class to this class
     */
    public function __construct() {
        add_action( 'dokan_after_announcement_saved', [ $this, 'send_announcement_email' ] );
        add_action( 'future_to_publish', [ $this, 'send_scheduled_announcement_email' ] );
    }

    /**
     * Send announcement email
     *
     * @since 2.8.2
     * @since 3.7.25 moved this method from includes/functions.php to this class
     * @since 3.9.4 moved this method from Announcement class to this class
     *
     * @param $announcement_id
     *
     * @return void
     */
    public function send_announcement_email( $announcement_id ) {
        $this->trigger_mail( $announcement_id );
    }

    /**
     * Send email for a scheduled announcement
     *
     * @since 2.9.13
     * @since 3.7.25 moved this method from includes/functions.php to this class
     * @since 3.9.4 moved this method from Announcement class to this class
     *
     * @param WP_Post $post
     *
     * @return void
     */
    public function send_scheduled_announcement_email( $post ) {
        if ( 'dokan_announcement' !== $post->post_type ) {
            return;
        }

        $this->trigger_mail( $post->ID );
    }

    /**
     * Trigger mail
     *
     * @since 2.8.0
     * @since 3.9.4 rewritten this method
     *
     * @return void
     */
    protected function trigger_mail( $post_id ) {
        $announcement = dokan_pro()->announcement->manager->get_single_announcement( $post_id );

        if ( is_wp_error( $announcement ) ) {
            return;
        }

        if ( 'publish' !== $announcement->get_status() ) {
            return;
        }

        $assigned_sellers = dokan_pro()->announcement->manager->get_assigned_seller_from_db( $announcement->get_id(), true );
        if ( empty( $assigned_sellers ) ) {
            return;
        }

        foreach ( $assigned_sellers as $vendor_id ) {
            $payload = [
                'post_id'   => $post_id,
                'sender_id' => $vendor_id,
            ];

            dokan_pro()->announcement->processor->push_to_queue( $payload );
        }

        dokan_pro()->announcement->processor->save()->dispatch();
    }
}
