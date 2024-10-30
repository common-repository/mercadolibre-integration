<?php


if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}


class TT_MLInt_List_Table extends WP_List_Table {
    

	var $options = array();
    function __construct($optionsparam){
        global $status, $page;
		$this->options = $optionsparam;
        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'id',     //singular name of the listed records
            'plural'    => 'ids',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ) );
        
    }
  
    function column_default($item, $column_name){
		return $item[$column_name];

    }
    

    function column_id($item){
        
        //Build row actions
        $actions = array(
            'edit'      => sprintf('<a href="?page=%s&action=%s&id=%s">Edit</a>',$_REQUEST['page'],'edit',$item['id']),
            'delete'    => sprintf('<a href="?page=%s&action=%s&id=%s&amp;updated=true">Delete</a>',$_REQUEST['page'],'delete',$item['id']),
        );
        
        //Return the user contents
        return sprintf('%1$s %2$s',
            /*$1%s*/ $item['id'],
            /*$2%s*/ $this->row_actions($actions)
        );
    }
    

    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/ $item['id']                //The value of the checkbox should be the record's id
        );
    }
    
    function get_columns(){
        $columns = array(
            'cb'        => '<input type="checkbox" />', //Render a checkbox instead of text
			'id'     => 'Name',
            'user'     => 'User',
            'columns'    => 'Columns',
			'bypage'    => 'Articles per page',
			'featured'    => 'Featured articles',
			'imgfeatured'    => 'Image'
        );
        return $columns;
    }
    
    function get_sortable_columns() {
        $sortable_columns = array(
			'id'     => array('id',true),
            'user'     => array('user',true),
            'columns'    => array('columns',false),
			'bypage'    => array('bypage',false),
			'featured'    => array('featured',false),
			'imgfeatured'    => array('imgfeatured',false)
        );
        return $sortable_columns;
    }
    

    function get_bulk_actions() {
        $actions = array(
            //'delete'    => 'Delete'
        );
        return $actions;
    }
    

    function process_bulk_action() {
        
        //Detect when a bulk action is being triggered...
        if( 'delete'===$this->current_action() ) {
            //wp_die('Items deleted (or they would be if we had items to delete)!');
        }
        
    }
    

    function prepare_items() {
        global $wpdb; //This is used only if making any database queries

        $per_page = 10;       

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();    

        $this->_column_headers = array($columns, $hidden, $sortable);
        
        $this->process_bulk_action();
         
        $data = $this->options;
                
        function usort_reorder($a,$b){
            $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'user'; //If no sort, default to title
            $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'asc'; //If no order, default to asc
            $result = strcmp($a[$orderby], $b[$orderby]); //Determine sort order
            return ($order==='asc') ? $result : -$result; //Send final sort direction to usort
        }
        usort($data, 'usort_reorder');

        $current_page = $this->get_pagenum();
        $total_items = count($data);
        $data = array_slice($data,(($current_page-1)*$per_page),$per_page);
        $this->items = $data;
        
        $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
        ) );
    }
    
}

function tt_render_list_page($options){
    
    //Create an instance of our package class...
    $testListTable = new TT_MLInt_List_Table($options);
    //Fetch, prepare, sort, and filter our data...
    $testListTable->prepare_items();
    
    ?>
    <div class="wrap">
        
        <div id="icon-users" class="icon32"><br/></div>
        <h2>Catalog List

			<input type="button" name="newcatalog" class="button-secondary" value="<?php _e('Add New', 'mercadolibre_integration') ?>" onclick="location.href='<?php echo sprintf('?page=%s&action=%s',$_REQUEST['page'],'new') ?>'" />

		</h2>
        
        <form id="movies-filter" method="get">

            <!-- For plugins, we also need to ensure that the form posts back to our current page -->
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
            <!-- Now we can render the completed list table -->
            <?php $testListTable->display() ?>

        </form>
		
        
    </div>
<?php
}
?>