<?php
/*
 * PayZen VADS payment example
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


// Generation of the data being transmitted to PayZen by HTML form
$formData = $toolbox->getFormData(
  substr(time(), -6)     // your order identifier - Change-it to reflect your needs
 , '4300'                // payment amount - Change-it to reflect your needs
 , '978'                 // payment currency code - Change-it to reflect your needs
);


?>
<html>
 <head>
  <title>PayZen Form Payment Example</title>
  <style>
   label {font-weight: bold;width:170px;display:inline-block;text-align:right;padding:2 10 2 0;}
   input[type=text] {width:350px;}
  </style>
 </head>
 <body>
  <pre>
  <?php echo print_r($formData, true) ?>
  </pre>
  <form action="<?php echo $formData['form']['action'] ?>"
        method="<?php echo $formData['form']['method'] ?>"
        enctype="<?php echo $formData['form']['enctype'] ?>"
        accept-charset="<?php echo $formData['form']['accept-charset'] ?>" />
  <?php foreach($formData['fields'] as $name => $value) { ?>
    <label><?php echo $name ?></label>
    <input type="text" readonly="readonly" name="<?php echo $name ?>" value="<?php echo $value ?>" />
   <br />
  <?php } ?> 
   <input type="submit" name="Time To" value="Pay" />
  </form>
 </body>
</html>
