# wp-comments-stats V.1.0.3

This WordPress Plugin is based on the original plugin 'Comment Stats' found at https://wordpress.org/plugins/comment-stats/ with a few more elements added.

It has been tested in WordPress Version 4.9.7 so should also work with Version 3.9.25.

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

![Dashboard preview](https://grafxflow.co.uk/storage/app/media/blog-images/2015/9337/dashboard-wp-comment-stats.png)

Admin
----------
In the admin page it has been updated to work with WordPress 4.9.7 and now allows the order by **Period**, **Approved**, **Posts Discussed**, **CS. Names**, **CS. Emails**, **CS. URLs**, **CS.IPs** (CS. = Commentator Statistics).

It is made up of the following details...

Page Item | Description
------------ | -------------
Period | Should be self-explanatory, it is the month and year for that particular row.
Approved | Shows the total number of comments that have been **APPROVED**.
Posts Discussed | Shows you the total number of posts during this period that received at least 1 approved comment.
Commentator Statistics (CS.) | Shows you the unique number for each of the sub-items: **CS. Names**: Total number of unique names used. **CS. Emails**: Total number of unique email addresses used. **CS. URLs**: Total number of unique websites used. **CS. IPs**: Total number of unique IP addresses
Most Commented Post(s) | Lists all of your posts that received at least 1 comment. The posts show here are listed by the number of comments received during that period (NOTE: It is common for a blog post to get comments for months after it is posted, as such if it shows 10 posts this month for a comment but there are 20 in total, look at previous months to see when the other comments arrived on this post).

![Admin Page preview](https://grafxflow.co.uk/storage/app/media/blog-images/2015/9337/admin-wp-comment-stats.png)

Changelog
----------
**V1.0.3** - Functions moved into class for the dashboard-widget, Separate css files, Dashboard-widget only outputs approved comments, Dashboord-widget order changed to 7 days, 12 months, 10 years

**V1.0.2** - Functions moved into class, separate views into folder

**V1.0.1** - Fix to make work with WordPress 4.9.7 plus tidy-up code layout

```
Required '$csList->screen = get_current_screen();'
```

**V1** - Initial Release