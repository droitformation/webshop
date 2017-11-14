<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrackingRequest extends FormRequest
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
            'event'          => 'required',
            'time'           => 'required',
            'MessageID'      => 'required',
            'email'          => 'required',
            'mj_campaign_id' => 'required',
            'mj_contact_id'  => 'required',
            'mj_message_id'  => 'required',
            'smtp_reply'     => 'required',
        ];
    }
}
