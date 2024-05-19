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
                    // echo '<button class="tablinks" onclick="openTab(event, `History`)">History</button>';
                echo '</div>';
                echo '<div style="border:1px solid #ccc;border-top:none;">
                        <div id="Publish" class="tabcontent">
                            <form id="upload-form" enctype="multipart/form-data" action="" method="post">
                                <div class="image-container" style="margin-bottom:30px">
                                    <div id="preview-container">
                                        <label>300×300</label>
                                    </div>
                                    <input class="file file-block" type="file" id="image-ads" name="image-ads" accept="image/*" onchange="previewImage(event)">
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
                    echo '<div class="input-item" style="display:flex;flex-direction:row">';
                    echo '<label for="product-category">Product Category:</label>';
                    echo '<div id="dokan-category-box">';
                    echo '<div class="custom-product-list-input item"><input type="text" id="product-category" name="product-category" placeholder="Enter Product name" required><ul id="products-dropdown-menu" style="display: none;"></ul></div>';
                    echo '<select name="product-category" id = "product-category" style="display:none">';
                    echo '<option value="">--Select Category--</option>';
                        generate_category_options($product_categories);
                    echo '</select>';
                    echo '</div>';
                    echo '</div>';
                }
                echo            '<div class="input-item" style="display:flex;flex-direction:row;align-items:center;">
                                    <div style="margin-right:15px">
                                        <label for="product-name">Product Name:</label>
                                        <select id="product-name" name="product-name">
                                            <option value="">--Select Product--</option>
                                        </select>
                                    </div>
                                    <div class="product_name_sticker custom-loader" style="width: 28px; height: 28px;"></div>
                                </div>
                                <div class="input-item" style="display:flex;flex-direction:row;align-items:center;">
                                    <div style="margin-right:15px">
                                        <label for="r-price">Regular Price($):</label>
                                        <input type="number" id="r-price" name="r-price" min="0" readonly>
                                    </div>
                                    <div class="r_price_sticker custom-loader" style="width: 28px; height: 28px;"></div>
                                </div>
                                <div class="input-item">
                                    <label for="d-price">Discount(%):</label>
                                    <input type="number" id="d-price" name="d-price" min="0" max="100">
                                </div>
                                <div class="input-item">
                                    <label for="n-price">Net Price($):</label>
                                    <input type="number" id="n-price" name="n-price" min="0" readonly>
                                </div>
                                <div class="input-item" style="display:flex;flex-direction:row">
                                    <div style="display:block;margin-right:20px">
                                        <label for="period">Term(min 2days):</label>
                                        <input type="number" id="period" name="period" min="2">
                                    </div>
                                    <div style="display:block;">
                                        <label for="payout">Payout($):</label>
                                        <input type="number" id="payout" name="payout" min="25" readonly>
                                    </div>
                                </div>
                                <div class="input-item">
                                    <label for="title">Title:</label>
                                    <input type="text" id="title" name="title">
                                </div>
                                <div class="input-item">
                                    <label for="description">Description:</label>
                                    <textarea id="description" name="description"></textarea>
                                </div>
                                <button class="publish-btn" type="submit" name="publish-ads">Publish</button>
                            </form>
                        </div>
                        <div id="Manage" class="tabcontent">
                            <div id="delete-ads-modal" class="modal">
                                <p>Are you sure you want to delete the selected Ads?
                                    You will not be refunded for this action.</p>
                                <button id="yesBtn" class="dokan-btn dokan-btn-sm dokan-btn-danger">Yes</button>
                                <button id="noBtn" class="dokan-btn dokan-btn-sm dokan-btn-theme">No</button>
                            </div>';
                        $user_id = get_current_user_id();
                        global $wpdb;
                        $type = "ads";
                        $query = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}posts INNER JOIN {$wpdb->prefix}postmeta ON {$wpdb->prefix}posts.ID = {$wpdb->prefix}postmeta.post_id
                        WHERE post_author = %s AND post_type = %s", $user_id, $type);
                        $results = $wpdb->get_results($query);
                        if(count($results)){
                            echo '<form id="manage-ads-form" action="" method="post">
                                    <table id="vendortable" class="wp-list-table widefat fixed striped" style="width:100%;table-layout: auto;"> 
                                        <thead> 
                                            <tr>
                                                <td class="check-column"><input id ="masterCheckbox" type="checkbox" name="all" value="all"></th>
                                                <th class="column" style="text-align:center">Ads Title</th> 
                                                <th class="column" style="text-align:center">Store</th> 
                                                <th class="column" style="text-align:center">Product</th> 
                                                <th class="column" style="text-align:center">Status</th> 
                                                <th class="column" style="text-align:center">Published at</th> 
                                                <th class="column" style="text-align:center">Expire at</th>
                                                <th class="column" style="text-align:center">Manage</th>
                                            </tr> 
                                        </thead> 
                                        <tbody>';
                            foreach ($results as $result) {
                                $meta_value = unserialize($result->meta_value);
                                $store_info = dokan_get_store_info($result->post_author);
                                $store_name = $store_info['store_name'];
                                $product_name = get_the_title(intval($meta_value['name']));
                                $status = $meta_value['status'];
                                $pub_date = $result->post_date;
                                $expiration = (string)($meta_value['period']) . " days";
                                echo '<tr>';
                                echo '<th class="check-column"><input class="subCheckbox" type="checkbox" name="delete[]" value="' . ($result ->ID) . '"></td>';
                                echo '<td class="dokan-ads-t" style="text-align:center">' . ($result->post_title) . '</td>';
                                echo '<td style="text-align:center">' . ($store_name ?? ''). '</td>';
                                echo '<td style="text-align:center">' . ($product_name ?? '') . '</td>';
                                if($status == "pending"){
                                    echo '<td style="text-align:center"><label class="switch"><input type="checkbox" id="status" name="status" disabled><span class="slider round"></span></label></td>';
                                }else{
                                    echo '<td style="text-align:center"><label class="switch"><input type="checkbox" id="status" name="status" checked disabled><span class="slider round"></span></label></td>';
                                }
                                    echo '<td style="text-align:center">' . ($pub_date ?? '') . '</td>';
                                    echo '<td style="text-align:center">' . ($expiration ?? '') . '</td>';
                                echo '<td style="text-align:center"><input class="edit-btn" type="button" value="Edit"><input class="save-btn" type="button" value="Save"></td>';
                                echo '</tr>';
                                
                            }
                            echo '      </tbody>
                                        <tfoot>
                                            <tr>
                                                <td class="column" style="text-align:center">Action</th>
                                                <th class="column" style="text-align:center">Ads Title</th> 
                                                <th class="column" style="text-align:center">Store</th> 
                                                <th class="column" style="text-align:center">Product</th>
                                                <th class="column" style="text-align:center">Status</th> 
                                                <th class="column" style="text-align:center">Published at</th> 
                                                <th class="column" style="text-align:center">Expire at</th>
                                                <th class="column" style="text-align:center">Manage</th>
                                            </tr> 
                                        </tfoot>
                                    </table>
                                    <input type="submit" id="bulk_delete_ads" name="bulk_delete_ads" value="Delete Selected Ads" class="dokan-btn dokan-btn-sm dokan-btn-danger dokan-btn-theme" style="margin-top:10px">
                                </form>';
                        }else{
                            echo '<p>You added nothing for advertisement.</p>'; 
                        }
                            
            echo        '</div>
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
            jQuery(document).ready(function($){
                $("#masterCheckbox").change(function(){
                    $(".subCheckbox").prop('checked', $(this).prop("checked"));
                });
    
                $(".subCheckbox").change(function(){
                    if($(".subCheckbox:checked").length == $(".subCheckbox").length){
                        $("#masterCheckbox").prop('checked', true);
                    }
                    else{
                        $("#masterCheckbox").prop('checked', false);
                    }
                });
                
                $('.tabcontent #upload-form').submit(function(){
                    if($('#product-category').val() == "" || $('#product-name').val() == "" || $('#d-price').val()=="" || $('#title').val() == "" || $('#description').val()==""){
                        event.preventDefault();
                        toastr.warning('Please complete all field');
                    }
                });
            });
            document.addEventListener("DOMContentLoaded", function() {
                document.getElementById("defaultOpen").click();
                ClassicEditor
                    .create(document.querySelector('#description'))
                    .catch(error => {
                        console.error(error);
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