<div class="wrap">
    <h2><?php echo __('WP Comment Stats'); ?></h2>

    <p>This page shows you various statistics about your comments for every month. <span style="font-style:italic;"><strong>Scroll down to the bottom of the page to see definitions for each column in the table</strong></span>.</p>

    <?php
    $csList = new CS_WP_Table();
    // Output the table of details
    $csList->prepare_items();
    $csList->screen = get_current_screen();
    $csList->display();
    ?>
    
    <h3>Column Definitions</h3>

    <table width="100%" bgcolor="#e6e6e6" border="0" cellspacing="0" cellpadding="10">
        <tbody>
            <tr>
                <td style="border-bottom:1px solid #a0a5aa;" valign="top">
                    <p><strong>Period</strong>
                    </p>
                </td>
                <td style="border-bottom:1px solid #a0a5aa;" valign="top">
                    <p>Should be self-explanatory, it is the month and year for that particular row.</p>
                </td>
            </tr>
            <tr>
                <td style="border-bottom:1px solid #a0a5aa;" valign="top">
                    <p><strong>Approved</strong></p>
                </td>
                <td style="border-bottom:1px solid #a0a5aa;" valign="top">
                    <p>Shows the total number of comments that have been <span style="font-style:italic;"><strong>APPROVED</strong></span>.</p>
                </td>
            </tr>
            <tr>
                <td style="border-bottom:1px solid #a0a5aa;" valign="top">
                    <p><strong>Posts Discussed</strong></p>
                </td>
                <td style="border-bottom:1px solid #a0a5aa;" valign="top">
                    <p>Shows you the total number of posts during this period that received at least 1 approved comment.</p>
                </td>
            </tr>
            <tr>
                <td style="border-bottom:1px solid #a0a5aa;" valign="top">
                    <p><strong>Commentator Statistics (CS.)</strong></p>
                </td>
                <td style="border-bottom:1px solid #a0a5aa;" valign="top">
                    <p>Shows you the unique number for each of the sub-items:</p>
                    <ul>
                        <li><strong>CS. Names</strong>: Total number of unique names used</li>
                        <li><strong>CS. Emails</strong>: Total number of unique email addresses used</li>
                        <li><strong>CS. URLs</strong>: Total number of unique websites used</li>
                        <li><strong>CS. IPs</strong>: Total number of unique IP addresses</li>
                    </ul>
                </td>
            </tr>
            <tr>
                <td valign="top">
                    <p><strong>Most Commented Post(s)</strong></p>
                </td>
                <td valign="top">
                    <p>Lists all of your posts that received at least 1 comment. The posts show here are listed by the number of comments received during that period (NOTE: It is common for a blog post to get comments for months after it is posted, as such if it shows 10 posts this month for a comment but there are 20 in total, look at previous months to see when the other comments arrived on this post).</p>
                </td>
            </tr>
        </tbody>
    </table>
</div>