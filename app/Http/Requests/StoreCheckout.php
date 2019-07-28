<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCheckout extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'billing_address.full_name' => 'required',
            'shipping_address.full_name' => 'required_without:same_address',
        ];
    }

    public function messages()
    {
        return [
            #'g-recaptcha-response.required' => __('Please verify that you are not a robot.'),
            #'g-recaptcha-response.captcha'  => __('Captcha error! try again later or contact site admin.'),
            #'shipping_address.*.required_with' => 'Each person must have a unique e-mail address',
        ];
    }
}
