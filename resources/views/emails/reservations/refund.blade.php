<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='x-apple-disable-message-reformatting'>
    <title></title>
    <link href='https://fonts.googleapis.com/css?family=Nunito+Sans:200,300,400,600,700,800,900' rel='stylesheet'>
    <style>
        html,
        body {
            margin: 0 auto !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
            background: #f1f1f1;
        }

        * {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }

        div[style*='margin: 16px 0'] {
            margin: 0 !important;
        }

        table,
        td {
            mso-table-lspace: 0pt !important;
            mso-table-rspace: 0pt !important;
        }

        table {
            border-spacing: 0 !important;
            border-collapse: collapse !important;
            table-layout: fixed !important;
            margin: 0 auto !important;
        }

        img {
            -ms-interpolation-mode: bicubic;
        }

        a {
            text-decoration: none;
        }

        *[x-apple-data-detectors],
        .unstyle-auto-detected-links *,
        .aBn {
            border-bottom: 0 !important;
            cursor: default !important;
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        .a6S {
            display: none !important;
            opacity: 0.01 !important;
        }

        .im {
            color: inherit !important;
        }

        img.g-img+div {
            display: none !important;
        }

        @media only screen and (min-device-width: 320px) and (max-device-width: 374px) {
            u~div .email-container {
                min-width: 320px !important;
            }
        }

        @media only screen and (min-device-width: 375px) and (max-device-width: 413px) {
            u~div .email-container {
                min-width: 375px !important;
            }
        }

        @media only screen and (min-device-width: 414px) {
            u~div .email-container {
                min-width: 414px !important;
            }
        }

        .primary {
            background: #f5564e;
        }

        .bg_white {
            background: #ffffff;
        }

        .bg_light {
            background: #fafafa;
        }

        .bg_black {
            background: #000000;
        }

        .bg_dark {
            background: rgba(0, 0, 0, .8);
        }

        .email-section {
            padding: 2.5em;
        }

        .btn {
            padding: 5px 15px;
            display: inline-block;
        }

        .btn.btn-primary {
            border-radius: 5px;
            background: #f5564e;
            color: #ffffff;
        }

        .btn.btn-white {
            border-radius: 5px;
            background: #ffffff;
            color: #000000;
        }

        .btn.btn-white-outline {
            border-radius: 5px;
            background: transparent;
            border: 1px solid #fff;
            color: #fff;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Nunito Sans', sans-serif;
            color: #000000;
            margin-top: 0;
        }

        body {
            font-family: 'Nunito Sans', sans-serif;
            font-weight: 400;
            font-size: 15px;
            line-height: 1.8;
            color: rgba(0, 0, 0, .4);
        }

        a {
            color: #f5564e;
        }

        table {}

        .logo h1 {
            margin: 0;
        }

        .logo h1 a {
            color: #000;
            font-size: 20px;
            font-weight: 700;
            text-transform: uppercase;
            font-family: 'Nunito Sans', sans-serif;
        }

        .navigation {
            padding: 0;
        }

        .navigation li {
            list-style: none;
            display: inline-block;
            margin-left: 5px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .navigation li a {
            color: rgba(0, 0, 0, .6);
        }

        .hero {
            position: relative;
            z-index: 0;
        }

        .hero .overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            content: '';
            width: 100%;
            background: #000000;
            z-index: -1;
            opacity: .3;
        }

        .hero .icon {}

        .hero .icon a {
            display: block;
            width: 60px;
            margin: 0 auto;
        }

        .hero .text {
            color: rgba(255, 255, 255, .8);
            padding: 0 4em;
        }

        .hero .text h2 {
            color: #ffffff;
            font-size: 40px;
            margin-bottom: 0;
            line-height: 1.2;
            font-weight: 900;
        }

        .heading-section {}

        .heading-section h2 {
            color: #000000;
            font-size: 24px;
            margin-top: 0;
            line-height: 1.4;
            font-weight: 700;
        }

        .heading-section .subheading {
            margin-bottom: 20px !important;
            display: inline-block;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: rgba(0, 0, 0, .4);
            position: relative;
        }

        .heading-section .subheading::after {
            position: absolute;
            left: 0;
            right: 0;
            bottom: -10px;
            content: '';
            width: 100%;
            height: 2px;
            background: #f5564e;
            margin: 0 auto;
        }

        .heading-section-white {
            color: rgba(255, 255, 255, .8);
        }

        .heading-section-white h2 {
            font-family: 'Nunito Sans', sans-serif;
            line-height: 1;
            padding-bottom: 0;
        }

        .heading-section-white h2 {
            color: #ffffff;
        }

        .heading-section-white .subheading {
            margin-bottom: 0;
            display: inline-block;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: rgba(255, 255, 255, .4);
        }

        .icon {
            text-align: center;
        }

        .icon img {}

        .services {
            background: rgba(0, 0, 0, .03);
        }

        .text-services {
            padding: 10px 10px 0;
            text-align: center;
        }

        .text-services h3 {
            font-size: 16px;
            font-weight: 600;
        }

        .services-list {
            padding: 0;
            margin: 0 0 10px 0;
            width: 100%;
            float: left;
        }

        .services-list .text {
            width: 100%;
            float: right;
        }

        .services-list h3 {
            margin-top: 0;
            margin-bottom: 0;
            font-size: 18px;
        }

        .services-list p {
            margin: 0;
        }

        .text-tour {
            padding-top: 10px;
        }

        .text-tour h3 {
            margin-bottom: 0;
        }

        .text-tour h3 a {
            color: #000;
        }

        .text-services .meta {
            text-transform: uppercase;
            font-size: 14px;
        }

        .text-testimony .name {
            margin: 0;
        }

        .text-testimony .position {
            color: rgba(0, 0, 0, .3);
        }

        .counter {
            width: 100%;
            position: relative;
            z-index: 0;
        }

        .counter .overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            content: '';
            width: 100%;
            background: #000000;
            z-index: -1;
            opacity: .3;
        }

        .counter-text {
            text-align: center;
        }

        .counter-text .num {
            display: block;
            color: #ffffff;
            font-size: 34px;
            font-weight: 700;
        }

        .counter-text .name {
            display: block;
            color: rgba(255, 255, 255, .9);
            font-size: 13px;
        }

        ul.social {
            padding: 0;
        }

        ul.social li {
            display: inline-block;
        }

        .footer {
            color: rgba(255, 255, 255, .5);
        }

        .footer .heading {
            color: #ffffff;
            font-size: 20px;
        }

        .footer ul {
            margin: 0;
            padding: 0;
        }

        .footer ul li {
            list-style: none;
            margin-bottom: 10px;
        }

        .footer ul li a {
            color: rgba(255, 255, 255, 1);
        }

        @media screen and (max-width: 500px) {
            .icon {
                text-align: left;
            }

            .text-services {
                padding-left: 0;
                padding-right: 20px;
                text-align: left;
            }
        }

    </style>
