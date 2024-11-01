<?php

/**
 * Product List Page
 * 
 * The html markup for the product list
 * 
 * @package wwt Translate
 * @since 1.0.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// Load WP_List_Table if not loaded
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Wwt_Tran_Product_Log_List extends WP_List_Table {

	public $model, $per_page;

	public function __construct() {

        global $wwt_tran_model, $page;

        // Set parent defaults
        parent::__construct( array(
							            'singular'  => 'product_log',	//singular name of the listed records
							            'plural'    => 'product_log',	//plural name of the listed records
							            'ajax'      => false		//does this table support ajax?
							        ) );

		$this->model	= $wwt_tran_model;
		$this->per_page	= apply_filters( 'wwt_tran_product_log_posts_per_page', 20 ); // Per page
    }

    /**
     * Function to overwrite the search box output
     * 
     * @package wwt Translate
	 * @since 1.0.0
     */
    /*public function search_box( $text, $input_id ) {
    	
    	if ( empty( $_REQUEST['s'] ) && !$this->has_items() )
			return;
		
    }*/

    /**
     * Display Columns
     * 
     * Handles which columns to show in table
     * 
	 * @package wwt Translate
	 * @since 1.0.0
     */
	public function get_columns() {

        $columns = array(
	        					//'cb'      		=> '<input type="checkbox" />',
	        					'title'	=> __( 'Title', WWT_TRAN_TEXT_DOMAIN ),
					            'pricing_type'	=> __( 'Pricing Rule On', WWT_TRAN_TEXT_DOMAIN ),
					            'percent_price'		=> __( 'Price (percentage)', WWT_TRAN_TEXT_DOMAIN ),
					            'flat_price'		=> __( 'Extra Cost', WWT_TRAN_TEXT_DOMAIN ),
					        );

		return apply_filters( 'wwt_tran_product_log_table_columns', $columns );
    }

    /**
     * Sortable Columns
     * 
     * Handles soratable columns of the table
     * 
	 * @package wwt Translate
	 * @since 1.0.0
     */
	public function get_sortable_columns() {
		
        $sortable_columns = array(
        								'title'	=> array( 'title', true ),
							            'pricing_type'	=> array( 'pricing_type', true ),
							            'percent_price'		=> array( 'percent_price', true ),
							            'flat_price'		=> array( 'flat_price', true ),
							        );
		
        return apply_filters( 'wwt_tran_product_log_sortable_columns', $sortable_columns );
    }

    /**
	 * Mange column data
	 * 
	 * Default Column for listing table
	 * 
	 * @package wwt Translate
	 * @since 1.0.0
	 */
	public function column_default( $item, $column_name ) {

        switch( $column_name ) {
           /* case 'ID':
            		return $item['ID'];*/
            case 'title':
            		return $item['title'];
            case 'pricing_type':
            		return ucfirst($item['pricing_type']);
            case 'percent_price' :
            		return $item['percent_price'];
            case 'flat_price' :
            		return $item['flat_price'];
			default:
				return print_r( $item, true );
        }
    }
    
    /**
	 * Render the checkbox column
	 * 
	 * @package wwt Translate
	 * @since 1.0.0
	 */
    public function column_cb( $item ) {
    	
    	$checkbox_html	= '<input type="checkbox" name="%1$s[]" value="%2$s" />';
        return sprintf( $checkbox_html, $this->_args['singular'], $item['ID'] );
    }
    
	/**
	 * Manage Edit/Delete Link
	 * 
	 * Does to show the edit and delete link below the column cell
	 * function name should be column_{field name}
	 * 
	 * @package wwt Translate
	 * @since 1.0.0
	 */
	public function column_title( $item ) {

		// Build row actions
		$actions = array(
			//'edit'      => sprintf('<a href="?page=%s&action=%s&product_id=%s">'.__('Edit', WWT_TRAN_TEXT_DOMAIN).'</a>','wwt-tran-adjust-price','edit',$item['ID']),
			'delete'    => sprintf('<a href="?page=%s&action=%s&product=%s">'.__('Delete', WWT_TRAN_TEXT_DOMAIN).'</a>',$_REQUEST['page'],'delete',$item['ID']),
		);
		
		// Return the title contents
		return sprintf( '%1$s %2$s', $item['title'], $this->row_actions( $actions ) );
	}
    
    /**
     * Bulk actions field
     * 
     * Handles Bulk Action combo box values
     * 
	 * @package wwt Translate
	 * @since 1.0.0
     */
	/*public function get_bulk_actions() {

		// Bulk action combo box parameter
        $actions	= array( 'delete' => 'Delete' );
		
		return apply_filters( 'wwt_tran_product_log_table_bulk_actions', $actions );
    }*/

    /**
	 * Process the bulk actions
	 * 
	 * @package wwt Translate
	 * @since 1.0.0
	 */
    public function process_bulk_action() {

        // Detect when a bulk action is being triggered...
        if( 'delete'===$this->current_action() ) {
        	wp_die(__( 'Items deleted (or they would be if we had items to delete)!', WWT_TRAN_TEXT_DOMAIN ));
        }
    }
    
    /**
	 * Display message when there is no items
	 * 
	 * @package wwt Translate
	 * @since 1.0.0
	 */
    public function no_items() {
		// Message to show when no records in database table
		_e( 'No products log found.', WWT_TRAN_TEXT_DOMAIN );
	}

    /**
	 * Displaying Prodcuts
	 * 
	 * Does prepare the data for displaying the products in the table.
	 * 
	 * @package wwt Translate
	 * @since 1.0.0
	 */
	public function display_products() {

		$prefix 	= WWT_TRAN_PREFIX;
		$resultdata = array();

		// Taking parameter
		$orderby 	= isset( $_GET['orderby'] )	? urldecode( $_GET['orderby'] )		: 'ID';
		$order		= isset( $_GET['order'] )	? $_GET['order']                	: 'DESC';
		$search 	= isset( $_GET['s'] ) 		? sanitize_text_field( trim($_GET['s']) )	: null;

		$args = array(
						'posts_per_page'	=> $this->per_page,
						'page'				=> isset( $_GET['paged'] ) ? $_GET['paged'] : null,
						'orderby'			=> $orderby,
						'order'				=> $order,
						'offset'  			=> ( $this->get_pagenum() - 1 ) * $this->per_page
					);

		// Function to retrive data
		$data = $this->model->wwt_tran_product_log_data( $args );

		if( !empty($data['data']) ) {

			// Re generate data
			foreach ($data['data'] as $key => $value) {
				$resultdata[$key]['ID'] 			= $value['ID'];
				$resultdata[$key]['pricing_type'] 	= get_post_meta($value['ID'],$prefix.'pricing_option',true);
				$resultdata[$key]['title'] 	= get_post_meta($value['ID'],$prefix.'title',true);
				$resultdata[$key]['percent_price'] 	= get_post_meta($value['ID'],$prefix.'price_percent',true);
				$resultdata[$key]['flat_price'] 	= get_post_meta($value['ID'],$prefix.'price_flat',true);
			}
		}

		$result_arr['data']		= !empty($resultdata) 	? $resultdata 		: array();
		$result_arr['total'] 	= isset($data['total']) ? $data['total'] 	: ''; // Total no of data

		return $result_arr;
	}
	
	/**
	 * Setup the final data for the table
	 * 
	 * @package wwt Translate
	 * @since 1.0.0
	 */
	public function prepare_items() {
        
        // Get how many records per page to show
        $per_page	= $this->per_page;
        
        // Get All, Hidden, Sortable columns
        $columns	= $this->get_columns();
        $hidden		= array();
		$sortable	= $this->get_sortable_columns();
        
		// Get final column header
		$this->_column_headers	= array( $columns, $hidden );
        
		// Proces bulk action
		//$this->process_bulk_action();
        
		// Get Data of particular page
		$data_res 	= $this->display_products();
		$data 		= $data_res['data'];
       	
		// Get current page number
		$current_page	= $this->get_pagenum();
		
		// Get total count
        $total_items	= $data_res['total'];
		
        // Get page items
        $this->items	= $data;
        
		// We also have to register our pagination options & calculations.
		$this->set_pagination_args( array(
										'total_items' => $total_items,
										'per_page'    => $per_page,
										'total_pages' => ceil($total_items/$per_page)
									) );
    }
}

