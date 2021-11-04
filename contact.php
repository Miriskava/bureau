<?php
if (isset ($_POST['mail'])) {
  $to = "miriskava@yandex.ru";//адрес почту куда будут приходить сообщения
  $from = $_POST['mail'];
  $subject = "Заполнена контактная форма на сайте ".$_SERVER['HTTP_REFERER'];
  $message = "\nИмя заказчика: ".$_POST['nameN']."\nEmail заказчика: ".$_POST['mail']."\nТелефон заказчика: ".$_POST['phone']."\nСообщение: ".$_POST['text_message']."\n\nАдрес сайта: ".$_SERVER['HTTP_REFERER'];
  $boundary = md5(date('r', time()));
  $filesize = '';
  $headers .= "From: " . $from . "\r\n";
  $headers .= "Reply-To: " . $from . "\r\n";
  $headers = "MIME-Version: 1.0\r\n";
  $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";
  $message="
Content-Type: multipart/mixed; boundary=\"$boundary\"
 
--$boundary
Content-Type: text/plain; charset=\"utf-8\"
Content-Transfer-Encoding: 7bit
 
$message";
  for($i=0;$i<count($_FILES['input__file']['name']);$i++) {
     if(is_uploaded_file($_FILES['input__file']['tmp_name'][$i])) {
         $attachment = chunk_split(base64_encode(file_get_contents($_FILES['input__file']['tmp_name'][$i])));
         $filename = $_FILES['input__file']['name'][$i];
         $filetype = $_FILES['input__file']['type'][$i];
         $filesize += $_FILES['input__file']['size'][$i];
         $message.="

--$boundary
Content-Type: \"$filetype\"; name=\"$filename\"
Content-Transfer-Encoding: base64
Content-Disposition: attachment; filename=\"$filename\"

$attachment";
     }
   }
   $message.="
--$boundary--";

  if ($filesize < 10000000) { // проверка на общий размер всех файлов. Многие почтовые сервисы не принимают вложения больше 10 МБ
    mail($to, $subject, $message, $headers);
    echo $_POST['nameN'].', Ваше сообщение получено, спасибо!';
  } else {
    echo 'Извините, письмо не отправлено. Размер всех файлов превышает 10 МБ.';
  }
}
?>
