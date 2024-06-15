// On Admin Page -> Non-compliant Page ->Tree View Data
const toasts = new Toasts({
    offsetX: 20, // 20px
    offsetY: 20, // 20px
    gap: 20, // The gap size in pixels between toasts
    width: 300, // 300px
    timing: 'ease', // See list of available CSS transition timings
    duration: '.5s', // Transition duration
    dimOld: true, // Dim old notifications while the newest notification stays highlighted
    position: 'top-right' // top-left | top-center | top-right | bottom-left | bottom-center | bottom-right
});
class StatusResultRenderer {
    init(params){
        let icon = document.createElement("img");
        icon.src = `https://www.ag-grid.com/example-assets/icons/${
          params.value == "on" ? "tick-in-circle" : "cross-in-circle"
        }.png`;
        icon.setAttribute("class", "missionIcon");
    
        this.eGui = document.createElement("span");
        this.eGui.setAttribute("class", "missionSpan");
        this.eGui.appendChild(icon);
    }
    getGui(){
        return this.eGui;
    }
    refresh(params){
        return false;
    }
}
let gridApi;
let selectedCatId;
const ragCellClassRules = {
              "rag-green": (params) => params.value === true,
            };
const gridOptions = {
    rowData: [ ],
    columnDefs: [
        { 
            field: "id", 
            checkboxSelection: true, 
            headerCheckboxSelection: true, 
            headerCheckboxSelectionFilteredOnly: true, 
            headerCheckboxSelectionCurrentPageOnly:true,
            maxWidth:150
        },
        { field: "name", minWidth: 400,},
        { field: "sku" },
        { headerName: "Sale Price(CAD)", field: "sale_price", filter: "agNumberColumnFilter" },
        { headerName: "Regular Price(CAD)", field: "regular_price", filter: "agNumberColumnFilter" },
        { headerName: "Fee Rate(%)", field: "fee_rate", filter: "agNumberColumnFilter" },
        { field: "status", maxWidth:150, cellRenderer: StatusResultRenderer,},
    ],
    defaultColDef: {
        filter: "agTextColumnFilter",
        floatingFilter: true,
        flex: 1,
    },
    rowClassRules: {
        // apply red to Ford cars
        // "rag-red": (params) => params.data.make === "Ford",
    },
    rowSelection: "multiple",
    suppressRowClickSelection: true,
    pagination: true,
    paginationPageSize: 25,
    cacheBlockSize : 10,
    paginationPageSizeSelector: [10, 25, 50],
    rowModelType: 'clientSide',
    getRowId: (params) => {return params.data.id;},
    onSelectionChanged: updateSelectedRowsCount,
    isRowSelectable: function(node) {
        return node.data.sale_price > 0;
    }
};
function updateSelectedRowsCount() {
    const selectedRows = gridApi.getSelectedNodes();
    document.getElementById('selectedRowsCount').innerText = selectedRows.length;
}

function loadData($, data){
    gridApi.showLoadingOverlay();
    $.ajax({
        url: ajax_object.get_nonc_products_by_cat_endpoint,
        method: 'POST',
        beforeSend: function(xhr) {
            xhr.setRequestHeader('X-WP-Nonce', ajax_object.nonce);
        },
        data: {
            catID: data.instance.get_node(data.selected[0]).id
        },
        success: function(response) {
            if (response.message == 'success') {
                gridApi.updateGridOptions({rowData: response.data});
                gridApi.hideOverlay();
            } else {
                alert('Failed to fetch options.');
            }
        },
        error: function(xhr) {
            alert('An error occurred: ' + xhr.responseJSON.message);
        }
    });
}

