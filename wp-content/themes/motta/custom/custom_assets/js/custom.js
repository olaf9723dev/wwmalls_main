function openTab(evt, tabName) {
    var i, tabcontent, tablinks;

    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}

function previewImage(event) {
      const input = event.target;
      const preview = document.getElementById('preview-container');
      const file = input.files[0];

      if (file) {
        const reader = new FileReader();
        reader.onload = function() {
          const img = document.createElement('img');
          img.src = reader.result;
          img.id = 'preview-image';
          img.classList.add('preview-image');
          preview.innerHTML = '';
          preview.appendChild(img);
        }
        reader.readAsDataURL(file);
      } else {
        preview.innerHTML = '';
      }
}

jQuery(document).ready(function($) {
    $('.dokan-proudct-advertisement').hide();
    var categories=[];
    $("#product-category option").each(function() {
        var category = {};
        category.id = $(this).attr("value");
        category.text = $(this).text();
        categories.push(category);
    });
    $('#product-category').on('input', function(){
        var productValue = $(this).val().toLowerCase();
        function searchProducts(arr, key, value){
            return $.grep(arr, function(obj){
                return obj[key].toLowerCase().includes(value);
            });
        }
        var relatedProducts = searchProducts(categories, 'text', productValue);
        $('#products-dropdown-menu').empty();
        
        relatedProducts.forEach(function(item){
            var listItem = $('<li>').text(item.text);
            listItem.val(item.id);
            $('#products-dropdown-menu').append(listItem);
        });
        
        if (relatedProducts.length > 0) {
            $('#products-dropdown-menu').show();
        } else {
            $('#products-dropdown-menu').hide();
        }
        
        $(document).on('click', function(event) {
            if (!$(event.target).closest('#product-category, #products-dropdown-menu').length) {
                $('#products-dropdown-menu').hide(); // Hide the dropdown
            }
        });
        // On vendor dashboard page->advertisement, when the category menu is changed, the read products for each category from database. 
        $('#products-dropdown-menu li').click(function(){
            $('#product-category').val($(this).text());
            $('#products-dropdown-menu').hide();
            
            var user_id = valueData.userID;
            var category_id = $(this).attr("value");
            product_name_spin.css("display", "block");
            $.ajax({
              url: 'https://wwmalls.com/wp-admin/admin-ajax.php',
              type: 'post',
              data: {
                  action: 'handle_products_by_category', // Action to be handled by backend
                  category_id: category_id, // Pass selected category ID as data
                  user_id: user_id
              },
              success: function(response) {
                  product_name_spin.css("display", "none");
                  $('#product-name').html(response); // Update product data area with fetched data
                  
              },
              error: function(xhr, status, error) {
                  console.error(xhr.responseText); // Log error to console
              }
            });    
        });
    });
    
    // Set initial value of vendor advertisement dashboard
    var product_name_spin = $('.product_name_sticker');
    product_name_spin.css("display", "none");
    
    var r_price_sticker = $('.r_price_sticker');
    r_price_sticker.css("display", "none");
    
    $('#period').val(2);
    
    // Select elements with the class "product-advertisement-td" and hide them
    $('.product-advertisement-td').hide();
    $('.product-advertisement-th').hide();
    
    // On vendor dashboard page->advertisement, when the category menu is changed, the read products for each category from database. (Origin using Select Element)
    // $('#product-category').on('change', function() {
    //     var user_id = valueData.userID;
    //     var category_id = $(this).val();
    //     product_name_spin.css("display", "block");
    //     $.ajax({
    //       url: 'https://wwmalls.com/wp-admin/admin-ajax.php',
    //       type: 'post',
    //       data: {
    //           action: 'handle_products_by_category', // Action to be handled by backend
    //           category_id: category_id, // Pass selected category ID as data
    //           user_id: user_id
    //       },
    //       success: function(response) {
    //           product_name_spin.css("display", "none");
    //           $('#product-name').html(response); // Update product data area with fetched data
              
    //       },
    //       error: function(xhr, status, error) {
    //           console.error(xhr.responseText); // Log error to console
    //       }
    //     });
    // });
    
    
    $('#product-name').on('change', function() {
        r_price_sticker.css("display", "block");
        var product_id = $(this).val();
        $.ajax({
          url: 'https://wwmalls.com/wp-admin/admin-ajax.php',
          type: 'post',
          data: {
              action: 'handle_product_info', // Action to be handled by backend
              product_id: product_id, // Pass selected category ID as data
          },
          success: function(response) {
                r_price_sticker.css("display", "none");
              $('#r-price').val(JSON.parse(response)[0].meta_value); // Update product data area with fetched data
          },
          error: function(xhr, status, error) {
              console.error(xhr.responseText); // Log error to console
          }
        });        
    });
    
    $('#d-price').on('input', function(){
        var regular_price = parseFloat($('#r-price').val());
        var discount = parseFloat($(this).val());
        var term = parseFloat($('#period').val());
        var net_price = 0;
        
        var ads_def_fee = parseFloat(valueData.adsDef_Fee);
        var ads_per_fee = parseFloat(valueData.adsPer_Fee);
        
        net_price = Math.floor((regular_price * (100-discount) /100)*100)/100;
        $('#n-price').val(net_price);
        
        var payout = Math.floor(((ads_def_fee + ads_per_fee*(term-2)) * (100-discount)/100)*100)/100;
        $('#payout').val(payout);
    });
    $('#period').on('input', function(){
        var discount = parseFloat($(this).val());
        var term = parseFloat($('#period').val());
        var ads_def_fee = parseFloat(valueData.adsDef_Fee);
        var ads_per_fee = parseFloat(valueData.adsPer_Fee);
        var payout = Math.floor(((ads_def_fee + ads_per_fee*(term-2)) * (100-discount)/100)*100)/100;
        $('#payout').val(payout);
    });
});

