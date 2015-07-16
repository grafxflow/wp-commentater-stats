# wp-comments-stats V.1

This WordPress Plugin is based on the original plugin 'Comment Stats' found at https://wordpress.org/plugins/comment-stats/ with a few more elements added.

It has been tested in WordPress Version 4.2.2 so should also work with Version 3.5.

Usage
----------
Once installed it can be found in the submenu **Comments** -> **WP Comment Stats**. Plus viewed on the **dashboard** using the name **WP Comments Stats**.

Dashboard
----------
Currently the dashboard shows 3 graphs with comment counts and dates.
Based on the tutorial found at http://premium.wpmudev.org/blog/adding-custom-widgets-to-the-wordpress-admin-dashboard/

1. Comments in the past 10 years
2. Comments in the past 12 months
3. Comments in the past 7 days

![Dashboard preview](http://www.grafxflow.co.uk/images/github/wp-comment-stats/dashboard-wp-comment-stats.jpg)

Admin
----------
In the admin page it has been updated to work with WordPress 4.2.2 and now allows the order by **Period**, **Approved**, **Posts Discussed**, **CS. Names**, **CS. Emails**, **CS. URLs**, **CS.IPs** (CS. = Commentator Statistics).

It is made up of the following details...

Page Item | Description
------------ | -------------
Period | Should be self-explanatory, it is the month and year for that particular row.
Approved | Shows the total number of comments that have been **APPROVED**.
Posts Discussed | Shows you the total number of posts during this period that received at least 1 approved comment.
Commentator Statistics (CS.) | Shows you the unique number for each of the sub-items: **CS. Names**: Total number of unique names used. **CS. Emails**: Total number of unique email addresses used. **CS. URLs**: Total number of unique websites used. **CS. IPs**: Total number of unique IP addresses
Most Commented Post(s) | Lists all of your posts that received at least 1 comment. The posts show here are listed by the number of comments received during that period (NOTE: It is common for a blog post to get comments for months after it is posted, as such if it shows 10 posts this month for a comment but there are 20 in total, look at previous months to see when the other comments arrived on this post).

![Admin Page preview](http://www.grafxflow.co.uk/images/github/wp-comment-stats/admin-wp-comment-stats.jpg)

Changelog
----------
**V1** - Initial Release