jQuery(document).ready(function($){
    var currentUrl = window.location.href;
    if(currentUrl.includes('non-compliant-products')){
        var gridDiv = document.querySelector("#data_table_for_tree");
        gridApi = agGrid.createGrid(gridDiv, gridOptions);
    }
    
    $('#sites-selector-top').on('change', function() {
        var selectedSiteValue = $(this).val();
        $.ajax({
            url: ajax_object.get_nonc_categories_endpoint,
            method: 'POST',
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-WP-Nonce', ajax_object.nonce);
            },
            data: {
                siteID: selectedSiteValue
            },
            success: function(response) {
                if (response.message == 'success') {
                    build_tree_view_select(response.data, $);
                } else {
                    alert('Failed to fetch options.');
                }
            },
            error: function(xhr) {
                alert('An error occurred: ' + xhr.responseJSON.message);
            }
        });
    });
    
    function build_tree_view_select(categories, $){
        const category_datas = [];
        categories.forEach((category) => {
            const category_data={};
            category_data.id = String(category.id);
            if (category.parent_id === '0'){
                category_data.parent = "#";
            }else{
                category_data.parent = String(category.parent_id);
            }
            category_data.text = category.name;
            category_datas.push(category_data);
        });
        
        $('#treeview')
    		.on("changed.jstree", function (e, data) {
    			if(data.selected.length) {
                    loadData($, data);   
                    selectedCatId = data.instance.get_node(data.selected[0]).id;
    			}
    		})
    		.jstree({
    			'core' : {
    				'multiple' : false,
    				'data' : category_datas
    			},
    			"types" : {
    			    "#" : {
                      "max_children" : 10,
                      "max_depth" : 10,
                      "valid_children" : ["root"]
                    },
    			},
    			'plugins':[
    			    "contextmenu", 
    			    "dnd", 
    			    "search", 
    			    "state",
    			    "types", 
    			    "wholerow"
    			],
    		});
    	$('#treeview').jstree(true).settings.core.data = category_datas;
    	$('#treeview').jstree(true).refresh();
    }
    
    var to = false;
    $('#search').keyup(function () {
        if(to) { clearTimeout(to); }
        to = setTimeout(function () {
            var v = $('#search').val();
            $('#treeview').jstree(true).search(v);
        }, 350);
    });
    
    $('#enable_btn').click(function() {
        const selectedNodes = gridApi.getSelectedNodes();
        const selectedIds = selectedNodes.map(node => node.data.id);

        const feeRate = $('#fee-rate').val();
        if (selectedIds.length > 0 ){
            gridApi.showLoadingOverlay();
            $.ajax({
                url: ajax_object.update_nonc_products_endpoint,
                method: 'POST',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', ajax_object.nonce);
                },
                data: {
                    extProductIds: selectedIds,
                    feeRate: feeRate,
                    catID:selectedCatId
                },
                success: function(response) {
                    if (response.message == 'success') {
                        console.log(response);
                        const updateTransaction = {
                            update:response.data
                        };
                        gridApi.applyTransaction(updateTransaction);
                        gridApi.deselectAll();
                        gridApi.hideOverlay();
                    } else {
                        alert('Failed to fetch options.');
                    }
                },
                error: function(xhr) {
                    alert('An error occurred: ' + xhr.responseJSON.message);
                }
            });
        }else{
            toasts.push({
                title: 'Error',
                content: 'Please select one or more rows.',
                style: 'error',
                dismissAfter: '3s'
            });
        }
    });
    
    $('#disable_btn').click(function() {
        const selectedNodes = gridApi.getSelectedNodes();
        const selectedIds = selectedNodes.map(node => node.data.id);
        if (selectedIds.length > 0){
            gridApi.showLoadingOverlay();
            $.ajax({
                url: 'https://wwmalls.com/wp-admin/admin-ajax.php',
                type: 'post',
                data: {
                    action: 'handle_disable_nonc_products', 
                    extProductIds: selectedIds,
                    catID:selectedCatId
                },
                success: function(response) {
                    // gridApi.updateGridOptions({rowData: response.data});
                    const updateTransaction = {
                        update:response.data
                    };
                    gridApi.applyTransaction(updateTransaction);
                    gridApi.deselectAll();
                    gridApi.hideOverlay();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText); // Log error to console
                }
            });
        }else {
            toasts.push({
                title: 'Error',
                content: 'Please select one or more rows.',
                style: 'error',
                dismissAfter: '3s'
            });
        }
    });
    
    $('#nonc-remove-btn').click(function() {
        $.ajax({
            url: ajax_object.remove_duplicated_products_endpoint,
            method: 'POST',
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-WP-Nonce', ajax_object.nonce);
            },
            data: {
                description: 'This is ajax for removing products!'
            },
            success: function(response) {
                alert(response.message);
                console.log(response.data);
                
            },
            error: function(xhr) {
                alert('An error occurred: ' + xhr.responseJSON.message);
            }
        });
    });
    $('#nonc-remove-all-btn').click(function() {
        $.ajax({
            url: ajax_object.remove_all_products_endpoint,
            method: 'POST',
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-WP-Nonce', ajax_object.nonce);
            },
            data: {
                description: 'This is ajax for removing all products from WWmalls!'
            },
            success: function(response) {
                console.log(response.data);
            },
            error: function(xhr) {
                alert('An error occurred: ' + xhr.responseJSON.message);
            }
        });
    });
    
    $('#reset-all').click(function(){
        $.ajax({
            url: ajax_object.reset_all_nonc_endpoint,
            method: 'POST',
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-WP-Nonce', ajax_object.nonce);
            },
            data: {
                description: 'This is ajax for resetting all!'
            },
            success: function(response) {
            },
            error: function(xhr) {
                alert('An error occurred: ' + xhr.responseJSON.message);
            }
        });
    });
    
});