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
        <?php echo $count ?><br />
        <strong><?php echo ($this_year - $i); $i++ ?></strong>
    </div>
    <?php endforeach ?>
</div>

<div class="comment-stat-caption">Comments in the past 10 years</div>