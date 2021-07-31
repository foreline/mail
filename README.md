# Mail
PHP Mail lib. Simple lib for sending emails from PHP

Example 1. Simple mail sending:
```php
<?php
require_once 'mail.class.php';

(new Mail)->send('email@example.com', 'Title', 'Mail body');
```

Example 2. Sending mail to multiple addressed:
```php
<?php
require_once 'mail.class.php';
(new Mail)->send(['1@example.com','2@example.com'], 'Title', 'Mail body');
```

Example 3. Error handling:
```php
<?php
require_once 'mail.class.php';

$mail = new Mail;
if ( !$mail->send('email@example.com', 'Title', 'Mail body') ) {
    echo 'Error: ' . $mail->errorMessage;
}
```