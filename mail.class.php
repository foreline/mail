<?php
/**
 * Класс для отправки почтовых уведомлений
 *
 * @package lib
 * @subpackage mail
 * @author dima@foreline.ru
 */

class Mail {

    /** @var string Сообщение об ошибке */
    public string $errorMessage = '';

    /** @var string Кодировка для отправки почты */
    public string $charset = 'utf-8';

    /** @var string Язык отправки письма (для формата html) */
    public string $lang = 'ru';

    /**
     * Конструктор класса
     *
     * @return void
     */

    public function __construct()
    {

    }

    /**
     * Отсылает почтовое сообщение
     *
     * @param array|string @email Массив или строка с адресом
     * @param string $subject Тема сообщения
     * @param string $message Сообщение
     * @param array $arHeaders Заголовки сообщения
     *
     * @return bool $result Результат выполнения метода
     */

    public function send($email, string $subject = 'Без темы', string $message = '', array $arHeaders = []): bool
    {
        $mailHeaders = [];

        if ( !empty($arHeaders['FROM']) ) {
            $mailHeaders[] = 'From: ' . '=?' . $this->charset . '?B?' . base64_encode($arHeaders['FROM']) . '?=' . ' <' . $arHeaders['FROM'] . '>';
        } else {
            $mailHeaders[] = 'From: ' . str_replace('www.', '', $_SERVER['HTTP_HOST']) . ' <noreply@' . str_replace('www.', '', $_SERVER['HTTP_HOST']) . '>';
        }

        $mailHeaders['CONTENT-TYPE'] = 'Content-Type: text/html; charset=' . $this->charset;

        //$mailHeaders = array_merge($mailHeaders, $arHeaders);
        if ( is_array($arHeaders) && 0 < count($arHeaders) ) {
            foreach ( $arHeaders as $key => $value ) {
                if ( $key == 'FROM' ) {
                    continue;
                }
                $mailHeaders[] = $key . ': ' . $value;
            }
        }

        $mailSubject = '=?' . mb_strtoupper($this->charset) . '?B?' . base64_encode($subject) . '?=';

        $arEmails = is_array($email) ? $email : [$email];

        $errors = false;

        $headers = implode("\r\n", $mailHeaders) . "\r\n";

        $mailMessage = '
            <html lang="' . $this->lang . '">
                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=' . $this->charset . '" />
                    <title>' . $subject . '</title>
                </head>
                <body>
                ' . $message . '
                </body>
            </html>
            ';

        foreach ( $arEmails as $email ) {

            if ( !mail($email, $mailSubject, $mailMessage, $headers) ) {
                $this->errorMessage = 'Ошибка при отправке почтового сообщения.';
                $errors = true;
            }
        }

        if ( $errors ) {
            return false;
        }

        return true;
    }


}
