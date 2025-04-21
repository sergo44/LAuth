<?php

declare(strict_types=1);

namespace La\Validators;

use La\Result;

class EmailExtendedValidator
{
    /**
     * Адрес электронной почты, который нужно проверить
     * @var string
     */
    protected string $email;

    /**
     * Конструктор класса
     * @param string $email
     */
    public function __construct(string $email)
    {
        $this->email = $email;
    }

    /**
     * Проверяет существование электронной почты.
     *
     * @param Result|null $result
     * @return Result
     */
    public function validate(?Result $result = null): Result
    {

        if (!isset($result)) {
            $result = new Result();
        }

        if (!$this->isValidFormat($this->email)) {
            return $result->addError("Адрес электронной почты указан неверно");
        }

        list($local_part, $domain) = explode('@', $this->email);

        if (!$this->checkDomain($domain)) {
            return $result->addError("Вы неверно указали домен почты (после @) - домен не существует, проверьте правильность указания почты");
        }

        $this->checkMailbox($result, $this->email, $domain);

        return $result;
    }

    /**
     * Проверяет формат электронной почты.
     *
     * @param string $email Электронная почта для проверки.
     * @return bool Возвращает true, если формат корректный, иначе false.
     */
    protected function isValidFormat(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Проверяет существование домена.
     *
     * @param string $domain Домен для проверки.
     * @return bool Возвращает true, если домен существует, иначе false.
     */
    protected function checkDomain(string $domain): bool
    {
        return checkdnsrr($domain, 'MX') && getmxrr($domain, $hosts);
    }

    /**
     * Проверяет существование почтового ящика, соединяясь с почтовым сервером.
     *
     * @param string $email Электронная почта для проверки.
     * @param string $domain Домен почтового сервера.
     * @return bool Возвращает true, если почтовый ящик существует, иначе false.
     */
    protected function checkMailbox(Result $result, string $email, string $domain): bool
    {
        // Получаем MX-записи домена
        if (!getmxrr($domain, $hosts)) {
            return false;
        }

        // Соединяемся с первым доступным MX сервером
        foreach ($hosts as $host) {
            $connect = fsockopen($host, 25);
            if (@$connect) {

                try {

                    if (!str_starts_with($res = $this->getServerResponse($connect), "220")) {
                        throw new \Exception(sprintf("Почтовый сервер %s вернул ошибку (не представился), проверьте правильность указания адреса электронной почты", $domain));
                    }

                    if (!$this->sendServerRequest($connect, sprintf("EHLO %s\r\n", LA_HOSTNAME))) {
                        throw new \Exception(sprintf("Ошибка коммуникации с почтовым сервером %s, проверьте правильность указания адреса электронной почты (hello send failed)", $domain));
                    }

                    if (!str_starts_with($this->getServerResponse($connect), "250")) {
                        throw new \Exception(sprintf("Ошибочный ответ от почтового сервера %s, проверьте правильность указания адреса электронной почты (hello response failed)", $domain));
                    }

                    if (!$this->sendServerRequest($connect, sprintf("MAIL FROM: <%s>\r\n", LA_MAIL_FROM))) {
                        throw new \Exception(sprintf("Ошибка коммуникации с почтовым сервером %s, проверьте правильность указания адреса электронной почты (mail from send failed)", $domain));
                    }

                    if (!str_starts_with($this->getServerResponse($connect), "250")) {
                        throw new \Exception(sprintf("Ошибочный ответ от почтового сервера %s, проверьте правильность указания адреса электронной почты (mail from failed)", $domain));
                    }

                    if (!$this->sendServerRequest($connect, sprintf("RCPT TO: <%s>\r\n", $email))) {
                        throw new \Exception(sprintf("Ошибка коммуникации с почтовым сервером %s, проверьте правильность указания адреса электронной почты (rcpt to send failed)", $domain));
                    }

                    if (!str_starts_with($res = $this->getServerResponse($connect), "250")) {
                        throw new \Exception(sprintf("Почтовый ящик %s не существует или не доступен, проверьте правильность указания адреса электронной почты", $email));
                    }

                    return true;

                } catch (\Exception $e) {
                    $result->addError($e->getMessage());
                    return false;
                } finally {
                    $this->closeConnection($connect);
                }
            }
        }

        $result->addError(sprintf("Невозможно соединиться с почтовым сервером %s, проверьте правильность указания адреса электронной почты", $domain));
        return false;
    }

    /**
     * Получает ответ сервера.
     *
     * @param resource $connect Соединение с сервером.
     * @return string Ответ сервера.
     */
    protected function getServerResponse($connect): string
    {
        $response = "";
        while ($str = fgets($connect, 4096)) {
            $response .= $str;
            if (substr($str, 3, 1) == " ") {
                break;
            }
        }

        return $response;
    }

    /**
     * Отправка данных на сервер
     * @param $connect
     * @param $data
     * @return bool
     */
    protected function sendServerRequest($connect, $data): bool
    {
        return fwrite($connect, $data) !== false;
    }

    /**
     * Завершает соединение с почтовым сервером
     * @param $connect
     * @return void
     */
    protected function closeConnection($connect): void
    {
        // Отправляем QUIT
        fwrite($connect, "QUIT\r\n");
        fclose($connect);
    }
}
