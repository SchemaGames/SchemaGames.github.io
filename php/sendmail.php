<?php
$sendMailAddress = 'benjam.elliott@gmail.com';
$from = $_POST['from'];
$subject = $_POST['subject'];
$message = '<b>Message From:</b> ' . $from . "<br><br>" .
    '<p>' . $_POST['message'] . '</p>';
$headers = array('Delivery-date: ' . date("r"),
    'MIME-Version: 1.0',
    'Content-type: text/html;charset=iso-8859-1',
    'X-Mailer: PHP/' . PHP_VERSION,
    'From: "Contact Form" <support@schemagames.com>',
    'Reply-To: ' . $from,
    'Sender: ' . $sendMailAddress );
$headers = implode("\r\n", $headers);
$mailSuccess = mail($sendMailAddress, $subject, $message, $headers);
if($mailSuccess)
{
    echo 1;
}
else
{
    echo 0;
}