jQuery(document).ready(function() {
    jQuery('.edit-btn').click(function() {
        var row = jQuery(this).closest('tr');
        var cells = row.find('td');
        cells.each(function() {
            if (jQuery(this).hasClass('dokan-ads-t')) {
              var value = jQuery(this).text();
              var input = jQuery('<input>').attr({
                type: 'text',
                value: value,
                class: 'dokan-ads-t-v'
              }).addClass('editable-input');
              jQuery(this).text('').append(input);
            }
        });
    });
    jQuery('.save-btn').click(function() {
        var row = jQuery(this).closest('tr');
        var ads_title = row.find('.dokan-ads-t-v').val();
        if(ads_title){
            alert(ads_title);
        }else{
            alert('die');
        }
    });
});

jQuery(document).ready(function() {
    jQuery("#bulk_delete_ads").click(function(e) {
        var checkboxes = document.querySelectorAll('.subCheckbox');
        var checked = false;
        checkboxes.forEach(function(checkbox) {
            if (checkbox.checked) {
                checked = true;
            }
        });
        if (!checked) {
            event.preventDefault();
            toastr.error('No one is selected!');
        }
        else{
            e.preventDefault(); // Prevent default form submission
            jQuery("#delete-ads-modal").modal({
                fadeDuration: 500
            });
            jQuery(".jquery-modal").css("z-index", "1000");
        }
    });
    jQuery("#delete-ads-modal #yesBtn").click(function(e) {
        jQuery.modal.close();
        var selectedCheckboxes = [];
        jQuery("input[name='delete[]']:checked").each(function() {
            selectedCheckboxes.push(jQuery(this).val());
        });
        jQuery.ajax({
            type: 'post',
            url: "https://wwmalls.com/wp-admin/admin-ajax.php", // Path to your PHP file
            data: {
                action: 'handle_vendor_delete_ads',
                deletes: selectedCheckboxes,
                delete_ads:'yes'
            },
            success: function(response) {
                toastr.success('Deleted successfully!');
                location.reload();
                console.log(response); // Update product data area with fetched data
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText); // Log error to console
            }
        });
    });
    jQuery("#delete-ads-modal #noBtn").click(function() {
        jQuery.modal.close();
    });
});

jQuery(document).ready(function() {
    jQuery('.ads-image-deal').on('load', function() {
        var width =jQuery(this).width();
        jQuery(this).css('height', width);
    }).each(function() {
        if(this.complete) jQuery(this).trigger('load');
    });
    
    function setAspectRatio() {
        jQuery('.ads-image-deal').each(function() {
            var width = jQuery(this).width();
            jQuery(this).css('height', width);
        });
    }
    // setAspectRatio();

    jQuery(window).on('resize', function() {
        setAspectRatio();
    });
});

