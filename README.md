# ScanEyes

ScanEyes allows end users to listen to, and manage calls on trunked radio systems.

## Install

* Install XAMPP w/ PHP 5.5+ (exe)
* Install XAMPP to C:\xampp
* Don't learn more about Bitnami
* Allow Firewall Exception
* Delete contents of C:\xampp\htdocs\ folder
* Open XAMPP control and stop Apache
* Paste contents of this folder into htdocs folder
* Modify httpd.conf and vhosts.conf home directory to C:\xampp\htdocs\ScanEyesV3\
* Go to gmail.com and create an email account, this is the email address users will get emails from when they need to reset their password, or activate their account. Save these credentials. Use an email address like scaneyes-mailerbot-yourdomain@gmail.com

## Modifying http vhosts

* Open C:\xampp\apache\conf\httpd.conf
* modify 'ServerAdmin', change it to your domain specific GMail account email address
* modify 'ServerName', change it to your FQDN (if applicable) e.g. scaneyes.mywebsite.com
* modify 'DocumentRoot', change it to "C:/xampp/htdocs/ScanEyesV3"
* also modify '<Directory' to match the line above
* between '<Directory "C:/xampp/htdocs/ScanEyesV3">' and '</Directory>' remove 'Indexes' from the line starting with 'Options'

* Open http://localhost in a web browser and complete the install process