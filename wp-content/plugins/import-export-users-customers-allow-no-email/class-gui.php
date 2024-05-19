<?php

class ACUI_ANM_GUI{
    function __construct(){
    }

    function bootstrap(){
        add_action( 'acui_homepage_after_options_rows', array( $this, 'gui' ) );
    }

    function gui(){
        ?>
        <tr class="form-field form-required">
            <th scope="row"><label for=""><?php _e( 'Force users to change their emails if they are being imported blank?', 'import-users-from-csv-with-meta' ); ?></label></th>
            <td>
                <?php ACUIHTML()->checkbox( array( 'name' => 'force_user_change_emails_blank', 'label' => __( 'If you import blank emails, you can force your users to update their emails in the first login', 'import-users-from-csv-with-meta' ), 'current' => 'yes', 'compare_value' => get_option( 'acui_manually_force_user_reset_password' ) ) ); ?>
            </td>
        </tr>
        <?php
    }
}

add_action( 'init', function(){
    $acui_anm_gui = new ACUI_ANM_GUI();
    $acui_anm_gui->bootstrap();
} );