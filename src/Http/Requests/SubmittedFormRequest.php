<?php

namespace Mcms\FrontEnd\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Pass this file to your controller to validate a request before taking any further steps
 * Usually needed in the store phase
 * Class SubmittedFormRequest
 * @package FrontEnd\Http\Requests
 */
class SubmittedFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