</head>

<body width='100%' style='margin: 0; padding: 0 !important; mso-line-height-rule: exactly; background-color: #222222;'>
    <center style='width: 100%; background-color: #f1f1f1;'>
        <div
            style='display: none; font-size: 1px;max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;'>
            &zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;
        </div>
        <div style='max-width: 600px; margin: 0 auto;' class='email-container'>
            <table align='center' role='presentation' cellspacing='0' cellpadding='0' border='0' width='100%'
                style='margin: auto;'>
                <tr>
                    <td valign='top' class='bg_white' style='padding: 1em 2.5em;'>
                        <table role='presentation' border='0' cellpadding='0' cellspacing='0' width='100%'>
                            <tr>
                                <td width='40%' class='logo' style='text-align: center;'><img
                                        src='https://world-adventures.us/img/logo_oso.png' alt=''></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td valign='middle' class='hero bg_white'
                        style='background-image: url(https://world-adventures.us/photos/18.jpg); background-size: cover; height: 400px;'>
                        <div class='overlay'></div>
                        <table>
                            <tr>
                                <td>
                                    <div class='text' style='text-align: center;'>
                                        <h2>Refund</h2>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class='bg_dark email-section' style='text-align:justify;'>
                        <div class='heading-section heading-section-white'>
                            <h2>Dear Customer: {{ $reservation->nombre }} {{ $reservation->apellidos }}</h2>
                            <p>We hope this email finds you and yours safe.<br><br>In response to the Coronavirus
                                (COVID-19) Crisis now unfolding, World Adventures has paused normal operations.
                                <br><br>Due to the nature of this unprecedented pandemic, we have had to close our call
                                centers in Florida,Mexico and Spain. This may cause heavy delays in our response to our
                                clients.
                                We apologize for this delay and hope that you can understand that this global tragedy
                                has affected the travel industry globally.
                                We are working hard to ensure that all changes, cancellations and refunds are addressed.
                                <br><br>We have received your request for a refund. If you have initiated a dispute with
                                your Bank in regards to this amount, please advise us of same so that we may inform our
                                bank and yours.
                                In this case, you do not need to fill out the attached Refund Request, now that your
                                refund will be credited to you through your Bank/Merchant Dispute.
                                <br><br><strong style='color: #f4e137;'>IF YOU HAVE NOT FILED A BANK/MERCHANT DISPUTE,
                                    kindly fill out the attached form and forward to us along with the corresponding
                                    Driver's License.</strong><br><br>
                                Thank you for your patience during these difficult times. It's uncertain when travel
                                restrictions will lift, but once they do; we welcome you to our fabulous ATV
                                Adventure!<br><br>
                                Warm Regards,<br><br>Priscilla<br><br>Business Manager</p>
                            <p style='text-align: center;'>
                                <a href='https://world-adventures.es/refund' class='btn btn-primary'>Go to Refund</a>
                            </p>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </center>
</body>

</html>
