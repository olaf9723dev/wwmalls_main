<?php
/**
 *  Dokan Dashboard Template
 *
 *  Dokan Main Dahsboard template for Fron-end
 *
 *  @since 2.4
 *
 *  @package dokan
 */
?>
<div class="dokan-dashboard-wrap">
    <?php

        /**
         *  dokan_dashboard_content_before hook
         *
         *  @hooked get_dashboard_side_navigation
         *
         *  @since 2.4
         */
            do_action( 'dokan_dashboard_content_before' );
    ?>

    <div class="dokan-dashboard-content">

        <?php
            /**
             *  dokan_dashboard_content_before hook
             *
             *  @hooked show_seller_dashboard_notice
             *
             *  @since 2.4
             */
            do_action( 'dokan_help_content_inside_before' );
        ?>
    <!----Container to be show on Vendor Dashboard for Ads---->
        <article class="help-content-area">
            <h3>Make Your Advertisement for Your Business</h3>
            <?php
            if (dokan_is_seller_enabled(get_current_user_id())){
                // Tab 
                echo '<div class="tab">';
                    echo '<button class="tablinks" onclick="openTab(event, `Publish`)" id="defaultOpen">Publish</button>';
                    echo '<button class="tablinks" onclick="openTab(event, `Manage`)">Manage</button>';
                    echo '<button class="tablinks" onclick="openTab(event, `History`)">History</button>';
                echo '</div>';
                // Publish Container for Pubkish Tab.
                echo '<div style="border:1px solid #ccc;border-top:none;">
                        <div id="Publish" class="tabcontent">
                            <form id="upload-form" enctype="multipart/form-data" action="" method="post">
                                <div class="image-container">
                                    <div id="preview-container">
                                        <label>300×300</label>
                                    </div>
                                    <input class="file file-block" type="file" id="image-input" name="image" accept="image/*" onchange="previewImage(event)">
                                </div>
                                <div class="input-item">
                                    <label for="title">Title:</label>
                                    <input type="text" id="title" name="title">
                                </div>';
                function generate_category_options($categories, $depth = 0){
                    foreach ($categories as $category) {
                        echo '<option value="' . esc_attr( $category->term_id ) . '">' . str_repeat( '&nbsp;&nbsp;', $depth * 2 ) . esc_html( $category->name ) . '</option>';
                        $subcategories = get_terms( array(
                            'taxonomy' => 'product_cat',
                            'hide_empty' => false,
                            'parent' => $category->term_id, //Only top-level categories
                        ));
                        if($subcategories){
                            generate_category_options($subcategories, $depth + 1);
                        }
                    }
                }
                $product_categories = get_terms( array(
                    'taxonomy' => 'product_cat',
                    'hide_empty' => false,
                    'parent' => 0, //Only top-level categories
                ));
                if(! empty($product_categories) && !is_wp_error($product_categories)){
                    echo '<div class="input-item">';
                    echo '<label for="product-category">Product Category:</label>';
                    echo '<select name="product-category" id = "product-category">';
                    echo '<option value="">--Select Category--</option>';
                        generate_category_options($product_categories);
                    echo '</select>';
                    echo '</div>';
                }
                echo            '<div class="input-item">
                                    <label for="product-name">Product Name:</label>
                                    <select id="product-name" name="product-name">
                                        <option value="">--Select Product--</option>
                                    </select>
                                </div>
                                <div class="input-item">
                                    <label for="d-price">Discount Price($):</label>
                                    <input type="number" id="d-price" name="d-price" min="0">
                                </div>
                                <div class="input-item">
                                    <div style="display:block;">
                                        <label for="period">Period(Default 2days and $5 per day for more):</label>
                                        <input type="number" id="period" name="period" min="2">
                                    </div>
                                    <div style="display:block;">
                                        <label for="payout">Payout:</label>
                                        <input type="number" id="payout" name="period" min="25" readonly>
                                    </div>
                                </div>
                                <div class="input-item">
                                    <label for="description">Description:</label>
                                    <textarea id="description" name="description"></textarea>
                                </div>
                                <button class="publish-btn" type="submit" name=`publish-ads`>Publish</button>
                            </form>
                        </div>
                        // Container for Manage Tab.
                        <div id="Manage" class="tabcontent">
                            <p>You added nothing for advertisement.</p> 
                        </div>
                        // Container for History Tab.
                        <div id="History" class="tabcontent">
                            <p>There is no history for advertisement.</p> 
                        </div>
                    </div>';
            }else{
                echo '<div class="dokan-alert dokan-alert-warning">' . esc_html__('Warning! You are not allowed to sell yet. Please contact the admin for approval.', 'text-domain') . '</div>';
            }
            ?> 
        </article><!-- .dashboard-content-area -->
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                document.getElementById("defaultOpen").click();
                ClassicEditor
                    .create(document.querySelector('#description'))
                    .catch(error => {
                        console.error(error);
                    });
                var periodInput = document.getElementById("period");
                periodInput.addEventListener("input", function() {
                    var period = parseInt(this.value);
                    var payoutInput = document.getElementById("payout");
                    if (period > 2) {
                        payoutInput.value = 25 + (period - 2) * 5;
                    } 
                    else if(period == 2) {
                        payoutInput.value = 25;
                    }
                });
            });
        </script>

         <?php
            /**
             *  dokan_dashboard_content_inside_after hook
             *
             *  @since 2.4
             */
            do_action( 'dokan_dashboard_content_inside_after' );
        ?>
    </div><!-- .dokan-dashboard-content -->

    <?php
        /**
         *  dokan_dashboard_content_after hook
         *
         *  @since 2.4
         */
        do_action( 'dokan_dashboard_content_after' );
    ?>

</div><!-- .dokan-dashboard-wrap -->