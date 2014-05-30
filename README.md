ChessPgnViewer
==============

This is a little project I worked on to keep track and be able to replay chess matches between my friends and I.  It reads files in a standard chess format called PGN.  It is not intended to be used for any sort of public or commercial use.

The system requirements for this are as following:
* PHP
* Apache (allowing .htaccess files, since I put this on a shared hosting account)
  * mod_rewrite must also be enabled
* MySQL

To get started running this locally, one should:
* Import the database schema into their local database connection
* Fill in appropriate values for database hostname, user, and password in dbconnect.php (this really ought to be a configuration file)
* There's a global password to use any of this, it's currently an md5 hash of a password.  Generate your own MD5 password ( <?= md5($yourStr) ?> and put it into the top of dbconnect.php, line 2
* Put the www directory into your favorite webhost

From there on out, you should be able to upload your own games from the main index page, and view past games via the "Launch Game" button.