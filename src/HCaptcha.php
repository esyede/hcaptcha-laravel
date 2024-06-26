<?php

namespace Esyede\Laravel\HCaptcha;

use GuzzleHttp\Client;

class HCaptcha
{
    const SITEVERIFY_URL = 'https://hcaptcha.com/siteverify';

    protected $sitekey;
    protected $secret;
    protected $client;
    protected $guzzleOptions = [];
    protected $verifiedResponses = [];

    public function __construct($sitekey, $secret, array $guzzleOptions = [])
    {
        $this->sitekey = $sitekey;
        $this->secret = $secret;
        $this->guzzleOptions = array_merge(config('hcaptcha.guzzle_options'), $guzzleOptions);
    }

    public function getClient()
    {
        $this->client = $this->client ?: new Client($this->guzzleOptions);
        return $this->client;
    }

    public function display(array $attributes = [])
    {
        $attributes = $this->prepareAttributes($attributes);
        return '<div ' . $this->buildAttributes($attributes) . '></div>';
    }

    public function displayButton($label = 'Submit', array $attributes = [])
    {
        $attributes['data-callback'] = isset($attributes['data-callback']) ? $attributes['data-callback'] : 'onSubmit';
        $attributes = $this->prepareAttributes($attributes);
        return '<button ' . $this->buildAttributes($attributes) . '>' . $label . '</button>';
    }

    public function script($locale = null, $render = false, $onload = null, $recaptchacompat = null)
    {
        $data = [
            'onload' => $onload,
            'render' => $render ? 'explicit' : null,
            'hl' => $locale ?: app()->getLocale(),
            'recaptchacompat' => $recaptchacompat ? 'on' : null,
        ];

        $parameters = http_build_query($data);
        return '<script src="https://js.hcaptcha.com/1/api.js?' . $parameters . '" async defer></script>' . PHP_EOL;
    }

    public function validate(?string $token, ?string $remoteip = null): bool
    {
        if (empty($token)) {
            return false;
        }

        if (in_array($token, $this->verifiedResponses)) {
            return true;
        }

        $response = $this->getClient()->request('POST', self::SITEVERIFY_URL, [
            'form_params' => [
                'secret' => $this->secret,
                'response' => $token,
                'remoteip' => $remoteip,
            ],
        ]);

        $data = json_decode($response->getBody());

        if (!optional($data)->success !== true) {
            return false;
        }

        $this->verifiedResponses[] = $token;
        return true;
    }

    protected function buildAttributes(array $attributes): string
    {
        $html = [];

        foreach ($attributes as $key => $value) {
            $html[] = $key.'="'.$value.'"';
        }

        return implode(' ', $html);
    }

    protected function prepareAttributes(array $attributes = [])
    {
        $defaults = ['class' => 'h-captcha', 'data-sitekey' => $this->sitekey];

        if (isset($attributes['class']) && !empty($attributes['class'])) {
            $defaults['class'] .= ' ' . $attributes['class'];
        }

        $attributes = array_merge($attributes, $defaults);
        return $attributes;
    }
}
