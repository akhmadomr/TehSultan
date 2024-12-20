<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use ReCaptcha\ReCaptcha;

class Captcha implements ValidationRule
{
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        $recaptcha = new ReCaptcha(config('services.recaptcha.secret'));
        $response = $recaptcha->verify($value, request()->ip());

        if (!$response->isSuccess()) {
            $fail('The :attribute verification failed. Please try again.');
        }
    }
}
