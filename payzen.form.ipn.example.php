<?php
/*
 * PayZen VADS payment example
 * This script demonstrate how to realise the first step
 * of IPN process: the validation of the data received from PayZen
 *
 * @version 0.4
 *
 */

require "payzenFormToolbox.php";

// Toolbox initialisation, using PayZen account informations
$toolbox = new payzenFormToolbox(
     '[***CHANGE-ME***]'          // shopId
   , '[***CHANGE-ME***]'          // certificate, TEST-version
   , '[***CHANGE-ME***]'          // certificate, PRODUCTION-version
   , 'TEST'                       // TEST-mode toggle
   , 'http://[***CHANGE-ME***]'   // The IPN URL PayZen must use XXX
   , 'http://[***CHANGE-ME***]'   // The return URL PayZen must use XXX
   );

/*
 * Toolbox can accept logging callback method
 * Use it if you need special logging, like database logging
 * or if you need to hook the toolbox to your own loggin process
 *
 $toolbox->setLogFunction(function($level, $message, $data = null){
  error_log(sprintf(
        ">>>\nLOG TIME: %s\nLOG LEVEL: %s\nLOG MESSAGE: %s\nLOG DATA:\n %s\n<<<\n"
      , date('r')
      , $level
      , $message
     , print_r($data, true)
    )
  );
  });
*/

// Sets the toolbox log level to 'NOTICE', to gain maximun feedback
// about the request process. Comment out this line to get rid of logs
$toolbox->setNoticeLogLevel();

try {
// PayZen Response Authentification
 $toolbox->checkIpnRequest($_POST);
}catch(Exception $e) {
  error_log("### ERROR - An exception raised during IPN PayZen process: ".$e);
  exit();
}

// PayZen Response Analysis
