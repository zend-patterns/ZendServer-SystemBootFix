Zend Server SystemBoot fixer
============================

This is a 3rd party Zend Server only module.

********************************************************
   Warning! The actions supplied by this module are
   dangerous for your system! Use them only with
   proper guidance or if you know what you're doing!
********************************************************

This module adds a new page to Zend Server and adds two new actions (not webapi) that make changes to the onboard system-boot snapshot used for reseting configuration.

The page displays comparison information between the local NODES_PROFILE and the current system boot snapshot "metadata" file content.

The Patch action allows you to rewrite the "metadata" file in the System boot snapshot and replace it with the information in NODES_PROFILE. This allows you to update the package so that at least formally it should be acceptable to the reset configuration. No guarantees regarding actual compatibility.

The Replace action allows you to completely replace the System Boot snapshot in case you want to or it's missing. Note that the new snapshot will be according to the current configuration at the time of creation.

Installation
------------
