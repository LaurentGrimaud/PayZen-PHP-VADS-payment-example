<?php
/*
 * PayZen VADS payment example
 * This script demonstrate how to realise the first step
 * of IPN process: the validation of the data received from PayZen
 *
 * @version 0.3
 *
 */

require "payzenFormToolbox.php";

// Toolbox initialisation, using PayZen account informations
$toolbox = new payzenFormToolbox(
     '[***CHANGE-ME***]'          // shopId XXX
   , '[***CHANGE-ME***]'          // certificate, TEST-version XXX
   , '[***CHANGE-ME***]'          // certificate, PRODUCTION-version XXX
   , 'http://[***CHANGE-ME***]'   // The IPN URL PayZen must use to notify you
   , 'http://[***CHANGE-ME***]'   // The return URL PayZen must use to send you customers back
   , 'TEST'                       // mode toggle ("TEST" or "PRODUCTION")
   );

try {
// PayZen Response Authentification
 $toolbox->checkIpnRequest($_POST);
}catch(Exception $e) {
  error_log("### ERROR - An exception raised during IPN PayZen process: ".$e);
  exit();
}

// PayZen Response Analysis
error_log('XXX PayZen response: '.print_r($_POST));
