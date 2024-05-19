<?php
/*
Template Name: Custom Deal Page
*/
get_header(); 

$user_id = get_current_user_id();
global $wpdb;
$limit = 12;
$page = isset($_GET['paged']) ? $_GET['paged'] : 1;
$search = isset($_GET['search']) ? $_GET['search'] :'';
$search_by = isset($_GET['searchBy']) ? $_GET['searchBy'] :'';

$offset = ($page-1) * $limit;
$type = "ads";
$status = "allowed";
$totalrows=0;
$totalpages=0;
$results = Array();
if($search == ''){
    // Count total rows
    $count_query = $wpdb->prepare("
        SELECT COUNT(*) 
        FROM {$wpdb->prefix}posts AS p 
        INNER JOIN {$wpdb->prefix}postmeta AS pm ON p.ID = pm.post_id 
        WHERE p.post_type = %s AND p.post_status = %s
    ", $type, $status);
    $totalrows = $wpdb->get_var($count_query);
    // Calculate total pages
    $totalpages = ceil($totalrows / $limit);
    // Fetch results for the current page
    $query = $wpdb->prepare("
        SELECT p.*, pm.*
        FROM {$wpdb->prefix}posts AS p 
        INNER JOIN {$wpdb->prefix}postmeta AS pm ON p.ID = pm.post_id 
        WHERE p.post_type = %s AND p.post_status = %s 
        LIMIT %d OFFSET %d
    ", $type, $status, $limit, $offset);
    $results = $wpdb->get_results($query);
    // $count_query = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}posts INNER JOIN {$wpdb->prefix}postmeta ON {$wpdb->prefix}posts.ID = {$wpdb->prefix}postmeta.post_id WHERE post_type = %s AND post_status = %s", $type, $status);
    // $all_datas = $wpdb->get_results($count_query);
    // $totalrows = count($all_datas);
    // $totalpages=ceil($totalrows/$limit);
    // $query = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}posts INNER JOIN {$wpdb->prefix}postmeta ON {$wpdb->prefix}posts.ID = {$wpdb->prefix}postmeta.post_id WHERE post_type = %s AND post_status = %s LIMIT %d OFFSET %d", $type, $status, $limit, $offset);
    // $results = $wpdb->get_results($query);
}
else{
    if($search_by == 0){
        try{
            $count_query = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}posts INNER JOIN {$wpdb->prefix}postmeta ON {$wpdb->prefix}posts.ID = {$wpdb->prefix}postmeta.post_id WHERE post_type = %s AND post_status = %s", $type, $status);
            $all_datas = $wpdb->get_results($count_query);
            
            foreach ($all_datas as $data) {
                $meta_value = unserialize($data->meta_value);
                if (is_array($meta_value) && isset($meta_value['name'])) {
                    $productname_search = $wpdb->get_results(
                        $wpdb->prepare("SELECT * FROM {$wpdb->prefix}posts WHERE post_title LIKE %s AND post_type = %s AND ID = %d",
                            '%' . $wpdb->esc_like($search) . '%', 'product', $meta_value['name']
                        )
                    );
                    if (!empty($productname_search)) {
                        $results[] = $data;
                    }
                }
            }
            $totalrows = count($results);
            $totalpages = ceil($totalrows / $limit);
        }catch (Exception $e) {
            echo "An error occurred: " . $e->getMessage();
        }
    }
    if ($search_by == 1) {
        try {
            $count_query = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}posts INNER JOIN {$wpdb->prefix}postmeta ON {$wpdb->prefix}posts.ID = {$wpdb->prefix}postmeta.post_id WHERE post_type = %s AND post_status = %s", $type, $status);
            $all_datas = $wpdb->get_results($count_query);
    
            foreach ($all_datas as $data) {
                $meta_value = unserialize($data->meta_value);
                if (is_array($meta_value) && isset($meta_value['name'])) {
                    $productcat_search = $wpdb->get_results(
                        $wpdb->prepare("SELECT * FROM {$wpdb->prefix}term_relationships INNER JOIN {$wpdb->prefix}term_taxonomy ON {$wpdb->prefix}term_relationships.term_taxonomy_id = {$wpdb->prefix}term_taxonomy.term_taxonomy_id
                        INNER JOIN {$wpdb->prefix}terms ON {$wpdb->prefix}term_taxonomy.term_id = {$wpdb->prefix}terms.term_id WHERE object_id = %d AND taxonomy = %s AND {$wpdb->prefix}terms.slug LIKE %s",
                            $meta_value['name'], 'product_cat', '%' . $wpdb->esc_like($search) . '%'
                        )
                    );
                    if (!empty($productcat_search)) {
                        $results[] = $data;
                    }
                }
            }
            $totalrows = count($results);
            $totalpages = ceil($totalrows / $limit);
        } catch (Exception $e) {
            echo "An error occurred: " . $e->getMessage();
        }
    }
}
?>
<!-- Your custom page content goes here -->
<link rel="stylesheet" href="https://bootswatch.com/5/journal/bootstrap.css">

