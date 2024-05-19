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
    // Select elements with the class "product-advertisement-td" and hide them
    $('.product-advertisement-td').hide();
    $('.product-advertisement-th').hide();
    // On vendor dashboard page->advertisement, when the category menu is changed, the read products for each category from database. 
  $('#product-category').on('change', function() {
    var category_id = $(this).val();
    $.ajax({
      url: 'https://wwmalls.com/wp-admin/admin-ajax.php',
      type: 'post',
      data: {
          action: 'handle_products_by_category', // Action to be handled by backend
          category_id: category_id // Pass selected category ID as data
      },
      success: function(response) {
          $('#product-name').html(response); // Update product data area with fetched data
      },
      error: function(xhr, status, error) {
          console.error(xhr.responseText); // Log error to console
      }
    });
  });
});