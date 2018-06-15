<?php
/**
 * Created by PhpStorm.
 * User: Dimbo
 * Date: 02-Jun-17
 * Time: 1:11 PM
 */

namespace App;


class Marketing
{
    private static function getMessageBody($email, $name, $username = "chipiisrael217", $token = "652984760b078")
    {
        return <<<XML
<?xml version="1.0" encoding="UTF-8" ?> 
         <InfoMailClient> 
             <UpdateContacts> 
                 <User> 
                     <Username>$username</Username> 
                     <Token>$token</Token> 
                 </User> 
                 <Contacts handleEvents="true"> 
                     <Contact fname="$name" email="$email" addToGroupName="chipi"/> 
                 </Contacts> 
             </UpdateContacts> 
         </InfoMailClient> 
XML;
    }

    // sendin message to owner about user's sign in
    static function signInNotify($email, $name)
    {
        $messageBody = self::getMessageBody($email, $name);

//        // new (rad) version
//        $ch = curl_init("http://cloud.inforu.co.il/mail/api.php?xml=" . urlencode($messageBody));
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        $response = curl_exec($ch);
//        curl_close($ch);
//
//        return $response;

//// old (pojeet) version
        $url = "http://infomail.inforu.co.il/api.php?xml=";
        $post_data = array("xml" => $messageBody,);
        $stream_options = [
            'http' => [
                'method' => 'POST',
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'content' => http_build_query($post_data),
            ]
        ];

        // create context for post request
        $context = stream_context_create($stream_options);

        // sending post itself
        return file_get_contents($url, null, $context);
    }

    static function sendMail($to = "info@chipi.co.il", $message = "new customer")
    {
        $headers = 'From: Chipi' . "\r\n" .
            'Reply-To: chipi.co.il' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        mail($to, $message, $message, $headers);
    }

    static function sendMessageForgotPassword($email,$name,$password){
        $linkToMsg = 'https://www.chipi.co.il/auth';
        $msg = "
        <html>
        <body>
        <div style='direction:rtl; unicode-bidi:embed;'><div style='text-align:center;'><img 
        src='http://www.chipi.co.il/images/banner_new.jpg' width='100%'></div><br/><br/><br/>
        <div style='text-align:center;font-size:1.6em;'>שלום {$name},<br/>
        <div>סיסמת לאתר צ'יפי הינה : {$password}</div></div><br/><br/>
        <div style='text-align:center';>
        <a href={$linkToMsg} style='background-color: #f78031;border: none;
        color: white;padding: 15px 32px;text-align: center;text-decoration: none;display: inline-block;font-size: 16px;
        '>למעבר לעמוד התחברות לאתר צ'יפי לחץ כאן</a>
        </div>
        <br/><br/>
        <div>*****אין להגיב למייל זה</div>
        </div>
        </body>
        </html>
        ";
        $mail = new \PHPMailer();
        $mail->setFrom('info@chipi.co.il', "אתר צ'יפי");
        $mail->addAddress("$email", "$name");

        $mail->AddReplyTo('info@chipi.co.il', "אתר צ'יפי" );
        $mail->Subject  = "שחזור סיסמתך לאתר צ'יפי";
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);
        $mail->Body = $msg;
        $mail->send();
    }
}