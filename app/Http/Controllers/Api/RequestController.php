<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Request as RequestModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RequestController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $data = $request->json();
        $user = $request->user();
        $categoryId = (int) $data->get('category_id');
        $category = Category::with("properties")->findOrFail($categoryId);

        $requestObject = new RequestModel;
        $requestObject->user_id = $user->id;
        $requestObject->category_id = $category->id;
        $requestObject->save();
        if (!empty($category->properties)) {
            foreach ($category->properties as $property) {
                $value = "ALL";
                $userValue = $data->get($property->code);
                if(is_string($userValue) && strlen($userValue) > 0){
                    $value = $userValue;
                }
                $requestObject->properties()->attach($property->id, ["value" => $value]);
            }
        }
        $requestObject->save();
        return response()->json(["message" => __("Request created successfully")]);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     * @internal param int $requestId
     * @internal param RequestModel $requestModel
     */
    public function show(Request $request, int $id)
    {
        //
        $requestObject = RequestModel::with(["properties", "category" => function($query){
            $query->with("properties");
        }])->findOrFail($id);
        if (!empty($requestObject->properties)) {
            foreach($requestObject->properties as $property){
                $property->title = __($property->title);
                if($property->extra_settings){
                    $property->extra_settings = json_decode($property->extra_settings);
                    if($property->extra_settings && isset($property->extra_settings->hint)){
                        $property->extra_settings->hint = __($property->extra_settings->hint);
                    }
                }
                $property->value = $property->pivot->value;
                unset($property->pivot);
                if($property->value && $property->value == "ALL"){
                    $property->value = null;
                }else{
                    $property->input_value = $property->value;
                }
            }
        }
        if(!empty($requestObject->category)) {
            $category = $requestObject->category;
            $category->name = __($category->name);
            if(!empty($category->properties)){
                foreach($category->properties as $property){
                    $property->title = __($property->title);
                    if($property->extra_settings){
                        $property->extra_settings = json_decode($property->extra_settings);
                        if($property->extra_settings && isset($property->extra_settings->hint)){
                            $property->extra_settings->hint = __($property->extra_settings->hint);
                        }
                    }
                    $property->value = $property->pivot->value;
                    unset($property->pivot);
                }
            }
        }
        return $requestObject;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param RequestModel|Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     * @internal param int $requestId
     */
    public function update(Request $request, int $id)
    {
        $requestData = $request->json();
        $requestObject = RequestModel::with(["category" => function($query){
            $query->with("properties");
        }])->findOrFail($id);

        $requestFinalProperties = [];
        $category = $requestObject->category;
        if(!empty($category->properties)){
            foreach($category->properties as $property){
                $value = "ALL";
                $userValue = $requestData->get($property->code);
                if(is_string($userValue) && strlen($userValue) > 0){
                    $value = $userValue;
                }
                $requestFinalProperties[$property->id] = ["value" => $value];
            }
        }
        $requestObject->properties()->sync($requestFinalProperties);
        $requestObject->save();
        return response()->json(["message" => __('Request updated successfully')]);
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $httpRequest
     * @param RequestModel $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $httpRequest, RequestModel $request)
    {
        $user = $httpRequest->user();
        if($request->user_id != $user->id){
            return response()->json(["message" => __("You are not allowed to delete this request")], 401);
        }
        $request->properties()->detach();
        $request->delete();
        return response()->json(["message" => __("Request deleted successfully")], 200);
    }
}
