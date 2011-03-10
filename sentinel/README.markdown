Sentinel Investments
====

Sentinel Investments is a high profile mutual fund and investment website with high security and time sensitive data regulated by the SEC.  It leverages PHP, XML, and SQL integration.

 - xml_insert.php - This script was run via a cron entry nightly.  It parses XML files uploaded to the server and inserts them into their relevant tables in the database.
 - inc/classes/SentinelMenu.php - The purpose of this class is to build dynamic navigation menus for a website.  It uses recursive functions to go as deep as necessary based on entries in a database table.
 - inc/classes/SentinelPortfolio.php - The purpose of this class was to set up all the funds of Sentinel Investments into easily accessed getter functions.  Of note is the get_fund_detail_products() function that returns all the relevant information about a fund's products.
 - views/content/templates/content_three_col.html - Template page that assumes the page has content on it and is in three columns.
 - views/views_primary.php - The views for individual pages for the site.  Of note is product_informaton_view(), the function that builds the product information page.
 - views/view_function.php - The functions that insert the views into their corresponding templates to create the page.