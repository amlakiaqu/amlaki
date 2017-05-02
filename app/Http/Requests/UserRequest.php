<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Backpack\CRUD\app\Http\Requests\CrudRequest;
use Illuminate\Validation\Rule;

class UserRequest extends CrudRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if(request()->is('*/restore')){
            return [];
        }
        $userId = $this->id;
        $validationRules = [
            'name' => 'required|min:3|max:64',
            'username' => 'required|string|min:4|max:64|unique:user',
            'email' => 'required|email|max:191|unique:user',
            'phone' => 'required|phone|unique:user',
            'is_admin' => 'required|boolean',
            'password' => 'required|min:6'
        ];
        if($userId){
            $validationRules["username"] = [
                'required',
                'string',
                'min:4',
                'max:64',
                Rule::unique('user')->ignore($userId)
            ];

            $validationRules["email"] = [
                'required',
                'email',
                'max:191',
                Rule::unique('user')->ignore($userId)
            ];

            $validationRules["phone"] = [
                'required',
                'phone',
                Rule::unique('user')->ignore($userId)
            ];

            $validationRules["password"] = [
                'min:6'
            ];
        }
        return $validationRules;
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}
