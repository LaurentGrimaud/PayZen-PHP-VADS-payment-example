# PayZen-PHP-VADS-payment-example

## Introduction
The code presented here is a demonstration of the implementation of the VADS PayZen payment system, aimed to ease its use and learning.



## Contents
This code is divided in three parts:
* payzen.form.example.php, the main file, entry point of the VADS payment process
* payzen.form.ipn.example.com, a minimal implementation of the IPN callback, the second step in the VADS payment process
* payzenFormToolBox.php, the core file, defining an utility class encapsulating all the PayZen logics of this example


## The first use
1. Place the files on the same directory, under the root of your web-server
2. In `payzen.form.example.php` and `payzen.form.ipn.example.php`, replace the occurences of `[***CHANGE-ME***]` by the actual values of your PayZen account
3. Access `payzen.form.example.php` from your browser, optionnaly change the values of the form being displayed, and validate-it
4. Follow the PayZen indications to perform the payment


## The next steps
You can follow the on-file documentation in `payzen.form.example.php` to change the properties of the payment you want to initiate, like the amount or the informations of the customer payment card.


## Note
* The documentation used to write this code was [Guide d'implementation formulaire de paiement, v3.4](https://payzen.eu/wp-content/uploads/2015/09/Guide_technique_d_implementation_Webservice_V5_v1.4_Payzen.pdf) (FRENCH)



