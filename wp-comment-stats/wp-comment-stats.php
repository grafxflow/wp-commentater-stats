<?php
/*
Plugin Name: WP Comment Stats
Plugin URI: https://grafxflow.co.uk/blog/content-management-systems/wp-comment-stats-plugin
Description: Shows the comments statistics breakdown plus a dashboard output
Version: 1.0.3
Author: jammy-to-go
Author URI: https://grafxflow.co.uk
*/

/* -------------------------------- REVISION HISTORY -----------------------------------

1.	Based on https://wordpress.org/plugins/comment-stats/ and updated to work with WordPress 4.9.7
	Added a dashboard widget which outputs
	i.		Comments in the past 10 years
	ii.		Comments in the past 12 months
	iii.	Comments in the past 7 days
--------------------------------------------------------------------------------------- */
?>
<?php
// Detect the WP Table class is available
if ( !class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class CS_WP_Table extends WP_List_Table {

    private $wpdb,
    $order,
    $orderby,
    $posts_per_page = 10;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        
        add_action('init', array($this, 'init' ));    
    }
    
    public function init() {
        add_action('admin_menu', array($this, 'comments_stats_admin_actions'));
        add_action('admin_enqueue_scripts', array($this, 'table_stats_css_include'));
    }
    
    public function table_stats_css_include() {
        wp_enqueue_style( 'table-stats-widget-styles', plugins_url( '', __FILE__ ) . '/css/plugin.css' );
    }
    
    public function comments_stats_admin_actions() {
        add_submenu_page( 'edit-comments.php', 'WP Comment Stats', 'WP Comment Stats', 'activate_plugins', 'commentstatslist', array($this, 'comments_stats_list') );
    }
    
    public function comments_stats_list() {
        include( plugin_dir_path( __FILE__ ) . 'views/table-stats.php');
    }

    private function get_sql_results() {
        
        if ( isset($_GET['orderby']) and $_GET['orderby'] ) {
            if ( $_GET['orderby'] == 'period' ) {
                $orderby = 'comment_date';
            } else {
                $orderby = $_GET['orderby'];
            }
        } else {
            $orderby = 'comment_date';
        }

        if ( isset( $_GET['order'] ) and $_GET['order'] ) {
            $order = $_GET['order'];
        } else {
            $order = 'DESC';
        }

        $query = $this->wpdb->get_results( "SELECT 
        date_format(comment_date, '%M, %Y') as period, 
        COUNT(*) as total,
        COUNT(DISTINCT(comment_post_ID)) as totalposts,
        COUNT(DISTINCT(comment_author)) as totalauthors,
        COUNT(DISTINCT(comment_author_email)) as totalemails,
        COUNT(DISTINCT(comment_author_url)) as totalurls,
        COUNT(DISTINCT(comment_author_IP)) as totalips
        FROM {$this->wpdb->comments}
        WHERE comment_approved = 1
        GROUP BY period
        ORDER BY ".$orderby." ".$order."
        " );

        return $query;
    }

    public function set_order() {
        
        if ( isset( $_GET['order'] ) and $_GET['order'] ) {
            $order = $_GET['order'];
        }
        
        $this->order = esc_sql( $order );
    }

    public function set_orderby() {
        
        if ( isset( $_GET['orderby'] ) and $_GET['orderby'] ){
            $orderby = $_GET['orderby']; 
        }

        $this->orderby = esc_sql( $orderby );
    }

    /**
     * @see WP_List_Table::ajax_user_can()
     */
    public function ajax_user_can() {
        return current_user_can( 'edit_posts' );
    }

    /**
     * @see WP_List_Table::no_items()
     */
    public function no_items() {
        _e( 'No comments found.' );
    }

    /**
     * @see WP_List_Table::get_views()
     */
    public function get_views() {
        return array();
    }

    /**
     * @see WP_List_Table::get_columns()
     */
    public function get_columns() {
        
        $columns = array(
            'period' => __( 'Period' ),
            'total' => __( 'Approved' ),
            'totalposts' => __( 'Posts Discussed' ),
            'totalauthors' => __( 'CS. Names' ),
            'totalemails' => __( 'CS. Emails' ),
            'totalurls' => __( 'CS. URLs' ),
            'totalips' => __( 'CS. IPs' ),
            'commented_posts' => __( 'Most Commented Post(s)' )
        );

        return $columns;
    }

    /**
     * @see WP_List_Table::get_sortable_columns()
     */
    public function get_sortable_columns() {

        $sortable = array(
            'period' => array( 'period', true ),
            'total' => array( 'total', true ),
            'totalposts' => array( 'totalposts', true ),
            'totalauthors' => array( 'totalauthors', true ),
            'totalemails' => array( 'totalemails', true ),
            'totalurls' => array( 'totalurls', true ),
            'totalips' => array( 'totalips', true ),
        );

        return $sortable;
    }

    /**
     * Prepare data for display
     * @see WP_List_Table::prepare_items()
     */
    public function prepare_items() {

        $columns = $this->get_columns();

        $hidden = array();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array(
            $columns,
            $hidden,
            $sortable
        );

        // SQL results
        $posts = $this->get_sql_results();

        empty( $posts ) and $posts = array();

        # >>>> Pagination
        $per_page = $this->posts_per_page;
        $current_page = $this->get_pagenum();
        $total_items = count( $posts );
        $this->set_pagination_args( array(
            'total_items' => $total_items,
            'per_page' => $per_page,
            'total_pages' => ceil( $total_items / $per_page )
        ) );
        
        $last_post = $current_page * $per_page;
        $first_post = $last_post - $per_page + 1;
        $last_post > $total_items and $last_post = $total_items;

        // Setup the range of keys/indizes that contain 
        // the posts on the currently displayed page(d).
        // Flip keys with values as the range outputs the range in the values.
        $range = array_flip( range( $first_post - 1, $last_post - 1, 1 ) );

        // Filter out the posts we're not displaying on the current page.
        $posts_array = array_intersect_key( $posts, $range );
        # <<<< Pagination
        // Prepare the data
        $permalink = __( 'Edit:' );
        
        foreach ( $posts_array as $key => $post ) {
            
            // Sort the actual post and results //
            $sql = $this->wpdb->prepare( "SELECT 
            {$this->wpdb->comments}.comment_post_ID as commentid,
            COUNT(*) AS count,
            {$this->wpdb->posts}.post_title as title,
            {$this->wpdb->posts}.post_date as title_date
            FROM {$this->wpdb->comments}
            LEFT JOIN {$this->wpdb->posts} ON {$this->wpdb->comments}.comment_post_ID = {$this->wpdb->posts}.ID
            WHERE date_format( comment_date, '%M, %Y' ) = '".$post->period."'
            AND {$this->wpdb->comments}.comment_approved = 1
            GROUP BY {$this->wpdb->comments}.comment_post_ID
            ORDER BY count DESC", $this->wpdb->comments);
            
            $popularquery = $this->wpdb->get_results( $sql );
            
            $temp_value = ''; 
            
            foreach ( $popularquery as $counting ):
                $temp_value .= '<strong class="popular-link">'.$counting->count.'</strong> | <a class="popular-link" href="'.get_permalink( $counting->commentid, false ).'">'.$counting->title.'</a><br />';
            endforeach;

            $posts_array[$key]->commented_posts = $temp_value;
        }
        $this->items = $posts_array;
    }

    /**
     * A single column
     */
    public function column_default( $item, $column_name ) {
        return $item->$column_name;
    }

    /**
     * Override of table nav to avoid breaking with bulk actions & according nonce field
     */
    public function display_tablenav( $which ) {

        ?>
        <div class="tablenav <?php echo esc_attr($which); ?>">
            <?php
            $this->extra_tablenav($which);
            $this->pagination($which);
            ?>
            <div class="clear"></div>
        </div>
        <?php
    }

    /**
     * Disables the views for 'side' context as there's not enough free space in the UI
     * Only displays them on screen/browser refresh. Else we'd have to do this via an AJAX DB update.
     * 
     * @see WP_List_Table::extra_tablenav()
     */
    public function extra_tablenav($which) {
        
        // global $wp_meta_boxes;
        $views = $this->get_views();
        
        if ( empty($views) ) {
            return;
        }

        $this->views();
    }

}

// Initiate table class
$CS_WP_Table = new CS_WP_Table();

// Add the Dashboard
require_once( plugin_dir_path( __FILE__ ).'wp-comment-dashboard.php' );