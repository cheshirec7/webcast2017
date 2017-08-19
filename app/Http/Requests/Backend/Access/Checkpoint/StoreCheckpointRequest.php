<?php

namespace App\Http\Requests\Backend\Access\Checkpoint;

use App\Http\Requests\Request;

/**
 * Class StoreCheckpointRequest.
 */
class StoreCheckpointRequest extends Request
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
            'checkpoint_name' => 'required|max:255',
            'checkpoint_code' => 'required|size:2',
            'miles_from_start' => 'required|numeric|max:1000',
            'hold_time' => 'integer|between:0,120',
            'allow_in_times' => 'boolean',
            'in_time_first_hour' => 'integer|between:0,23',
            'in_time_first_minute' => 'integer|between:0,59',
            'in_time_last_hour' => 'integer|between:0,23',
            'in_time_last_minute' => 'integer|between:0,59',
            'in_time_show_ordering' => 'boolean',
            'allow_out_times' => 'boolean',
            'out_time_first_hour' => 'integer|between:0,23',
            'out_time_first_minute' => 'integer|between:0,59',
            'out_time_last_hour' => 'integer|between:0,23',
            'out_time_last_minute' => 'integer|between:0,59',
            'out_time_show_ordering' => 'boolean',
            'allow_pulls' => 'boolean'
        ];
    }
}
