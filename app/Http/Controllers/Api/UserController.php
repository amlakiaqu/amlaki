<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return User::select(["id", "name"])->orderBy('id')->paginate(2);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

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
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
        $user = User::select(["id", "name", "phone", "address"])->findOrFail($user->id);
        return $user;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }

    /**
     * Return user posts
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function getPosts(Request $request, User $user){

        $user =  User::select(["id", "name"])->with(["posts" => function($query){
            $query->with(["category" => function($categoryQuery){
                $categoryQuery->select(["id", "name"]);
            }])->select(["id", "title", "user_id", "category_id", "created_at"]);

        }])->findOrFail($user->id);

        foreach($user->posts as $post){
            if($post->created_at){
                $post->created = $post->created_at->format('d/m/Y');
            }
            unset($post->created_at);
            $post->category->name = __($post->category->name);
        }

        return $user;
    }

    /**
     * Return user requests
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function getRequests(Request $request, User $user){
        $user =  User::select(["id", "name"])->with(["requests" => function($query){
            $query->with(["category" => function($categoryQuery){
                $categoryQuery->select(["id", "name"]);
            }, "properties"])->select(["id", "user_id", "category_id", "created_at"]);
        }])->findOrFail($user->id);

        foreach($user->requests as $request){
            if($request->created_at){
                $request->created = $request->created_at->format('d/m/Y');
            }

            $request->category->name = __($request->category->name);
            foreach ($request->properties as $property){
                $property->title = __($property->title);
                $property->value = $property->pivot->value;
                unset($property->pivot);
                if($property->extra_settings){
                    $property->extra_settings = json_decode($property->extra_settings);
                }
            }
            unset($request->created_at);
            unset($request->user_id);
            unset($request->category_id);
        }

        return $user;
    }
}
