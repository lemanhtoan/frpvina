=======================================================================================================
RealEstateManager PRO, version 2.2, for Joomla 1.5.x, Joomla 1.6.x, Joomla 1.7.x, Joomla 2.5.x and PHP5
=======================================================================================================


CONTENTS
========

01. Introduction
02. System Requirements
03. License
04. Installation
05. Upgrade Instructions
06. UPGRADE INSTRUCTIONS FROM PREVIOUS 1.5.x VERSIONS TO Pro WITHOUT LOST DATA 
07. Getting started
08. Support
09. Addons
10. Languages
11. Other components/plugins
12. Customisation
13. Keeping up-to-date



01. INTRODUCTION
----------------

The RealEstateManager Pro component for Joomla allows you to manage and maintain 
a collection of houses for sale and rent with ease on a Joomla-based website. 

The Pro version of RealEstateManager has a lot of added functionality compared to 
the Free version. For full details on the different options visit 
http://www.ordasoft.com.

Version    : 2.2 Pro
Maintainers: Andrey Kvasnevskiy
Homepage   : http://www.ordasoft.com



02. SYSTEM REQUIREMENTS
-----------------------

RealEstateManager Pro is a Joomla component. It needs a functioning Joomla 1.5.x 
installation. RealEstateManager Pro needs PHP 5 with SOAP, CURL, XSL and GD  
extensions to function properly.

PLEASE CHECK BEFORE INSTALLING RealEstateManager:
In order for csv export to work, you need to compile PHP5 with support for the 
XSL extension!
In order for CAPTCHA images (Guest User anti-spam for reviews and suggestions) 
to work, you need to compile PHP5 with the GD extension!

CHECK YOUR PHP INSTALLATION FOR PROPER EXTENSIONS:
First please make sure PHP5 has the above extensions enabled!
- If you run your own web server, please recompile PHP with support for XSL, 
  SOAP, CURL, GD.
- If your website is with a hosting provider, check with them for the inclusion 
  of these PHP extensions.
RealEstateManager needs these PHP extensions to install and function correctly!

INSTALLER CHECK FOR PHP EXTENSIONS:
The RealEstateManager installer will check for the availability of these PHP 
extensions and issue a warning if they are missing. If you get such a warning, 
just uninstall RealEstateManager, fix the PHP extensions first, and then 
reinstall RealEstateManager.

PLEASE NOTE:
Even though we can do checks for the proper PHP extensions inside the 
RealEstateManager installer package, we have no way to roll back the installation 
once started, not even when a check fails. So installation will continue, even 
if a PHP extension is not present. This is unfortunately a limitation of the 
Joomla installer!



03. LICENSE
-----------

RealEstateManager Pro is released as a commercial component.
Check the included LICENSE.txt file for license details.
There is also a Free version available (with less functionality), released 
under the GNU/GPL.



04. INSTALLATION
----------------

RealEstateManager Pro is installed easily with the standard Joomla component 
installer. For additional information on how to set up and configure 
RealEstateManager Pro to suit your needs, please consult the 
[RealEstateManager Manuals] section on the website.



05. UPGRADE INSTRUCTIONS FROM PREVIOUS 2.0 FREE VERSIONS TO Pro WITHOUT LOST DATA 
----------------------------------------------------------

Full save so files and folders:

{yours site}/administrator/components/com_realestatemanager
{yours site}/components/com_realestatemanager

Please do full dataBase backup.

Then go to [RealEstateManager] [Settings Backend] and set [Update] to “YES”

The uninstall Free component and install Pro version.

Please recove folders
{yours site}/components/com_realestatemanager/edocs
{yours site}/components/com_realestatemanager/photos

Also you will need upgrade all plugins and modules

And all will work.

At first please check Upgrade process at test site



06. UPGRADE INSTRUCTIONS FROM PREVIOUS 1.5.x VERSIONS TO Pro WITHOUT LOST DATA 
----------------------------------------------------------

Full save so files and folders:

{yours site}/administrator/components/com_realestatemanager
{yours site}/components/com_realestatemanager

Please do full houses import.

After that please remove
{yours site}/administrator/components/com_realestatemanager
{yours site}/components/com_realestatemanager

Please do install RealEstateManager 2.0 version.

Please recover folders:
{yours site}/components/com_realestatemanager/edocs
{yours site}/components/com_realestatemanager/photos

Also you will need upgrade all plugins and modules
And all will work.

At first please check Upgrade process at test site



07. GETTING STARTED
-------------------

The [RealEstateManager Pro 2.2 Manuals] section on the website has many articles 
with instructions on RealEstateManager setup and use.



08. SUPPORT
-----------

The OrdaSoft site has a [Support Forums] section for support to the 
RealEstateManager component, modules and plugins. There is a special forum section 
dedicated to registered RealEstateManager Pro users.



09. ADDONS (MODULES, PLUGINS)
-----------------------------

Modules and plugins (mambots) are constantly being developed for use with 
RealEstateManager. You can download them from the download sections on the website.



10. LANGUAGES
-------------

RealEstateManager Pro comes with English and Russian language files included.
The RealEstateManager interface will automatically pick up the frontend or backend 
language set in your Joomla configuration. Frontend language switches with 
JoomFish will also result in the automatic language change in RealEstateManager.

You can add non-included languages to RealEstateManager by creating your own 
translations. 
Copy english.php from the directory /components/com_realestatemanager/language/, 
rename it to your language and then create the translation. Next add the 
language selectors to the code and upload the translation to your RealEstateManager.
You can find full instructions on creating translations and adding languages 
on the website.



11. OTHER COMPONENTS/PLUGINS
----------------------------

Add-ons for interaction with other components are also available developed 
(SEF, Sitemap). Community Builder and full JoomFish integration will be 
developed later.



12. CUSTOMISATION
-----------------

If you need a new specific feature added to RealEstateManager Pro for your own 
installation, you can order a custom development. 
Just contact sales@ordasoft.com describing the details of your requirements. 
We will then investigate your request and send you a price quote for this 
development. When you pay for a customisation for RealEstateManager, you will receive 
the next version of RealEstateManager Pro with your feature included.



13. KEEPING UP-TO-DATE
----------------------

Please check http://www.ordasoft.com for news, details and contact 
information regarding RealEstateManager. In time there will also be a 
RealEstateManager Newsletter to which you can subscribe (news, developments, etc.).