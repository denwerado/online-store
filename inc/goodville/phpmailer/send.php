<?php
//Phpmailer files
require dirname(__FILE__) . '/PHPMailer.php';
require dirname(__FILE__) . '/SMTP.php';
require dirname(__FILE__) . '/Exception.php';



/**
 * The formation of the letter itself
 */
$title = "Goodville Boots. Your code";
$body = '<!DOCTYPE html>
        <html>
            <head>
                <!-- General styles, not used by all email clients -->
                <style type="text/css" media="all">
                    a {
                        text-decoration: none;
                        color: #0088cc;
                    }
                    hr {
                        border-top: 1px solid #999;
                    }
                </style>
            </head>

            <!-- KEEP THE LAYOUT SIMPLE: THOSE ARE SERVICE MESSAGES. -->
            <body style="margin: 0; padding: 0;">

                <!-- Top title with dark background -->
                <table style="background-color: #444; width: 100%;" cellspacing="0" cellpadding="0">
                    <tr>
                        <td style="padding: 20px; text-align: center; font-family: sans-serif; color: #fff; font-size: 28px">
                            Goodville Boots
                        </td>
                    </tr>
                </table>

                <!-- Main table 100% wide with background color #eee -->    
                <table style="background-color: #eee; width: 100%;">
                    <tr>
                        <td align="center" style="padding: 15px;">

                            <!-- Content table with backgdound color #fff, width 500px -->
                            <table style="background-color: #fff; max-width: 600px; width: 100%; border: 1px solid #ddd;">
                                <tr>
                                    <td style="padding: 15px; color: #333; font-size: 16px; font-family: sans-serif">
                                        <!-- The "message" tag below is replaced with one of confirmation, welcome or goodbye messages -->
                                        <!-- Messages content can be configured on Newsletter List Building panels --> 

                                        <h1>Your code: ' . $coupon_code . '</h1>

                                        <hr>
                                        <!-- Signature if not already added to single messages (surround with <p>) -->
                                        <p>
                                            <small>
                                                <a href="http://artisanoils.dy-design.com/">http://artisanoils.dy-design.com/</a><br>
                                                Goodville Boots<br>
                                                46 Brookhaven ln Glenmont, NY 12077
                                            </small>
                                        </p>
                                        

                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>
                </table>
            </body>
        </html>
    ';



/**
 * PHPMailer Settings
 */
$mail = new PHPMailer\PHPMailer\PHPMailer();
try {
    $mail->isSMTP();   
    $mail->CharSet = "UTF-8";
    $mail->SMTPAuth   = true;
    $mail->SMTPDebug = 2;
    $mail->Debugoutput = function($str, $level) {$GLOBALS['status'][] = $str;};

    //Your Mail Settings
    $mail->Host       = ''; // SMTP сервера вашей почты
    $mail->Username   = ''; // Логин на почте
    $mail->Password   = ''; // Пароль на почте
    $mail->SMTPSecure = 'ssl';
    $mail->Port       = 465;
    $mail->setFrom('', 'Goodville Boots'); // Адрес самой почты и имя отправителя

    //The recipient of the letter
    $mail->addAddress($cliEmail);  
    //$mail->addAddress('youremail@gmail.com'); // Ещё один, если нужен

    //Attaching files to an email
    if (!empty($file['name'][0])) {
        for ($ct = 0; $ct < count($file['tmp_name']); $ct++) {
            $uploadfile = tempnam(sys_get_temp_dir(), sha1($file['name'][$ct]));
            $filename = $file['name'][$ct];
            if (move_uploaded_file($file['tmp_name'][$ct], $uploadfile)) {
                $mail->addAttachment($uploadfile, $filename);
                $rfile[] = "Файл $filename прикреплён";
            } else {
                $rfile[] = "Не удалось прикрепить файл $filename";
            }
        }   
    }

    //Sending a message
    $mail->isHTML(true);
    $mail->Subject = $title;
    $mail->Body = $body;    

    //Checking the poisoning of the message
    if ($mail->send()) {
        $result = "success";
        $message = $messageSuccess;
    } 
    else {
        $result = "error";
        $message = $messageError;
    }

}catch (Exception $e) {
    $result = "error";
    $message = $messageError;
    $status = "Сообщение не было отправлено. Причина ошибки: {$mail->ErrorInfo}";
}


// Отображение результата
//echo json_encode(["result" => $result, "resultfile" => $rfile, "status" => $status]);
echo json_encode(["result" => $result, 'message'=>$message, "status" => $status]);