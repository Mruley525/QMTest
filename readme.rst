#####################################
QuaverMusic PHP Test with CodeIgniter
#####################################

This is a simple application that using cURL queries the GitHub repository
list and finds the top 1000 most starred public PHP projects.

*******************
Release Information
*******************

This code is intented for the review of QuaverMusic and the author.

************
Architecture
************

Using CodeIgniter was easy to setup.  The files that were changed and created
for this application are as followes:

* .htaccess = Modified to remove index.php from the links
* css/styles.css - All the css is here, only spent maybe five minutes styling it
* application/controllers/Repositories.php - The control file
* application/config/database.php - Contains the database connection information
* application/config/routes.php - Used to route between the listing and detail pages
* application/models/Repositories_model.php - Handles the database connection and writing the data.
* application/views/Repositories_details.php - View for the individual repositories
* application/views/Repositories_list.php - An overall list of all the repositories
* application/views/templates/header.php - Header html for clean coding
* application/views/templates/footer.php - Footer html for clean coding

*******************
Server Requirements
*******************

I used the Xampp server (v 3.2.4) to create this application.  It comes with a small
MySQL database and PHP 7.3.9 so installing CodeIgniter was easy.

************
Installation
************

Installation should only consist of three steps:

1. Create a database called quaver_test.
2. Create a folder called QuaverMusicTest in the htdocs and place all the files there.
3. Open the php.ini file and boost the max_execution_time to 120.  The only faster way I could think of processing all the results would be to clear the database and do all inserts but even then you run the risk of some overlapping on the api result pages.

***************
Acknowledgement
***************

CodeIgniter for being so easy to work with and QuaverMusic for allowing me to demonstrate my skills and ability to learn quickly.
