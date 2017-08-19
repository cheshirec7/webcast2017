<?php

namespace App\Http\Requests\Backend\Access\Racer;

use App\Http\Requests\Request;

/**
 * Class StoreRacerRequest.
 */
class StoreRacerRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->hasRole(1);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'racer_no' => 'required|integer|between:1,999',
            'racer_name' => 'required|max:64',
            'gps_name' => 'max:100',
            'jr' => 'max:2',
            'city' => 'max:64',
            'state' => 'max:30',
            'country' => 'max:30',
            'horse_name' => 'max:64',
            'breed' => 'max:30',
            'gender' => 'max:1',
            'color' => 'max:20',
            'horse_age' => 'numeric',
            'height' => 'max:10',
            'award' => 'max:100'
        ];
    }
}
