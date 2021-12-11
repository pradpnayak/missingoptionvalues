# Missing option values

#### missingoptionvalues

## Overview

Since CiviCRM 5.42, the [entity_table field in CiviCRM tables will be derived from CiviCRM option group](https://lab.civicrm.org/dev/core/-/issues/2682). This has caused most of the extension to break. So if you have an extension and have code that stores value in entity_table which is not part of option list present in Civi core install the system will throw error. This extension avoids the fatal error by defining the entity table list with all the entities present in the system.

This extension by default supports only few entities mentioned below
1. Case
1. Activity
1. Grant
1. Contribution
1. Membership
1. OptionValue

Incase you need to have support for all the entities set below line in your civicrm.settings.php file

```define('MISSINGOPTIONVALUES_INLCUDE_ALL_CIVI_TABLES', TRUE);```

## Installation

1. If you have not already done so, setup Extensions Directory
  1. Go to Administer >> System Settings >> Directories
      1. Set an appropriate value for CiviCRM Extensions Directory. For example, for Drupal, [civicrm.files]/ext/
      1. In a different window, ensure the directory exists and is readable by your web server process.
  1. Click Save.
1. If you have not already done so, setup Extensions Resources URL
  1. Go to Administer >> System Settings >> Resource URLs
      1. Beside Extension Resource URL, enter an appropriate values such as [civicrm.files]/ext/
  1. Click Save.
1. Install Missing option values extension
  1. Go to Administer >> Customize Data and Screens >> Manage Extensions.
  1. Click on Add New tab.
  1. If Missing option values is not in the list of extensions, manually download it and unzip it into the extensions direction setup above, then return to this page.
  1. Beside Missing option values, click Download.
  1. Review the information, then click Download and Install.


This extension has been developed and is being maintained by [Pradeep Nayak](https://github.com/pradpnayak).