<div id="comment-ads-modal" class="modal" style="position:relative;height:auto;">
    <textarea id="ads-msg"></textarea>
    <button id="yesBtn" class="dokan-btn dokan-btn-sm dokan-btn-danger">Send</button>
    <button id="noBtn" class="dokan-btn dokan-btn-sm dokan-btn-theme">Cancel</button>
</div>

<div class = "container clearfix">
    <div class="row mb-5">
        <div class="col-lg-12">
            <div class="page-header">
                <h1 id="containers">Find GREAT deals here !</h1>
            </div>
        </div>
    </div>
    <div class="row" >
        <div class="input-group mb-3" style="display:flex;justify-content:center;">
            <input type="search" id="post-search-input" class = "form-control" name="s" placeholder="Search...">
            <div class="column">
                    <select name="badge_status" id="badge_name" class="vendor-picker" style="min-width: 100%;">
                        <option value="0">ProductName</option> 
                        <option value="1">ProductCategory</option>
                    </select>
                </div>
            <button id="searchButton" class="button button-primary" style="color:white!important"><span class="motta-button__icon"><span class="motta-svg-icon motta-svg-icon--search"><svg width="24" height="24" aria-hidden="true" role="img" focusable="false" viewBox="0 0 32 32"><path d="M28.8 26.544l-5.44-5.44c1.392-1.872 2.24-4.192 2.24-6.704 0-6.176-5.024-11.2-11.2-11.2s-11.2 5.024-11.2 11.2 5.024 11.2 11.2 11.2c2.512 0 4.832-0.848 6.688-2.24l5.44 5.44 2.272-2.256zM6.4 14.4c0-4.416 3.584-8 8-8s8 3.584 8 8-3.584 8-8 8-8-3.584-8-8z"></path></svg></span></span></button>
            <button id="showAll" class="btn btn-primary">Show All</button>
        </div>
    </div>
    <div class="row">
        <?php  
        if(count($results)){
            foreach ($results as $result) {
                $meta_value = unserialize($result->meta_value);
                global $wpdb;
                $product_regular_price = get_post_meta($meta_value['name'], '_regular_price', true);
                $product_sale_price = get_post_meta($meta_value['name'], '_sale_price', true);
                
                $query = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}usermeta WHERE user_id = $result->post_author AND meta_key = 'dokan_profile_settings'");
                $author_mata = $wpdb->get_results($query);
                
                $product = $query = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}posts WHERE ID = %d AND post_type = 'product'", $meta_value['name']));
                $detail_url = home_url() . '/product/' . $product[0]->post_name;
        ?>
        <div class = "col-lg-2">
                        <div class="bs-component">
                            <div class="card mb-3">
                                <h3 class="card-header"><?php echo $result->post_title ?></h3>
                                <img class="ads-image-deal" style="width:100%" src=<?php echo $meta_value['image_url'] ?> >
                                <div class="card-body">
                                    <p class="card-text"><?php echo $meta_value['discription'] ?></p>
                                    <a href=<?php echo $detail_url?>>More Detail...</a>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item" value=<?php echo $result->post_author; ?>>Vendor : <?php echo unserialize($author_mata[0]->meta_value)['store_name'] ?></li>
                                    <li class="list-group-item">Discount(%) : <?php echo $meta_value['discount']  ?></li>
                                    <li class="list-group-item">Regular Price : <?php echo '$' . ($product_regular_price === NULL ? '0' : $product_regular_price) ?></li>
                                    <li class="list-group-item">Sale Price : <?php echo '$' . ($product_sale_price === NULL ? '0' : $product_sale_price) ?></li>
                                    <li class="list-group-item c-d-flex">Send comment to WWMalls Inc &nbsp;&nbsp;&nbsp; <span class="email_send_btn"><i class="fa-solid fa-paper-plane"></i></span></li>
                                </ul>
                            </div>
                        </div>
            </div>
        <?php 
            }
        ?>
        <div class="nav-bottom">
                        <div class="nav-pages">
                        <?php 
                        echo '<span class="displaying-num">' . $totalrows .' Posts</span>'; 
                        echo '<span class="pagination-links">';
                            if ($page==1){
                                echo '<span aria-hidden="true" class="page-round disabled" style = "margin:0px 5px">«</span>';
                                echo '<span aria-hidden="true" class="page-round disabled" style = "margin:0px 5px">‹</span>';
                            }else{
                                echo '<a href="' . add_query_arg('paged', 1) . '" style = "margin:0px 5px">';
                                echo '<span aria-hidden="true" class="page-round">«</span>';
                                echo '</a>';
                                echo '<a href="' . add_query_arg('paged', $page-1) . '" style = "margin:0px 5px">';
                                echo '<span aria-hidden="true" class="page-round">‹</span>';
                                echo '</a>';
                            }
                            echo '<span class="paging-input">';
                            echo '<span class="tablenav-paging-text">' . $page . ' of <span class="total-pages">' .  $totalpages  . '</span></span>';
                            echo '</span>';
                        ?>
                        <?php 
                            if ($page==$totalpages){
                                echo '<span aria-hidden="true" class="page-round disabled" style = "margin:0px 5px">›</span>';
                                echo '<span aria-hidden="true" class="page-round disabled" style = "margin:0px 5px">»</span>';
                            }else{
                                echo '<a href="' . add_query_arg('paged', $page + 1) . '" style = "margin:0px 5px">';
                                echo '<span aria-hidden="true" class="page-round" >›</span>';
                                echo '</a>';
                                echo '<a href="' . add_query_arg('paged', $totalpages) . '" style = "margin:0px 5px">';
                                echo '<span aria-hidden="true" class="page-round">»</span>';
                                echo '</a>';
                            }
                        ?>
                        </span>
                     </div>
                    </div>        
        <?php 
        }
        else{
            echo '<div style="height:450px;padding: 100px 30px;">
                    <h1>Sorry there are no deals currently available based on your criteria. Please check back again later. <br>However, if you wish
                    to be notified when there is a deal on the criteria you selected, please <span id="send-comment-search">subscribe</span> here.</h1>
                <div>';
        }
        ?>
    </div>
</div>
<style>
    .toast-error{
        background-color:#bc5350!important;
    }
    .toast-success{
        background-color:#026F72!important;
    }
</style>
<script>
    // SearchPanel Action
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('searchButton').addEventListener('click', function() {
                var paramInput = document.getElementById('post-search-input');
                var paramBy = document.getElementById('badge_name');
                var paramValue = paramInput.value;
                var paramValueBy = paramBy.value;
                if(paramInput.value !==""){
                    var newUrl = 'https://wwmalls.com/deal-page?' + "search=" + paramInput.value + "&searchBy=" + paramBy.value;
                    window.location.href= newUrl;
                }
            });
        document.getElementById('showAll').addEventListener('click', function() {
                var newUrl = 'https://wwmalls.com/deal-page';
                window.location.href= newUrl;
            });
    });
</script>

<?php the_content(); ?>
<?php get_footer(); ?>