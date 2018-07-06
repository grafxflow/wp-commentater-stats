<?php
// Add comments stats for dashboard
function dashboard_widget_display_enqueues( $hook ) {
    if ( 'index.php' != $hook ) {
        return;
    }
    wp_enqueue_style( 'dashboard-widget-styles', plugins_url( '', __FILE__ ) . '/widgets.css' );
}

add_action( 'admin_enqueue_scripts', 'dashboard_widget_display_enqueues' );

function register_comment_stats_dashboard_widget() {
    wp_add_dashboard_widget(
        'comment_stats_widget',
        'WP Comments Stats',
        'comment_stats_dashboard_widget_display'
    );
}

add_action( 'wp_dashboard_setup', 'register_comment_stats_dashboard_widget' );

function comment_stats_dashboard_widget_display() {

    // Find the current comment counts for the dashboard
    global $wpdb;
    for ( $i = 0; $i <= 9; $i++ ) {

        $current_year = date( "Y-12-31", strtotime( date( 'Y-m-d' ) . " -$i years" ) );
        $last_year = date( "Y-01-01", strtotime( $current_year ) );

        $comment_counts[] = $wpdb->get_var( "SELECT COUNT(comment_ID) 
			FROM $wpdb->comments 
			WHERE comment_date < '" . $current_year . "' 
			AND comment_date > '" . $last_year . "'" );
    }

    $highest_value = max( $comment_counts );
    $data_points = count( $comment_counts );
    $bar_width = 100 / $data_points - 2;
    $total_height = 120;
    ?>

    <div class="comment-stat-bars" style="height:<?php echo $total_height ?>px;">
        <?php
        foreach ( $comment_counts as $count ):
            $count_percentage = $count / $highest_value;
        $bar_height = $total_height * $count_percentage;
        $border_width = $total_height - $bar_height;
        ?>
        <div class="comment-stat-bar" style="height:<?php echo $total_height ?>px; border-top-width:<?php echo $border_width ?>px; width: <?php echo $bar_width ?>%;"></div>
        <?php endforeach ?>
    </div>

    <div class='comment-stat-labels'>
        <?php $i = 0; $this_year = date('Y'); foreach( $comment_counts as $count ) : ?>
        <div class='comment-stat-label' style='width: <?php echo $bar_width ?>%;'>
            <?php echo $count ?><br/>
            <strong>
                <?php echo ($this_year - $i); $i++ ?>
            </strong>
        </div>
        <?php endforeach ?>
    </div>

    <div class='comment-stat-caption'>Comments in the past 10 years</div>

    <?php
    $comment_counts = NULL;
    for ( $i = 0; $i <= 11; $i++ ) {

        $current_month = date( "Y-m-01", strtotime( date( 'Y-m-d' ) . " -$i months" ) );
        $last_month = date( "Y-m-t", strtotime( $current_month ) );

        $comment_counts[] = $wpdb->get_var( "SELECT COUNT(comment_ID) 
			FROM $wpdb->comments 
			WHERE comment_date > '" . $current_month . "' 
			AND comment_date < '" . $last_month . "'" );
    }

    $highest_value = max( $comment_counts );
    $data_points = count( $comment_counts );
    $bar_width = 100 / $data_points - 2;
    $total_height = 120;
    ?>

    <div class="comment-stat-bars" style="height:<?php echo $total_height ?>px;">
        <?php
        foreach ( $comment_counts as $count ):
            $count_percentage = $count / $highest_value;
        $bar_height = $total_height * $count_percentage;
        $border_width = $total_height - $bar_height;
        ?>
        <div class="comment-stat-bar" style="height:<?php echo $total_height ?>px; border-top-width:<?php echo $border_width ?>px; width: <?php echo $bar_width ?>%;"></div>
        <?php endforeach ?>
    </div>

    <div class='comment-stat-labels'>

        <?php $i = 0; foreach( $comment_counts as $count ) : ?>
        <div class='comment-stat-label' style='width: <?php echo $bar_width ?>%;'>
            <?php echo $count ?><br/>
            <strong>
                <?php echo date("M", strtotime( date( 'Y-m-01' )." -$i months")).'<br />'.date("Y", strtotime( date( 'Y-m-01' )." -$i months")); $i++ ?>
            </strong>
        </div>
        <?php endforeach ?>
    </div>

    <div class='comment-stat-caption'>Comments in the past 12 months</div>

    <?php
    $comment_counts = NULL;
    for ( $i = 0; $i <= 6; $i++ ) {

        $current_day = date( "Y-m-d 00:00:00", strtotime( date( 'Y-m-d' ) . " -$i days" ) );
        $last_day = date( "Y-m-d 23:59:59", strtotime( date( 'Y-m-d' ) . " -$i days" ) );

        $comment_counts[] = $wpdb->get_var( "SELECT COUNT(comment_ID) 
			FROM $wpdb->comments 
			WHERE comment_date > '" . $current_day . "' 
			AND comment_date < '" . $last_day . "'" );
    }

    $highest_value = max( $comment_counts );
    $data_points = count( $comment_counts );
    $bar_width = 100 / $data_points - 2;
    $total_height = 120;
    ?>

    <div class="comment-stat-bars" style="height:<?php echo $total_height ?>px;">
        <?php
        foreach ( $comment_counts as $count ):
            $count_percentage = $count / $highest_value;
        $bar_height = $total_height * $count_percentage;
        $border_width = $total_height - $bar_height;
        ?>
        <div class="comment-stat-bar" style="height:<?php echo $total_height ?>px; border-top-width:<?php echo $border_width ?>px; width: <?php echo $bar_width ?>%;"></div>
        <?php endforeach ?>
    </div>

    <div class='comment-stat-labels'>

        <?php $i = 0; foreach( $comment_counts as $count ) : ?>
        <div class='comment-stat-label' style='width: <?php echo $bar_width ?>%;'>
            <?php echo $count ?><br/>
            <strong>
                <?php echo date("D", strtotime( date( 'Y-m-d' )." -$i days")); $i++ ?>
            </strong>
        </div>
        <?php endforeach ?>
    </div>

    <div class='comment-stat-caption'>Comments in the past 7 days</div>
    <?php
}
?>