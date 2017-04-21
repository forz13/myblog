<?php

namespace Blogger\BlogBundle\Service;

use ReCaptcha\ReCaptcha;

class CaptchaService
{
    private $captchaService;

    private $clientSecretKey;

    private $serverSecretKey;

    public function __construct(ReCaptcha $captchaService, $clientSecretKey, $serverSecretKey)
    {
        $this->serverSecretKey = $serverSecretKey;
        $this->clientSecretKey = $clientSecretKey;
        $this->captchaService = $captchaService;
    }

    public function verify($response, $remoteIp = null)
    {
        $result = $this->captchaService->verify($response, $remoteIp);
        return $result->isSuccess();
    }

    public function getClientSecretKey()
    {
        return $this->clientSecretKey;
    }

    public function getServerSecretKey()
    {
        return $this->serverSecretKey;
    }


}