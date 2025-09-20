<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSavedRouteRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'start_lat' => 'required|numeric|between:-90,90',
            'start_lng' => 'required|numeric|between:-180,180',
            'end_lat' => 'required|numeric|between:-90,90',
            'end_lng' => 'required|numeric|between:-180,180',
            'start_address' => 'required|string|max:500',
            'end_address' => 'required|string|max:500',
            'polyline' => 'required|string',
            'safety_score' => 'nullable|numeric|between:0,5',
            'duration' => 'nullable|string|max:100',
            'distance' => 'nullable|string|max:100',
            'crime_analysis' => 'nullable|array',
            'crime_analysis.total_crimes_in_area' => 'nullable|integer|min:0',
            'crime_analysis.crimes_near_route' => 'nullable|integer|min:0',
            'crime_analysis.high_severity_crimes' => 'nullable|integer|min:0',
            'is_safer_route' => 'boolean',
            'route_type' => 'string|in:regular,safer',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Route name is required',
            'start_lat.required' => 'Start latitude is required',
            'start_lng.required' => 'Start longitude is required',
            'end_lat.required' => 'End latitude is required',
            'end_lng.required' => 'End longitude is required',
            'start_address.required' => 'Start address is required',
            'end_address.required' => 'End address is required',
            'polyline.required' => 'Route polyline is required',
            'safety_score.between' => 'Safety score must be between 0 and 5',
        ];
    }
}

class UpdateSavedRouteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $route = $this->route('saved_route');
        return $route && $route->user_id === $this->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ];
    }
}
