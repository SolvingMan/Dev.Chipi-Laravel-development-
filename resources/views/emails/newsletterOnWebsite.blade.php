<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="initial-scale=1.0"/>
    <meta name="format-detection" content="telephone=no"/>
    {{--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">--}}
    <title>Chipi</title>
    <style type="text/css">
        body {
            padding: 0;
            margin: 0;
        }

        .item .mob_100 {
            border: 1px solid #d0d0d0;
            outline: 2px solid rgba(0,0,0,0.05);
        }

        .item {
            text-decoration: none;
        }

        .item .mob_100:hover {
            border: 1px solid #ec6409;
        }

        .shop .mob_100 {
            border-bottom: 1px solid transparent;
        }
        .shop img:hover {
            opacity: 0.8 !important;
        }

        .shop .mob_100:hover {
            border-bottom: 1px solid #ec6409;
        }
        .button:hover {
            background-color: #ffffff !important;
            border-color: #ec6409 !important;
            color: #ec6409 !important;
        }

        .descr {
            font-family: 'Raleway', sans-serif;
            font-size:20px; color:#000;
            line-height:40px;
            font-weight: bold;
        }

        html { -webkit-text-size-adjust:none; -ms-text-size-adjust: none;}
        @media only screen and (max-device-width: 680px), only screen and (max-width: 680px) {
            *[class="table_width_100"] {
                width: 96% !important;
            }
            *[class="border-right_mob"] {
                border-right: 1px solid #dddddd;
            }
            *[class="mob_100"] {
                width: 100% !important;
                margin-bottom: 10px;
            }
            *[class="mob_center"] {
                text-align: center !important;
            }
            *[class="mob_center_bl"] {
                float: none !important;
                display: block !important;
                margin: 0px auto;
            }
            .logo {
                height: 60px !important;
            }
            .shop .mob_100 {
                margin-bottom: 0;
            }
            .shop .bottom {
                line-height: 0 !important;
                height: 0 !important;
            }
            .iage_footer a {
                text-decoration: none;
                color: #929ca8;
            }
            img.mob_display_none {
                width: 0px !important;
                height: 0px !important;
                display: none !important;
            }
            img.mob_width_50 {
                width: 40% !important;
                height: auto !important;
            }
            .table-mob {
                width: 100% !important;
                padding: 0 10px;
            }
            .table-mob .shop .mob_100 {
                width: 33.33% !important;
            }
            .shop img {
                width: 100% !important;
            }
            .shop>.mob_100>table.mob_100 {
                width: 100% !important;
            }
            .shop>.mob_100>table.mob_100 td {
                padding: 0 5px !important;
            }

            .ebayDeals {
                font-size: 14px;
            }

            .descr {
                font-size: 14px;
                line-height: 20px;
            }

        }
        .table_width_100 {
            width: 680px;
        }

        ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        li {
            float: left;
        }

        li a {
            display: inline-block;
            color: black;
            text-align: center;
            padding: 8px;
            text-decoration: none;
        }

        li a:hover {
            color: #23527c;
            text-decoration: underline;
        }

    </style>

    <meta name="robots" content="noindex,follow" />
</head>

<body>
<div id="mailsub" class="notification" align="center">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="min-width: 320px;"><tr><td align="center" bgcolor="#eff3f8">
                <table border="0" cellspacing="0" cellpadding="0" class="table_width_100" width="100%" style="max-width: 680px; min-width: 300px;">
                    <tr><td>
                            <a href="{{$site}}?utm_source={{$nameOfMail}}&utm_medium={{$nameOfMail}}&utm_campaign={{$nameOfMail}}"><img class="logo" style="display:block;" src="https://www.chipi.co.il/images/media/index1/header_newsletter.jpg" width="100%" height="100" alt="icon" /></a>
                            {{--<div style="height: 40px; background-color: #ec6409;"></div>--}}
                            {{--<!-- padding --><div style="height: 15px; line-height: 15px; font-size: 10px;"> </div>--}}
                            {{--<!-- padding --><div style="line-height: 50px; font-size: 10px;"><img style="display:block; line-height:0px; font-size:0px; border:0px;" src="https://www.chipi.co.il/images/icon/index1/main_logo.png" width="200" height="100" alt="icon" /></div>--}}
                            {{--<!-- padding --><div style="height: 15px; line-height: 15px; font-size: 10px;"> </div>--}}
                        </td>
                    </tr>

                    <!--header END-->
                    <tr><td align="center" bgcolor="#f78031" style="border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #eff2f4;">
                            <table width="94%" border="0" cellspacing="0" cellpadding="0" class="table-mob">
                                <tr><td align="center">
                                        <a class="shop" href="{{$site}}/amazon?utm_source={{$nameOfMail}}&utm_medium={{$nameOfMail}}&utm_campaign={{$nameOfMail}}">
                                            <div class="mob_100" style="float: left; display: inline-block; width: 33%;">
                                                <table class="mob_100" width="100%" border="0" cellspacing="0" cellpadding="0" align="left" style="border-collapse: collapse;">
                                                    <tr><td align="center" style="line-height: 14px; padding: 0 27px;">
                                                            <!-- padding --><div style="height: 20px;"> </div>
                                                            <div style="line-height: 14px;">
                                                                <img style="display:block; line-height:0px; font-size:0px; border:0px;" src="https://www.chipi.co.il/images/media/index1/amazon_logo_newsletter.jpg" width="144" height="40" alt="icon" />
                                                            </div>
                                                            <!-- padding --><div class="bottom" style="height: 18px; line-height: 18px; font-size: 10px;"> </div>
                                                        </td></tr>
                                                </table>
                                            </div>
                                            <a/>
                                            <a class="shop" href="{{$site}}/aliexpress?utm_source={{$nameOfMail}}&utm_medium={{$nameOfMail}}&utm_campaign={{$nameOfMail}}">
                                                <div class="mob_100" style="float: left; display: inline-block; width: 33%;">
                                                    <table class="mob_100" width="100%" border="0" cellspacing="0" cellpadding="0" align="left" style="border-collapse: collapse;">
                                                        <tr><td align="center" style="line-height: 14px; padding: 0 27px;">
                                                                <!-- padding --><div style="height: 20px;"> </div>
                                                                <div style="line-height: 14px;">
                                                                    <img style="display:block; line-height:0px; font-size:0px; border:0px;" src="https://www.chipi.co.il/images/media/index1/ali_logo_newsletter.jpg" width="144" height="40" alt="icon" />
                                                                </div>
                                                                <!-- padding --><div class="bottom" style="height: 18px; line-height: 18px; font-size: 10px;"> </div>
                                                            </td></tr>
                                                    </table>
                                                </div>
                                                <a/>
                                                <a class="shop" href="{{$site}}/ebay?utm_source={{$nameOfMail}}&utm_medium={{$nameOfMail}}&utm_campaign={{$nameOfMail}}">
                                                    <div class="mob_100" style="float: left; display: inline-block; width: 33%;">
                                                        <table class="mob_100" width="100%" border="0" cellspacing="0" cellpadding="0" align="left" style="border-collapse: collapse;">
                                                            <tr>
                                                                <td align="center" style="line-height: 14px; padding: 0 27px;">
                                                                    <!-- padding --><div style="height: 20px;"> </div>
                                                                    <div style="line-height: 14px;">
                                                                        <img style="display:block; line-height:0px; font-size:0px; border:0px;" src="https://www.chipi.co.il/images/media/index1/ebay_logo_newsletter.jpg" width="144" height="40" alt="icon" />
                                                                    </div>
                                                                    <!-- padding --><div style="height: 18px; line-height: 18px; font-size: 10px;"> </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </a>
                                    </td></tr>
                            </table>
                        </td></tr>
                    <!--content 2 END-->
                    <tr>
                        <td align="center" bgcolor="#ffffff" style="border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #eff2f4; padding: 15px 10px 5px">
                            <ul style="display: inline-block; font-family: 'Raleway', sans-serif; font-size:20px; color:#000; line-height:20px; font-weight: bold;">
                                <li style="display: inline-block"><a href="https://www.chipi.co.il/ebay/search/women%20fashion/1?utm_source={{$nameOfMail}}&utm_medium={{$nameOfMail}}&utm_campaign={{$nameOfMail}}">נשים</a><span style="display: inline-block;margin-right: 5px;">/</span></li>
                                <li style="display: inline-block"><a href="https://www.chipi.co.il/ebay/search/men%20fashion/1?utm_source={{$nameOfMail}}&utm_medium={{$nameOfMail}}&utm_campaign={{$nameOfMail}}">גברים</a><span style="display: inline-block;margin-right: 5px;">/</span></li>
                                <li style="display: inline-block"><a href="https://www.chipi.co.il/ebay/search/mobile%20phone/1?utm_source={{$nameOfMail}}&utm_medium={{$nameOfMail}}&utm_campaign={{$nameOfMail}}">סלולאר</a><span style="display: inline-block;margin-right: 5px;">/</span></li>
                                <li style="display: inline-block"><a href="https://www.chipi.co.il/ebay/search/makeup/1?utm_source={{$nameOfMail}}&utm_medium={{$nameOfMail}}&utm_campaign={{$nameOfMail}}">איפור וטיפוח</a><span style="display: inline-block;margin-right: 5px;">/</span></li>
                                <li style="display: inline-block"><a href="https://www.chipi.co.il/ebay/search/jewelry/1?utm_source={{$nameOfMail}}&utm_medium={{$nameOfMail}}&utm_campaign={{$nameOfMail}}">תכשיטים</a><span style="display: inline-block;margin-right: 5px;">/</span></li>
                                <li style="display: inline-block"><a href="https://www.chipi.co.il/ebay/search/kids%20fashion/1?utm_source={{$nameOfMail}}&utm_medium={{$nameOfMail}}&utm_campaign={{$nameOfMail}}">ילדים</a></li>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" bgcolor="#ffffff" style="border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #eff2f4; padding: 15px 10px 5px">
                              <span class="descr">
                                  מצ'יפי אליך ,ריכוז מבצעי היום השווים ביותר מאתרי הסחר העולמיים
                              </span>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" bgcolor="#ffffff" style="border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #eff2f4;">
                            <span style="font-family: 'Raleway', sans-serif; font-size:14px; line-height:20px; color: red; padding: 5px;">***אתר צ'יפי עבר שדרוג,מומלץ לנקות את היסטוריית הדפדפן על מנת להנות מביצועים מלאים***</span>
                        </td>
                    </tr>
                {{--<tr>--}}
                {{--<td align="center" bgcolor="#ffffff" style="border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #eff2f4;">--}}
                {{--<span style="font-family: 'Raleway', sans-serif; font-size:14px; color:#000; line-height:20px; font-weight: bold;">--}}
                {{--Some text--}}
                {{--</span>--}}
                {{--</td>--}}
                {{--</tr>--}}
                {{--<tr>--}}
                {{--<td align="center" bgcolor="#ffffff" style="border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #eff2f4;">--}}
                {{--<span style="font-family: 'Raleway', sans-serif; font-size:14px; color:#000; line-height:20px; font-weight: bold;">--}}
                {{--Some text--}}
                {{--</span>--}}
                {{--</td>--}}
                {{--</tr>--}}
                {{--<tr>--}}
                {{--<td align="center" bgcolor="#ffffff" style="border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #eff2f4;">--}}
                {{--<span style="font-family: 'Raleway', sans-serif; font-size:14px; color:#000; line-height:20px; font-weight: bold;">--}}
                {{--Some text--}}
                {{--</span>--}}
                {{--</td>--}}
                {{--</tr>--}}
                <!--content 3 -->
                    <tr><td align="center" bgcolor="#ffffff" >
                            <table width="94%" border="0" cellspacing="0" cellpadding="0">
                                <tr><td align="center" class="table-mob">
                                        <!-- padding --><div style="height: 20px; line-height: 20px; font-size: 10px;"> </div>
                                        @foreach($products as $product)
                                            <div class="mob_100" style=" display: inline-block; width: 48%; padding: 0 5px;">
                                                <a class="item" href="{{$site}}/{{$product->siteName}}/product/{{$product->nameOfProduct}}/{{$product->productId}}?utm_source={{$nameOfMail}}&utm_medium={{$nameOfMail}}&utm_campaign={{$nameOfMail}}">
                                                    <table class="mob_100" width="100%" border="0" cellspacing="0" cellpadding="0" align="left" style="border-collapse: collapse;">
                                                        <tr><td align="center" style="line-height: 14px; padding: 0 10px;">
                                                                <img style="display:block; line-height:0px; font-size:0px; border:0px;" class="images_style" src="{{$product->pic}}" width="210" height="250">
                                                                <!-- padding --><div style="height: 18px; line-height: 18px; font-size: 10px;"> </div>
                                                                <div style="line-height: 21px;">
                                                            <span style="font-family: 'Raleway', sans-serif; font-size:14px; color:#000; line-height:20px; font-weight: bold;">
                                                                {{$product->nameOfProduct}}
                                                            </span>
                                                                </div>
                                                                <div align="center">
                                                                    <span style="font-family: 'Lato', sans-serif; font-size:26px; color:#f78031; font-weight: bold; line-height: 44px;">₪ {{$product->price}}</span>
                                                                </div>
                                                            </td></tr>
                                                    </table>
                                                    <!-- padding --><div style="height: 20px; line-height: 20px; font-size: 10px;"> </div>

                                                </a>
                                            </div>
                                        @endforeach
                                    </td></tr>
                                <tr><td><!-- padding --><div style="height: 40px; line-height: 40px; font-size: 10px;"> </div></td></tr>
                            </table>
                        </td></tr>
                    <!--content 3 END-->
                    <!--button -->
                    <tr><td align="center" bgcolor="#ffffff" style="border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #eff2f4;">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr><td align="center">
                                        <a class="button ebayDeals"  style="background-color: #ec6409; padding: 10px; color: #fff; text-decoration: none;border: 1px solid #ec6409"  href="{{$site}}/ebay/oneDayDeals?utm_source={{$nameOfMail}}&utm_medium={{$nameOfMail}}&utm_campaign={{$nameOfMail}}">את מבצעי צ'יפי כאסח כבר ראית ? לחץ כאן</a>
                                    </td>
                                </tr>
                            </table>
                            <!-- padding --><div style="height: 30px; line-height: 30px; font-size: 10px;"> </div>
                        </td></tr>
                    <!--button END-->
                    <!--brands -->
                    <tr><td align="center" bgcolor="#fbfbfb" style="border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #eff2f4;">
                            <table width="94%" border="0" cellspacing="0" cellpadding="0">
                                <tr><td align="center">
                                        <div class="mob_100" style="float: left; display: inline-block; width: 25%;">
                                            <table class="mob_100" width="100%" border="0" cellspacing="0" cellpadding="0" align="left" style="border-collapse: collapse;">
                                                <tr><td align="center" style="line-height: 14px; padding: 0 24px;">
                                                        <!-- padding --><div style="height: 30px; line-height: 30px; font-size: 10px;"> </div>
                                                        <div style="line-height: 14px;">
                                                            <img style="display:block; line-height:0px; font-size:0px; border:0px;" src="https://www.chipi.co.il/images/media/index1/service1.png" width="40" height="40" alt="icon">
                                                        </div>
                                                        <!-- padding --><div style="height: 18px; line-height: 18px; font-size: 10px;"> </div>
                                                         <span style="font-family: 'Raleway', Arial, sans-serif; font-size:20px; color:#2b3c4d; line-height:24px; font-weight: bold;">
                                                               קניה מרוכזת
                                                            </span>
                                                        <div style="line-height: 21px;">

                                                            <span style="font-family: 'Lato', sans-serif; font-size:14px; color:#757575; line-height:24px; font-weight: 300;">
                                                                כל אתרי הסחר הגדולים במקום אחד
                                                            </span>
                                                        </div>
                                                    </td></tr>
                                            </table>
                                        </div>

                                        <div class="mob_100" style="float: left; display: inline-block; width: 25%;">
                                            <table class="mob_100" width="100%" border="0" cellspacing="0" cellpadding="0" align="left" style="border-collapse: collapse;">
                                                <tr><td align="center" style="line-height: 14px; padding: 0 24px;">
                                                        <!-- padding --><div style="height: 30px; line-height: 30px; font-size: 10px;"> </div>
                                                        <div style="line-height: 14px;">
                                                            <img style="display:block; line-height:0px; font-size:0px; border:0px;" src="https://www.chipi.co.il/images/media/index1/service2.png" width="40" height="40" alt="icon">
                                                        </div>
                                                        <!-- padding --><div style="height: 18px; line-height: 18px; font-size: 10px;"> </div>
                                                        <span style="font-family: 'Raleway', Arial, sans-serif; font-size:20px; color:#2b3c4d; line-height:24px; font-weight: bold;">
                                                              רכישה בכל כרטיס אשראי
                                                        </span>
                                                        <div style="line-height: 21px;">

                                                            <span style="font-family: 'Lato', sans-serif; font-size:14px; color:#757575; line-height:24px; font-weight: 300;">
                                                                רכישה בכל כרטיס אשראי ישראלי/נטען/בינלאומי
                                                            </span>
                                                        </div>
                                                    </td></tr>
                                            </table>
                                        </div>

                                        <div class="mob_100" style="float: left; display: inline-block; width: 25%;">
                                            <table class="mob_100" width="100%" border="0" cellspacing="0" cellpadding="0" align="left" style="border-collapse: collapse;">
                                                <tr><td align="center" style="line-height: 14px; padding: 0 24px;">
                                                        <!-- padding --><div style="height: 30px; line-height: 30px; font-size: 10px;"> </div>
                                                        <div style="line-height: 14px;">
                                                            <img style="display:block; line-height:0px; font-size:0px; border:0px;" src="https://www.chipi.co.il/images/media/index1/service3.png" width="40" height="40" alt="icon">
                                                        </div>
                                                        <!-- padding --><div style="height: 18px; line-height: 18px; font-size: 10px;"> </div>
                                                        <span style="font-family: 'Raleway', Arial, sans-serif; font-size:20px; color:#2b3c4d; line-height:24px; font-weight: bold;">
                                                                אתר מאובטח
                                                       </span>
                                                        <div style="line-height: 21px;">

                                                            <span style="font-family: 'Lato', sans-serif; font-size:14px; color:#757575; line-height:24px; font-weight: 300;">
                                                                אתר צ'יפי מאובטח בפרוטוקול מחמיר
                                                            </span>
                                                        </div>
                                                    </td></tr>
                                            </table>
                                        </div>

                                        <div class="mob_100" style="float: left; display: inline-block; width: 25%;">
                                            <table class="mob_100" width="100%" border="0" cellspacing="0" cellpadding="0" align="left" style="border-collapse: collapse;">
                                                <tr><td align="center" style="line-height: 14px; padding: 0 24px;">
                                                        <!-- padding --><div style="height: 30px; line-height: 30px; font-size: 10px;"> </div>
                                                        <div style="line-height: 14px;">
                                                            <img style="display:block; line-height:0px; font-size:0px; border:0px;" src="https://www.chipi.co.il/images/media/index1/service4.png" width="40" height="40" alt="icon">
                                                        </div>
                                                        <!-- padding --><div style="height: 18px; line-height: 18px; font-size: 10px;"> </div>
                                                        <span style="font-family: 'Raleway', Arial, sans-serif; font-size:20px; color:#2b3c4d; line-height:24px; font-weight: bold;">
                                                               שירות לקוחות ישראלי
                                                        </span>
                                                        <div style="line-height: 21px;">
                                                            <span style="font-family: 'Lato', sans-serif; font-size:14px; color:#757575; line-height:24px; font-weight: 300;">
                                                                האתר היחיד שפועל עבורכם מול המוכרים
                                                            </span>
                                                        </div>
                                                    </td></tr>
                                            </table>
                                        </div>

                                    </td></tr>
                                <tr><td><!-- padding --><div style="height: 28px; line-height: 28px; font-size: 10px;"> </div></td></tr>
                            </table>
                        </td></tr>
                    <!--brands END-->
                    <!--text after button -->
                    <tr><td align="center" bgcolor="#ffffff" style="border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: #eff2f4;">

                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr><td align="center" style="padding: 0 10px">
                                        <p>הודעה זאת נשלחה אליך בעקבות הרשמתך לאתר במידה והינך מעונין להסיר עצמך מרשימת התפוצה לחץ כאן</p>
                                    </td>
                                </tr>
                            </table>

                        </td></tr>
                    <!--button -->
                    <tr><td align="center" bgcolor="#ffffff" >
                            <!-- padding --><div style="height: 30px; line-height: 30px; font-size: 10px;"> </div>
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr><td align="center">
                                        <a class="button" style="background-color: #ec6409; padding: 10px; color: #fff; text-decoration: none;border: 1px solid #ec6409"  href="{{$site}}/unsubscribe/{{$email}}?utm_source={{$nameOfMail}}&utm_medium={{$nameOfMail}}&utm_campaign={{$nameOfMail}}">הסר מרשימת התפוצה</a>
                                    </td>
                                </tr>
                            </table>
                            <!-- padding --><div style="height: 30px; line-height: 30px; font-size: 10px;"> </div>
                        </td></tr>
                    <!--button END-->
                    <!--footer -->
                    <tr><td class="iage_footer" align="center" bgcolor="#ffffff">
                            <!-- padding --><div style="height: 30px; line-height: 30px; font-size: 10px;"> </div>

                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr><td align="center">
				<span style="font-family: Arial, Helvetica, sans-serif; font-size: 13px; color: #96a5b5;">

                                                                         כל הזכויות שמורות לצ'יפי קניות ברשת בע"מ © 2014.טל"ח

				</span>
                                    </td></tr>
                            </table>

                            <!-- padding --><div style="height: 30px; line-height: 30px; font-size: 10px;"> </div>
                        </td></tr>
                    <!--footer END-->
                    <tr><td>
                            {{--<!-- padding --><div style="height: 80px; line-height: 80px; font-size: 10px;"> </div>--}}
                        </td></tr>
                </table>
            </td></tr>
    </table>

</div>
</body>
</html>