<?php
/*
Widget Name: WP Comment Stats Widget
*/
class CS_WP_Widget extends WP_Widget {
    
    public function __construct() {
        
        global $wpdb;
        $this->wpdb = $wpdb;
        
        add_action('init', array($this, 'init' ));    
    }
    
    public function init() {
        add_action('wp_dashboard_setup', array($this, 'dashboard_widget_add_widget'));
        add_action('admin_enqueue_scripts', array($this, 'dashboard_widget_css_include'));
    }
    
    public function dashboard_widget_add_widget() {
        wp_add_dashboard_widget(
            'comment_stats_widget',
            'WP Comments Stats (Approved Only)',
            array($this, 'comment_stats_dashboard_widget_display')
        );
    }
    
    public function dashboard_widget_css_include($hook) {
        if ( 'index.php' != $hook ) {
            return;
        }
        wp_enqueue_style( 'dashboard-widget-styles', plugins_url( '', __FILE__ ) . '/css/widgets.css' );
    }
    
    public function comment_stats_dashboard_widget_display() {
        
        // 7 days - 1 week
        // Reset comment counts
        $comment_counts = null;
    
        for ( $i = 0; $i <= 6; $i++ ) {
            $current_day = date( "Y-m-d 00:00:00", strtotime( date( 'Y-m-d' ) . " -$i days" ) );
            $last_day = date( "Y-m-d 23:59:59", strtotime( date( 'Y-m-d' ) . " -$i days" ) );
        
            $comment_counts[] = $this->wpdb->get_var( "SELECT COUNT(comment_ID) 
            FROM {$this->wpdb->comments} 
            WHERE comment_date > '" . $current_day . "' 
            AND {$this->wpdb->comments}.comment_approved = 1
            AND comment_date < '" . $last_day . "'" );
        }
        
        $highest_value = max( $comment_counts );
        $data_points = count( $comment_counts );
        $bar_width = 100 / $data_points - 2;
        $total_height = 120;
        
        include( plugin_dir_path( __FILE__ ) . 'views/widget/widget-stats-weekly.php');
    
        // 12 months
        // Reset comment counts
        $comment_counts = null;
        
        for ( $i = 0; $i <= 11; $i++ ) {
            $current_month = date( "Y-m-01", strtotime( date( 'Y-m-d' ) . " -$i months" ) );
            $last_month = date( "Y-m-t", strtotime( $current_month ) );
            $comment_counts[] = $this->wpdb->get_var( "SELECT COUNT(comment_ID) 
            FROM {$this->wpdb->comments}
            WHERE comment_date > '" . $current_month . "' 
            AND {$this->wpdb->comments}.comment_approved = 1
            AND comment_date < '" . $last_month . "'" );
        }
        
        $highest_value = max( $comment_counts );
        $data_points = count( $comment_counts );
        $bar_width = 100 / $data_points - 2;
        $total_height = 120;
        
        include( plugin_dir_path( __FILE__ ) . 'views/widget/widget-stats-monthly.php');
        
        // 10 years
        // Reset comment counts
        $comment_counts = null;
        
        for ( $i = 0; $i <= 9; $i++ ) {

            $current_year = date( "Y-12-31", strtotime( date( 'Y-m-d' ) . " -$i years" ) );
            $last_year = date( "Y-01-01", strtotime( $current_year ) );

            $comment_counts[] = $this->wpdb->get_var( "SELECT COUNT(comment_ID) 
            FROM {$this->wpdb->comments} 
            WHERE comment_date < '".$current_year."' 
            AND {$this->wpdb->comments}.comment_approved = 1
            AND comment_date > '".$last_year."'" );
        }
        
        $highest_value = max($comment_counts);
        $data_points = count($comment_counts);
        $bar_width = 100 / $data_points - 2;
        $total_height = 120;
        
        include( plugin_dir_path( __FILE__ ) . 'views/widget/widget-stats-yearly.php');
    
    }

}

// Initiate widget class
$CS_WP_Widget = new CS_WP_Widget();
?>