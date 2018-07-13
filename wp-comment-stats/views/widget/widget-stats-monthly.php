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
        <?php echo $count ?><br />
        <strong><?php echo date("M", strtotime( date( 'Y-m-01' )." -$i months")).'<br />'.date("Y", strtotime( date( 'Y-m-01' )." -$i months")); $i++ ?></strong>
    </div>
    <?php endforeach ?>
</div>

<div class='comment-stat-caption'>Comments in the past 12 months</div>