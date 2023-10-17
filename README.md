# Coomer.su-Scraper
Scrapes the media from a list of users from the site coomer.su

# Requirements
php-curl
simple_html_dom
php5+

# Usage
1. Add users to the coomer_users.txt file in a line delimited format. 
2. Then execute the scraper and it will create a list of all of their posts and media.

downloader.php will download the media into username folders. 
They will be ordered by username, post id, numeric order of media in the post, finally the filename selected by the site.