// Create an instance of our package class...
$ProductListTable = new Wwt_Tran_Product_Log_List();

// Fetch, prepare, sort, and filter our data...
$ProductListTable->prepare_items();

// Creating page link
$manage_product_page = add_query_arg( array( 'page' => 'ww_spt_add_products' ), admin_url( 'admin.php' ) );
?>

<!-- List Table Output Starts Here -->
<div class="wrap ww-spt-wrap">
    <h2><?php 
    	_e( 'Rules', WWT_TRAN_TEXT_DOMAIN ); ?>
    </h2><?php

    	if( isset($_GET['message']) && !empty($_GET['message'] ) ) { //check message
			if($_GET['message'] == '3') { // Check delete message
				$msg = __("Product deleted Successfully.",WWT_TRAN_TEXT_DOMAIN);
			} 
		}

		// Displaying message
		if( !empty($msg) ) {
			$html = '<div class="updated" id="message">
						<p><strong>'.$msg.'</strong></p>
					</div>';
			echo $html;
		}
	?>

    <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
    <form id="product-filter" method="get">
        
    	<!-- For plugins, we also need to ensure that the form posts back to our current page -->
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
        
        <?php //$ProductListTable->views() ?>
        
        <!-- Search Title -->        
        <!-- Now we can render the completed list table -->
        <?php $ProductListTable->display() ?>
        
    </form>
	
</div><!-- end .ww-spt-wrap -->
<!-- List Table Output Ends Here -->