// On Admin Page-> dokan management advertisement->handle change status->ajax  
document.addEventListener('DOMContentLoaded', function() {
    var statusCheckboxes = document.querySelectorAll('#status');
    statusCheckboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            if (checkbox.checked) {
                var closestRow = this.closest('tr');
                var subCheckbox = closestRow.querySelector('.subCheckbox');
                var subCheckboxValue = subCheckbox.value;
                jQuery.ajax({
                    url: 'https://wwmalls.com/wp-admin/admin-ajax.php',
                    type: 'post',
                    data: {
                        action: 'handle_admin_ads_status',
                        status:'allowed',
                        post_id: subCheckboxValue
                    },
                    success: function(response) {
                        console.log(response); // Update product data area with fetched data
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText); // Log error to console
                    }
                });
            } else {
                var closestRow = this.closest('tr');
                var subCheckbox = closestRow.querySelector('.subCheckbox');
                var subCheckboxValue = subCheckbox.value;
                jQuery.ajax({
                    url: 'https://wwmalls.com/wp-admin/admin-ajax.php',
                    type: 'post',
                    data: {
                        action: 'handle_admin_ads_status',
                        status:'pending',
                        post_id: subCheckboxValue
                    },
                    success: function(response) {
                        console.log(response); // Update product data area with fetched data
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText); // Log error to console
                    }
                });
            }
        });
    });
});
jQuery(document).ready(function() {
    jQuery('#update-fee-btn').on('click', function() {
        var new_def_fee = jQuery('#ads-def-fee-input').val();
        var new_per_fee = jQuery('#ads-per-fee-input').val();
        if(new_def_fee != '' && new_per_fee != '' ){
            jQuery.ajax({
                type: 'post',
                url: "https://wwmalls.com/wp-admin/admin-ajax.php", // Path to your PHP file
                data: {
                    action: 'handle_update_ads_fee',
                    new_def_fee: new_def_fee,
                    new_per_fee: new_per_fee
                },
                success: function(response) {
                    toastr.success('Updated successfully!');
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText); // Log error to console
                }
            });
        }else{
            toastr.error('Please set the Value!');
        }

    });
  });

// Deal Page
jQuery(document).ready(function() {
    var urlParams = new URLSearchParams(window.location.search);
    var searchText = urlParams.get('search');
    var searchBy = urlParams.get('searchBy');
    jQuery("#post-search-input").val(searchText);
    jQuery("#badge_name").val(searchBy?searchBy:0);    
    
    jQuery('.email_send_btn').click(function(e){
        var user_id = valueData.userID;
        if(user_id>0){
            jQuery("#comment-ads-modal").modal({
                fadeDuration: 500
            });
            jQuery(".jquery-modal").css("z-index", "1000");
        }else{
            toastr.error('Please sign in with your account');
        }
    });
    
    jQuery("#comment-ads-modal #yesBtn").click(function() {
        var message = jQuery('#ads-msg').val();
        var user_id = valueData.userID;
        jQuery.ajax({
            type: 'post',
            url: "https://wwmalls.com/wp-admin/admin-ajax.php", // Path to your PHP file
            data: {
                action: 'handle_send_ads_comment',
                message: message,
                user_id: user_id
            },
            success: function(response) {
                toastr.success('Email has been sent successfully!');
                location.reload();
                console.log(response); // Update product data area with fetched data
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText); // Log error to console
            }
        });
    });
    jQuery("#comment-ads-modal #noBtn").click(function() {
        jQuery.modal.close();
    });
    
    jQuery("#send-comment-search").click(function(){
        var user_id = valueData.userID;
        var searchText = jQuery("#post-search-input").val();
        var searchCat = jQuery("#badge_name").val();
        jQuery.ajax({
            type: 'post',
            url: "https://wwmalls.com/wp-admin/admin-ajax.php", // Path to your PHP file
            data: {
                action: 'handle_send_ads_subscribe',
                search_text: searchText,
                search_cat: searchCat,
                user_id: user_id
            },
            success: function(response) {
                console.log(response);
                if(response == 'yes'){
                    toastr.success('Subscription has been sent successfully!');
                }else{
                    toastr.error('There is not any product which is matched with Search Option');
                }
                location.reload();
                // window.location.href = "https://wwmalls.com/deal-page";
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText); // Log error to console
            }
        });
    });
    
});

