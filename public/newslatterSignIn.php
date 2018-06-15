<?php 
ob_start();
session_start();

Tools::showErroeMessages();
function __autoload($classname)
{
    $filename = "./admin/". $classname .".php";
    include_once($filename);
}

$ipAddress = $_SERVER['REMOTE_ADDR'];
$justDate=date('Y/m/d');

$email = isset($_GET['email']) == true ? $_GET['email'] : '';

if(!empty($email))
{
    $new = new MaillingListMysqli();
    $res = $new->checkIfMailExist($email);
    if($res==0)
    {
    ?>
    <!-- <script>
        (function (i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function () {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

        ga('create', 'UA-57155240-1', 'auto');
        ga("require", "ec");
        ga('send', 'pageview');



    </script> -->
    <?php 
$name="";


    XmlTools::sendUserToChipiClub($email,$name);
    $newUser = new MaillingList(null, $email, $ipAddress, $justDate, 1);
    $addUser = $new->addUserToMaillingList($newUser);
        
?>
<!-- Google Code for Sign up - Newsletter Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 929435375;
var google_conversion_language = "en";
var google_conversion_format = "3";
var google_conversion_color = "ffffff";
var google_conversion_label = "14D6CMazmWUQ752YuwM";
var google_remarketing_only = false;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/929435375/?label=14D6CMazmWUQ752YuwM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
<?php
    }else{
        echo 'exist';
        return false;
    }
}else{?>
    <script>
        var date = new Date(new Date().getTime() + 3 * 1000);
        document.cookie = "user_sigin=1; path=/; expires=" + date.toUTCString();
    </script>
<?
    header('refresh:0; url=' . $_SERVER['HTTP_REFERER']);
}


?>


