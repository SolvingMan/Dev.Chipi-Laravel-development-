<?php

# Global Definetions :
$TerminalNumber = 1000; # Company terminal
$UserName = 'barak9611';   # API User
$CreateInvoice = true;  # to Create Invoice an invoice ?
$IsIframe = false;
$Operation = 1;  # = 1 - Bill Only , 2- Bill And Create Token , 3 - Token Only , 4 - Suspended Deal ( Order).


#Create Post Information
// Account vars
$vars = array();
$vars['TerminalNumber'] = $TerminalNumber;
$vars['UserName'] = $UserName;
$vars["APILevel"] = "10"; // req
$vars['codepage'] = '65001'; // unicode
$vars["Operation"] = $Operation;


$vars["Language"] = 'en';   // page languge he- hebrew , en - english , ru , ar
$vars["CoinID"] = '1'; // billing coin , 1- NIS , 2- USD other , article :  http://kb.cardcom.co.il/article/AA-00247/0
$vars["SumToBill"] = "20";// Sum To Bill
$vars['ProductName'] = "Test Product"; // Product Name , will how if no invoice will be created.

$vars['SuccessRedirectUrl'] = "https://secure.cardcom.co.il/DealWasSuccessful.aspx"; // Success Page
$vars['ErrorRedirectUrl'] = "https://secure.cardcom.co.il/DealWasUnSuccessful.aspx?customVar=1234"; // Error Page


// Other optinal vars :

// $vars["CancelType"] = "2"; # show Cancel button on start ,
// $vars["CancelUrl"] ="http://www.yoursite.com/OrderCanceld";
// $vars['IndicatorUrl']  = "http://www.yoursite.com/NotifyURL"; // Indicator Url \ Notify URL

$vars["ReturnValue"] = "1234"; // value that will be return and save in CardCom system
$vars["MaxNumOfPayments"] = "5"; // max num of payments to show  to the user

if ($CreateInvoice) {
    // article for invoice vars:  http://kb.cardcom.co.il/article/AA-00244/0
    $vars['IsCreateInvoice'] = "true";
    // customer info :
    $vars["InvoiceHead.CustName"] = 'Test "customer'; // customer name
    $vars["InvoiceHead.SendByEmail"] = "true"; // will the invoice be send by email to the customer
    $vars["InvoiceHead.Language"] = "he"; // he or en only
    $vars["InvoiceHead.Email"] = "yaniv@smsender.co.il";

    // products info

    // Line 1

    $vars["InvoiceLines1.Description"] = "ad160 יניב עבו בדיקה";
    $vars["InvoiceLines1.Price"] = "5";
    $vars["InvoiceLines1.Quantity"] = "2";

    // line 2
    $vars["InvoiceLines2.Description"] = "itme 2";
    $vars["InvoiceLines2.Price"] = "10";
    $vars["InvoiceLines2.Quantity"] = "1";

    // ********   Sum of all Lines Price*Quantity  must be equals to SumToBill ***** //
}

// Send Data To Bill Gold Server
$r = PostVars($vars, 'https://secure.cardcom.co.il/Interface/LowProfile.aspx');

parse_str($r, $responseArray);


# Is Deal OK
if ($responseArray['ResponseCode'] == "0") {
    # Iframe or  Redicet User :
    if ($IsIframe) {
        echo '<html><body>
                <iframe runat="server"  ID="TestIfame" width="700px" height="700px" src="' . $responseArray['url'] . '">
                </iframe>
              </body></html>';
    } else  // redirect
    {
        dd($responseArray);
        header("Location:" . $responseArray['url']);
    }

} # Show Error to developer only
else {
    echo $r;
}


function PostVars($vars, $PostVarsURL)
{
    $urlencoded = http_build_query($vars);
    #init curl connection
    if (function_exists("curl_init")) {
        $CR = curl_init($PostVarsURL);
        curl_setopt($CR, CURLOPT_POST, 1);
        curl_setopt($CR, CURLOPT_FAILONERROR, true);
        curl_setopt($CR, CURLOPT_POSTFIELDS, $urlencoded);
        curl_setopt($CR, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($CR, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($CR, CURLOPT_FAILONERROR, true);
        #actual curl execution perfom
        $r = curl_exec($CR);
        $error = curl_error($CR);
        # some error , send email to developer
        if (!empty($error)) {
            echo $error;
            die();
        }
        curl_close($CR);
        return $r;
    } else {
        echo "No curl_init";
        die();
    }
}