// Find Businesss page
jQuery(document).ready(function($){
    var catergoryBox = $('.category-box ul');
    var storeCategories = catergoryBox.find('li').map(function() {
        return $(this).text().trim(); // Get the text of each descendant
    }).get();

    $('.category-input').css('display', 'none');
    $('.store-lists-other-filter-wrap').prepend('<div class="custom-store-list-input item"><input type="text" id="store-category" name="store-category" placeholder="Enter category name" required><ul id="cat-dropdown-menu" style="display: none;"></ul></div>');

    $('#store-category').on('input', function(){
        
        var currentValue = $(this).val().toLowerCase();
        
        var realtedCategories = storeCategories.filter(function(storeCategory){
            return storeCategory.toLowerCase().includes(currentValue);
        })
        
        $('#cat-dropdown-menu').empty();
        
        realtedCategories.forEach(function(item) {
            var listItem = $('<li>').text(item); // Create list item with text
            $('#cat-dropdown-menu').append(listItem); // Add to dropdown menu
        });
        
        if (realtedCategories.length > 0) {
            $('#cat-dropdown-menu').show();
        } else {
            $('#cat-dropdown-menu').hide();
        }
        
        $(document).on('click', function(event) {
            if (!$(event.target).closest('#store-category, #cat-dropdown-menu').length) {
                $('#cat-dropdown-menu').hide(); // Hide the dropdown
            }
        });
        
        $('#cat-dropdown-menu li').click(function(){
            $('#store-category').val($(this).text());
            $('#cat-dropdown-menu').hide();
            
            var selectedCategory = $(this).text().toLowerCase();
            
            $('.category-box ul li').filter(function(){
                var itemText = $(this).text().toLowerCase();
                if(itemText.includes(selectedCategory)){
                    if ($(this).hasClass('dokan-btn-theme')){
                        return false;
                    }else{
                        return true;
                    }
                }
            }).click();
            
            
            $('.category-box ul li').filter(function(){
                var itemText = $(this).text().toLowerCase();
                if(!itemText.includes(selectedCategory)){
                    if ($(this).hasClass('dokan-btn-theme')){
                        return true;       
                    }else{
                        return false;       
                    }
                }
            }).click();
            
        });
    });    
});

// Register Page
jQuery(document).ready(function($){
    var storeCategories=valueData.store_categories;
    $('.show_if_seller').children().eq(6).remove();
    $('.show_if_seller').children().eq(6).remove();
    $('.show_if_seller').children().eq(6).remove();
    $('.show_if_seller').children().eq(6).remove();
    
    // $('.show_if_seller').children().eq(2).after('<div class="register-custom-store form-row form-group form-row-wide" style="width:100%"><label for="store-category" class="pull-left">Vendor Category<span class="required">*</span></label><input type="text" class="input-text form-control" id="store-category" name="store-category" placeholder="Enter category name" required><ul id="cat-dropdown-menu" style="display: none;"></ul></div>');
    
    $('.show_if_seller').children().eq(7).after('<p class="term-link"><a href="https://wwmalls.com/wp-content/uploads/2024/01/terms.pdf" target="_blank" rel="noopener noreferrer">Please read Terms & Condition. Click here.</a></p>');
    
    // $('#store-category').on('input', function(){
        
    //     var currentValue = $(this).val().toLowerCase();
        
    //     var realtedCategories = storeCategories.filter(function(storeCategory){
    //         return storeCategory.toLowerCase().includes(currentValue);
    //     })
        
    //     $('#cat-dropdown-menu').empty();
        
    //     realtedCategories.forEach(function(item) {
    //         var listItem = $('<li>').text(item); // Create list item with text
    //         $('#cat-dropdown-menu').append(listItem); // Add to dropdown menu
    //     });
        
    //     if (realtedCategories.length > 0) {
    //         $('#cat-dropdown-menu').show();
    //     } else {
    //         $('#cat-dropdown-menu').hide();
    //     }
        
    //     $(document).on('click', function(event) {
    //         if (!$(event.target).closest('#store-category, #cat-dropdown-menu').length) {
    //             $('#cat-dropdown-menu').hide(); // Hide the dropdown
    //         }
    //     });
        
    //     $('#cat-dropdown-menu li').click(function(){
    //         $('#store-category').val($(this).text());
    //         $('#cat-dropdown-menu').hide();
    //     });
    // });
    
    
});

