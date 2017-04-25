<?php

namespace App\Http\Controllers\Api;

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
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param RequestModel $requestModel
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, RequestModel $requestModel)
    {
        //
        return response()->json(["message" => "not implemented yet"], 404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(RequestModel $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param RequestModel $requestModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RequestModel $requestModel)
    {
        //
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
