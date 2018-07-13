=== WP Comment Stats ===
Contributors: jammy to go
Tags: comments, stats, dashboard comment stats
Requires at least: 3.9.25
Tested up to: 4.9.7
Stable tag: 1.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Shows detailed stats of your WordPress comments based on original plugin 'Comment Stats' - https://wordpress.org/plugins/comment-stats/

== Description ==

The admin comment stats shows the following comment stats: 

1. **Period** Should be self-explanatory, it is the month and year for that particular row.
1. **Approved** Shows the total number of comments that have been **APPROVED**.
1. **Posts Discussed** Shows you the total number of posts during this period that received at least 1 approved comment.
1. **Commentator Statistics (CS.)** Shows you the unique number for each of the sub-items: **CS. Names**: Total number of unique names used. **CS. Emails**: Total number of unique email addresses used. **CS. URLs**: Total number of unique websites used. **CS. IPs**: Total number of unique IP addresses
1. **Most Commented Post(s)** Lists all of your posts that received at least 1 comment. The posts show here are listed by the number of comments received during that period (NOTE: It is common for a blog post to get comments for months after it is posted, as such if it shows 10 posts this month for a comment but there are 20 in total, look at previous months to see when the other comments arrived on this post).

The Dashboard widget shows the following comment stats (It is based on the tutorial found at http://premium.wpmudev.org/blog/adding-custom-widgets-to-the-wordpress-admin-dashboard/):

1. Comments in the past 10 years
1. Comments in the past 12 months
1. Comments in the past 7 days

== Installation ==

1. Upload the folder **wp-comment-stats** and its contents to the `/wp-content/plugins/` directory
1. Activate the plugin **WP Comment Stats** through the **Plugins** menu in WordPress
1. The dashboard should be activated automatically and is named **WP Comments Stats**
1. The Admin WP Comment Stats page can be viewed in the following dashboard menu **Comments -> WP Comment Stats**

== Screenshots ==

1. WP Comments Stats Admin Page (running with WordPress 4.9.7 here)
2. WP Comments Stats Dashboard Widget (running with WordPress 4.9.7 here)

== Contributions ==

Would you like to like to contribute to **WP Comments Stats**? You are more than welcome to submit your pull requests on the [GitHub repo](https://github.com/grafxflow/wp-comments-stats). Also, if you have any notes about the code, please open a ticket on the issue tracker.

== Changelog ==

= 1.0.2 =
Functions moved into class
Separate views into folder

= 1.0.1 =
Fix to make work with WordPress 4.9.7
Required '$csList->screen = get_current_screen();'

= 1.0.0 =
Initial Release