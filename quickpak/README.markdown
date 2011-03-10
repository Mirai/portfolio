QuikPack
====

QuickPak is an eCommerce site written in PHP and the CodeIgniter framework.  It leverages jQuery and the authorize.net API to allow a user to step through the process of purchasing a product.

 - views/main - The view files that control the layout of the website.
 - views/home_view.php - Uses jQuery to populate data fields based on earlier choices in the input.
 - controllers/home.php, baby.php, emergency.php, travel.php - Takes the input from the view and adds the products to a shopping cart.  Also encrypts credit card information for security.
 - controllers/orderprocess.php - Uses the authorize.net API to process the order.
 - controllers/thankyou.php - Sends email to client enumerating order.  Also sends thank you email to user, thanking them for their purchase.
 - views/thankyou_view.php - Displays order on screen for user and thanks them for their purchase.