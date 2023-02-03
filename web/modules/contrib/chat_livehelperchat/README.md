CONTENTS OF THIS FILE
---------------------

INTRODUCTION
============
This module allows you to easily perform the necessary configuration of 
the Live Helper Chat server from the Drupal administration system for the 
correct display of the Chat and FAQ modules on our site, thus allowing
you to integrate with the Live Helper Chat server.
The viewing restriction of these modules is allowed for pages, content types 
and user roles.

It allows administrators to inject JS into the page output according to
configurable rules. It is useful to add the JS configuration generated in
the Live Helper Chat server.

REQUIREMENTS
============
This module only has a requirement that is not necessary for its installation
and that does not influence its main operation:
for the php code evaluator to work correctly it is necessary to have
the PHP module installed: https://www.drupal.org/project/php
Another requirement is to have knowledge of the domain where your LHC server
is located, by default it is https://demo.livehelperchat.com
 
RECOMMENDED MODULES
============
 
INSTALLATION
============
1. Install as you would normally install a contributed Drupal module. Visit
https://www.drupal.org/node/1897420 for further information.
2. Or use composer installation: 

       composer require 'drupal/chat_livehelperchat'


CONFIGURATION
============
To configure this module, do the following:
1. Configure user permissions in Administration » People » Permissions:
    * Administer livehelperchat module
       Permission to change livehelperchat settings.Give to trusted roles only.
    * Use PHP for livehelperchat visibility
       Permission to set PHP conditions to customize livehelperchat visibility
       on various pages. Give to trusted roles only.
    * Allow JS configuration injection in the page header. Permission to allow
       JS configuration injection in the header of the page generated in the 
       chat server. Give to trusted roles only. 

2. Add yor configuration LHC in in Administration » Configuration » System » 
    Settings LiveHelperChat
    
    Go to /admin/config/chat_livehelperchat/livehelperchatformsettings and
    choose the desired configuration for your site. The configuration allows
    the option to embed the JS code generated in the Live Chat Helper (LHC)
    server on our page or visually configure for less advanced users the CHAT
    or the FAQS module comfortably. It is allowed to select the domain where
    the LHC is hosted, the subject, height, width, position of the chat and
    the FAQ, etc..
    
    Chat display control and FAQ module are allowed by content types, pages 
    or user roles.
    
3. Enjoy!!
    
TROUBLESHOOTING
============
 
* If the FAQ module is not displayed, check the following:
 
    - The page where it should be shown is not excluded from the display?
 
    - Have you added some sample FAQs to your Live Helper Chat server?
    
    - Is the FAQ module activated in the configuration section?
    
 FAQ
============
 
  
MAINTENERS
============
Current maintainers:
  * Maikel Maldonado del Toro (mmaldonado) - https://www.drupal.org/u/mmaldonado
  * Zaidee Berges Pedrianes (zberges) - https://www.drupal.org/u/zberges