// Google Map editor

jQuery(document).ready(function($){
    var pathname = window.location.href;
    var flag = false;
    // Function to create a MutationObserver and observe the target node
        function observeDOM($targetNode, config, checkElement, flag) {
            // Create a new MutationObserver
            const observer = new MutationObserver(function (mutations) {
                mutations.forEach(function (mutation) {
                    if (mutation.type === 'childList') {
                        $(mutation.addedNodes).each(function (index, node) {
                            // Check if the node meets your specific condition
                            if (checkElement($(node))) {
                                if(pathname.includes('/store/') && !flag){
                                    $(node).hide();
                                }
                                if(pathname.includes('/store/') && flag){
                                    $(node).show();
                                }
                            }
                        });
                    }
                });
            });

            // Start observing the target node
            observer.observe($targetNode[0], config);

            return observer;
        }
        const target_2 = function ($element) {
            return $element.is(".gm-svpc");
        };
        const observer2 = observeDOM($("#dokan-store-location"), { childList: true, subtree: true }, target_2, flag);

    if(pathname.includes('/store/')){
        $('#dokan-store-location').addClass('dokan-dashboard');
    }
    // $('.gm-control-active.gm-fullscreen-control').click(function(){
    //     flag=!flag;
    //     console.log(flag);
    // });
});


// Store Setup Page
jQuery(document).ready(function($){
    $('.dokan-seller-setup-form > table > tbody').children().eq(6).remove();
});

jQuery(document).ready(function($){
    var pathname = window.location.href;
    if(pathname.includes('/store/')){
        $("head").append("<link rel='stylesheet' id='elementor-frontend-css' href='https://wwmalls.com/wp-content/plugins/elementor/assets/css/frontend.min.css?ver=3.21.4' type='text/css' media='all'>");
        $("head").append("<link rel='stylesheet' id='elementor-frontend-css' href='https://wwmalls.com/wp-content/plugins/elementor-pro/assets/css/frontend.min.css?ver=3.21.4' type='text/css' media='all'>");
    
        // Remove the CSS file from the HTML page
        $("link[href='https://wwmalls.com/wp-content/plugins/elementor/assets/css/frontend-lite.min.css?ver=3.21.4']").remove();
        $("link[href='https://wwmalls.com/wp-content/plugins/elementor-pro/assets/css/frontend-lite.min.css?ver=3.21.2']").remove();
        

        // Change the text of Vendor BioGraphy to About Us
        var elements = $("span:contains('Vendor Biography'), h2:contains('Vendor Biography')");
        elements.each(function() {
            $(this).text('About us');
        });
        
        var alert = $("p.dokan-info:contains('No products were found on this vendor!')").each(function(){
                $(this).text('This vendor has not yet made their products or services available through WWMalls. If you would like to shop at their store online, please ask them to contact us to upload their products so you can buy from them conveniently.')
            })
    }
});




// Page Loader JavaScript
jQuery(document).ready(function($){
    $('body').append('<div class="loader"><div class="loader-inner"><div class="loader-line-wrap"><div class="loader-line"></div></div><div class="loader-line-wrap"><div class="loader-line"></div></div><div class="loader-line-wrap"><div class="loader-line"></div></div><div class="loader-line-wrap"><div class="loader-line"></div></div><div class="loader-line-wrap"><div class="loader-line"></div></div></div></div>');
    function showModal() {
        $(".loader").show();
    }
    
    // Function to hide modal
    function hideModal() {
        $(".loader").hide();
    }
    $("form").on("submit", function() {
        showModal();
    });

    // Hide the loading modal when the page is refreshed
    $(window).on('beforeunload', function() {
        hideModal();
    });
    
    jQuery(document).ajaxStart(function($) {
        showModal();
    });
    
    // Hide modal when an AJAX request completes
    jQuery(document).ajaxStop(function($) {
        hideModal();
    });
});

jQuery(document).ready(function($){
    
});
