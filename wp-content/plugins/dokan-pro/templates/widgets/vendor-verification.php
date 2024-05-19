<?php
extract( $data, EXTR_SKIP );

$title = apply_filters( 'widget_title', $instance['title'] );

echo $before_widget;

if ( ! empty( $title ) ) {
    echo $before_title . $title . $after_title;
}

if ( empty( $store_info['dokan_verification'] ) ) {
    return;
}
?>
<div id="dokan-verification-list">
    <ul class="fa-ul">
        <?php
        foreach ( $store_info['dokan_verification'] as $key => $item ) {
            $widget->print_item( $key, $item );
        }
        ?>
    </ul>
</div>
<?php
echo $after_widget;
