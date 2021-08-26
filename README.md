# php-date-time-tracker
This is a basic PHP web page that allows recording the date/times that something happened.

## Requirements
* You must have a MySQL database setup. The database can be named whatever you want, but it needs to have a records table with 2 columns: id (int Auto Increment) and dt (datetime).
* The database connection information is to be saved in the src/config/config.php file. There is a sample file in that same directory.
* You have to create a Google API account and generate a client ID that can be used for OAuth authentication. That also goes in config.php.

## Setup
* Clone this repository to your local machine.
* Install the composer libraries `composer install`.
* Depending on how you deploy this project to your HTTP server, you may need to redirect all public traffic to public/index.php so that routing works correctly. This can be done using something like an .htaccess (Apache) or web.config (IIS) file.
* To run the application locally, using the built-in php HTTP server, use the following terminal command `composer start`.
* To run the unit tests, use the following terminal command `composer test`.
