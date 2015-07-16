<?php
/*
Plugin Name: WP Comment Stats
Plugin URI: http://www.grafxflow.co.uk
Description: Shows the comments statistics breakdown plus a dashboard output
Version: 1
Author: grafxflow.co.uk
Author URI: http://www.grafxflow.co.uk
*/

/* -------------------------------- REVISION HISTORY -----------------------------------

1.	Based on https://wordpress.org/plugins/comment-stats/ and updated to work with WordPress 4.2.2
	Added a dashboard widget which outputs
	i.		Comments in the past 10 years
	ii.		Comments in the past 12 months
	iii.	Comments in the past 7 days
--------------------------------------------------------------------------------------- */
?>
<?php
// Detect the WP Table class is available
if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
    class CS_WP_Table extends WP_List_Table
    {

        private $order;
        private $orderby;
        private $posts_per_page = 10;

        public function __construct()
        {	
			// NOTE: TO BE UPDATED
        }

        private function get_sql_results()
        {
            global $wpdb;
			if ( isset( $_GET['orderby'] ) AND $_GET['orderby'] ){
				if ($_GET['orderby'] == 'period'){
					$orderby = 'comment_date';	
				}
				else{
					$orderby = $_GET['orderby'];		
				}
			}
			else{
				$orderby = 'comment_date';
			}
			
			if ( isset( $_GET['order'] ) AND $_GET['order'] ){
				$order = $_GET['order'];	
			}
			else{
				$order = 'DESC';
			}
			
			
			$query = $wpdb->get_results("SELECT 
              date_format(comment_date, '%M, %Y') as period, 
              COUNT(*) as total,
              COUNT(DISTINCT(comment_post_ID)) as totalposts,
              COUNT(DISTINCT(comment_author)) as totalauthors,
              COUNT(DISTINCT(comment_author_email)) as totalemails,
              COUNT(DISTINCT(comment_author_url)) as totalurls,
              COUNT(DISTINCT(comment_author_IP)) as totalips
            FROM $wpdb->comments
			WHERE comment_approved = 1
            GROUP BY period
            ORDER BY ".$orderby." ".$order."
            ");
			
            return $query;
        }
		
		public function set_order()
		{
			if ( isset( $_GET['order'] ) AND $_GET['order'] )
				$order = $_GET['order'];
			$this->order = esc_sql( $order );
		}
	
		public function set_orderby()
		{
			if ( isset( $_GET['orderby'] ) AND $_GET['orderby'] )
				$orderby = $_GET['orderby'];
			$this->orderby = esc_sql( $orderby );
		}

        /**
         * @see WP_List_Table::ajax_user_can()
         */
        public function ajax_user_can()
        {
            return current_user_can('edit_posts');
        }

        /**
         * @see WP_List_Table::no_items()
         */
        public function no_items()
        {
            _e('No comments found.');
        }

        /**
         * @see WP_List_Table::get_views()
         */
        public function get_views()
        {
            return array();
        }

        /**
         * @see WP_List_Table::get_columns()
         */
        public function get_columns()
        {
            $columns = array(
				'period' => __('Period'),
                'total' => __('Approved'),
                'totalposts' => __('Posts Discussed'),
                'totalauthors' => __('CS. Names'),
				'totalemails' => __('CS. Emails'),
				'totalurls' => __('CS. URLs'),
				'totalips' => __('CS. IPs'),
                'commented_posts' => __('Most Commented Post(s)')
            );
			
            return $columns;
        }

        /**
         * @see WP_List_Table::get_sortable_columns()
         */
        public function get_sortable_columns()
        {
		
			$sortable = array(
                'period' => array('period', true),
                'total' => array('total', true),
                'totalposts' => array('totalposts', true),
                'totalauthors' => array('totalauthors', true),
				'totalemails' => array('totalemails', true),
				'totalurls' => array('totalurls', true),
				'totalips' => array('totalips', true),
            );
			
            return $sortable;
        }

        /**
         * Prepare data for display
         * @see WP_List_Table::prepare_items()
         */
        public function prepare_items()
        {
            
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
			
            empty($posts) AND $posts = array();

            # >>>> Pagination
            $per_page = $this->posts_per_page;
            $current_page = $this->get_pagenum();
            $total_items = count($posts);
            $this->set_pagination_args(array(
                'total_items' => $total_items,
                'per_page' => $per_page,
                'total_pages' => ceil($total_items / $per_page)
            ));
            $last_post = $current_page * $per_page;
            $first_post = $last_post - $per_page + 1;
            $last_post > $total_items AND $last_post = $total_items;

            // Setup the range of keys/indizes that contain 
            // the posts on the currently displayed page(d).
            // Flip keys with values as the range outputs the range in the values.
            $range = array_flip(range($first_post - 1, $last_post - 1, 1));

            // Filter out the posts we're not displaying on the current page.
            $posts_array = array_intersect_key($posts, $range);
            # <<<< Pagination
            // Prepare the data
            $permalink = __('Edit:');
            foreach ($posts_array as $key => $post) {
            	// Sort the actual post and results //
				
				// Output the actual posts
				global $wpdb;
			$popularquery = $wpdb->get_results("SELECT 
                     $wpdb->comments.comment_post_ID as commentid,
                     COUNT(*) AS count,
                     $wpdb->posts.post_title as title,
					 $wpdb->posts.post_date as title_date
                     FROM $wpdb->comments
                     LEFT JOIN $wpdb->posts ON $wpdb->comments.comment_post_ID = $wpdb->posts.ID
                     WHERE date_format( comment_date, '%M, %Y' ) = '".$post->period."'
					 AND $wpdb->comments.comment_approved = 1
                     GROUP BY $wpdb->comments.comment_post_ID
                     ORDER BY count DESC
                    ");
			$temp_value = '';

			foreach( $popularquery as $counting ) :
				$temp_value .= "<strong style='line-height:12px; font-size:10px;'>$counting->count</strong> | <a style='font-size:10px; line-height:12px;' href=".get_permalink($counting->commentid, false).">$counting->title</a><br />";
			endforeach;
				
				$posts_array[$key]->commented_posts = $temp_value;
            }
            $this->items = $posts_array;
        }

        /**
         * A single column
         */
        public function column_default($item, $column_name)
        {
            return $item->$column_name;
        }

        /**
         * Override of table nav to avoid breaking with bulk actions & according nonce field
         */
        public function display_tablenav($which)
        {

            ?>
            <div class="tablenav <?php echo esc_attr($which); ?>">
                <?php
                $this->extra_tablenav($which);
                $this->pagination($which);

                ?>
                <br class="clear" />
            </div>
            <?php
        }

        /**
         * Disables the views for 'side' context as there's not enough free space in the UI
         * Only displays them on screen/browser refresh. Else we'd have to do this via an AJAX DB update.
         * 
         * @see WP_List_Table::extra_tablenav()
         */
        public function extra_tablenav($which)
        {
            global $wp_meta_boxes;
            $views = $this->get_views();
            if (empty($views)) return;

            $this->views();
        }

    }





function comments_stats_admin_actions()
{
	add_submenu_page('edit-comments.php', 'WP Comment Stats', 'WP Comment Stats', 'activate_plugins', 'commentstatslist', 'comments_stats_list' );
}

add_action('admin_menu', 'comments_stats_admin_actions');

function comments_stats_list()
{
    $csList = new CS_WP_Table();
	?>
    <div class="wrap">
    	
        <h2><?php echo __('WP Comment Stats'); ?></h2>
        
        <p>This page shows you various statistics about your comments for every month. <span style="font-style:italic;"><strong>Scroll down to the bottom of the page to see definitions for each column in the table</strong></span>. Please note that this plugin is in it's very early stages of development, as such there may be some bugs in the numbers.</p>
    
    <?php
    $csList->prepare_items();
    $csList->display();
	?>
    <h3>Column Definitions</h3>
    
    <table width="100%" bgcolor="#e6e6e6" border="0" cellspacing="0" cellpadding="10">
  <tbody>
    <tr>
      <td style="border-bottom:1px solid #a0a5aa;" valign="top"><p><strong>Period</strong></p></td>
      <td style="border-bottom:1px solid #a0a5aa;" valign="top"><p>Should be self-explanatory, it is the month and year for that particular row.</p></td>
    </tr>
    <tr>
      <td style="border-bottom:1px solid #a0a5aa;" valign="top"><p><strong>Approved</strong></p></td>
      <td style="border-bottom:1px solid #a0a5aa;" valign="top"><p>Shows the total number of comments that have been <span style="font-style:italic;"><strong>APPROVED</strong></span>.</p></td>
    </tr>
    <tr>
      <td style="border-bottom:1px solid #a0a5aa;" valign="top"><p><strong>Posts Discussed</strong></p></td>
      <td style="border-bottom:1px solid #a0a5aa;" valign="top"><p>Shows you the total number of posts during this period that received at least 1 approved comment.</p></td>
    </tr>
    <tr>
      <td style="border-bottom:1px solid #a0a5aa;" valign="top"><p><strong>Commentator Statistics (CS.)</strong></p></td>
      <td style="border-bottom:1px solid #a0a5aa;" valign="top"><p>Shows you the unique number for each of the sub-items:</p>
      <ul>
<li><strong>CS. Names</strong>: Total number of unique names used</li>
<li><strong>CS. Emails</strong>: Total number of unique email addresses used</li>
<li><strong>CS. URLs</strong>: Total number of unique websites used</li>
<li><strong>CS. IPs</strong>: Total number of unique IP addresses</li>
</ul></td>
    </tr>
    <tr>
      <td valign="top"><p><strong>Most Commented Post(s)</strong></p></td>
      <td valign="top"><p>Lists all of your posts that received at least 1 comment. The posts show here are listed by the number of comments received during that period (NOTE: It is common for a blog post to get comments for months after it is posted, as such if it shows 10 posts this month for a comment but there are 20 in total, look at previous months to see when the other comments arrived on this post).</p></td>
    </tr>
  </tbody>
</table>
    </div>
<?php
}

// Add the Dashboard
require_once(plugin_dir_path( __FILE__ ).'wp-comment-dashboard.php');