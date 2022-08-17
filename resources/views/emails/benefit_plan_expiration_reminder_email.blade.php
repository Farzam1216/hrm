<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=320, initial-scale=1" />
    <style type="text/css">

        /* ----- Client Fixes ----- */

        /* Force Outlook to provide a "view in browser" message */
        #outlook a {
            padding: 0;
        }

        /* Force Hotmail to display emails at full width */
        .ReadMsgBody {
            width: 100%;
        }

        .ExternalClass {
            width: 100%;
        }

        /* Force Hotmail to display normal line spacing */
        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }


        /* Prevent WebKit and Windows mobile changing default text sizes */
        body, table, td, p, a, li, blockquote {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        /* Remove spacing between tables in Outlook 2007 and up */
        table, td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        /* Allow smoother rendering of resized image in Internet Explorer */
        img {
            -ms-interpolation-mode: bicubic;
        }

        /* ----- Reset ----- */

        html,
        body,
        .body-wrap,
        .body-wrap-cell {
            margin: 0;
            padding: 0;
            background: #ffffff;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            color: #464646;
            text-align: left;
        }

        img {
            border: 0;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }

        table {
            border-collapse: collapse !important;
        }

        td, th {
            text-align: left;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            color: #464646;
            line-height:1.5em;
        }

        b a,
        .footer a {
            text-decoration: none;
            color: #464646;
        }

        a.blue-link {
            color: blue;
            text-decoration: underline;
        }

        /* ----- General ----- */

        td.center {
            text-align: center;
        }

        .left {
            text-align: left;
        }

        .body-padding {
            padding: 24px 40px 40px;
        }

        .border-bottom {
            border-bottom: 1px solid #D8D8D8;
        }

        table.full-width-gmail-android {
            width: 100% !important;
        }


        /* ----- Header ----- */
        .header {
            font-weight: bold;
            font-size: 16px;
            line-height: 16px;
            height: 16px;
            padding-top: 19px;
            padding-bottom: 7px;
        }

        .header a {
            color: #464646;
            text-decoration: none;
        }

        /* ----- Footer ----- */

        .footer a {
            font-size: 12px;
        }
    </style>

    <style type="text/css" media="only screen and (max-width: 650px)">
        @media only screen and (max-width: 650px) {
            * {
                font-size: 16px !important;
            }

            table[class*="w320"] {
                width: 320px !important;
            }

            td[class="mobile-center"],
            div[class="mobile-center"] {
                text-align: center !important;
            }

            td[class*="body-padding"] {
                padding: 20px !important;
            }

            td[class="mobile"] {
                text-align: right;
                vertical-align: top;
            }
        }
    </style>

</head>
<body style="padding:0; margin:0; display:block; background:#ffffff; -webkit-text-size-adjust:none">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td valign="top" align="left" width="100%" style="background:repeat-x url(https://www.filepicker.io/api/file/al80sTOMSEi5bKdmCgp2) #f9f8f8;">
            <center>

                <table class="w320 full-width-gmail-android" bgcolor="#f9f8f8" background="https://www.filepicker.io/api/file/al80sTOMSEi5bKdmCgp2" style="background-color:transparent" cellpadding="0" cellspacing="0" border="0" width="100%">
                    <tr>
                        <td width="100%" height="48" valign="top">
                            <!--[if gte mso 9]>
                            <v:rect xmlns:v="urn:schemas-microsoft-com:vml" fill="true" stroke="false" style="mso-width-percent:1000;height:49px;">
                                <v:fill type="tile" src="https://www.filepicker.io/api/file/al80sTOMSEi5bKdmCgp2" color="#ffffff" />
                                <v:textbox inset="0,0,0,0">
                            <![endif]-->
                            <table class="full-width-gmail-android" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td class="header center" width="100%">
                                        <h4>
                                            Benefit Plans are Up for Renewal
                                        </h4>
                                    </td>
                                </tr>
                            </table>
                            <!--[if gte mso 9]>
                            </v:textbox>
                            </v:rect>
                            <![endif]-->
                        </td>
                    </tr>
                </table>

                <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff">
                    <tr>
                        <td align="center">
                            <center>
                                <table class="w320" cellspacing="0" cellpadding="0" width="500">
                                    <tr>
                                        <td class="body-padding mobile-padding">

                                            <table cellspacing="0" cellpadding="0" width="100%">
                                                <tr>
                                                    <td class="left" style="padding-bottom:40px; text-align:left;">
                                                        Hi {{$admin->firstname}},<br>
                                                        This is a reminder that you have benefit plans ending on {{$end_date}} and they'll soon need to be updated.
                                                    </td>
                                                </tr>
                                            </table>

                                            <table cellspacing="0" cellpadding="0" width="100%">
                                                <tr>
                                                    <td>
                                                        <b>Plan</b>
                                                    </td>
                                                    <td>
                                                        <b>Expiry Date</b>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="border-bottom" height="5"></td>
                                                    <td class="border-bottom" height="5"></td>
                                                </tr>
                                                @foreach($plans as $plan)
                                                <tr>
                                                    <td style="padding-top:5px;">
                                                        {{$plan->name}}
                                                    </td>
                                                    <td style="padding-top:5px;">
                                                        {{$plan->end_date}}
                                                    </td>
                                                </tr>
                                                    @endforeach
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </center>
                        </td>
                    </tr>
                </table>
                <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff">
                    <tr>
                        <td align="center">
                            <center>
                                <table class="w320" cellspacing="0" cellpadding="0" width="500">
                                    <tr>
                                        <td class="body-padding mobile-padding">

                                            <table cellspacing="0" cellpadding="0" width="100%">
                                                <tr>
                                                    <td class="left" style="text-align:left;">
                                                        <b>What Now</b><br>
                                                        <ol>
                                                            <li>
                                                                Go to Settings >> Benefits >> Plans.
                                                            </li>
                                                            <li>
                                                                Make notes of plans that are ending soon.
                                                            </li>
                                                            <li>
                                                                Click on plan name.
                                                            </li>
                                                            <li>
                                                                Update the end date to the new end date.
                                                            </li>
                                                            <li>
                                                                Repeat this process until all ending plans are updated with a new date.
                                                            </li>
                                                        </ol>
                                                    </td>
                                                </tr>
                                            </table>
                                            <table cellspacing="0" cellpadding="0" width="100%">
                                                <tr>
                                                    <center>
                                                    <td class="mobile-center" align="left" style="padding:2px 0;">
                                                        <div class="mobile-center" align="left"><!--[if mso]>
                                                            <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" style="height:38px;v-text-anchor:middle;width:190px;" arcsize="11%" strokecolor="#407429" fill="t">
                                                                <v:fill type="tile" src="https://www.filepicker.io/api/file/N8GiNGsmT6mK6ORk00S7" color="#41CC00" />
                                                                <w:anchorlock/>
                                                                <center style="color:#ffffff;font-family:sans-serif;font-size:17px;font-weight:bold;">View Account</center>
                                                            </v:roundrect>
                                                            <![endif]--><a target="_blank" href="{{ url($URL) }}" style="background-color:#41CC00;background-image:url(https://www.filepicker.io/api/file/N8GiNGsmT6mK6ORk00S7);border:1px solid #407429;border-radius:4px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:17px;font-weight:bold;text-shadow: -1px -1px #47A54B;line-height:38px;text-align:center;text-decoration:none;width:190px;-webkit-text-size-adjust:none;mso-hide:all;">Update Benefit Plans</a></div>
                                                    </td>
                                                    </center>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </center>
                        </td>
                    </tr>
                </table>

            </center>
        </td>
    </tr>
</table>
</body>
</html>