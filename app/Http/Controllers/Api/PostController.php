<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Events\PostCreatedEvent;
use App\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(Request $request)
    {
      $filterQuery = $request->query('query');
      $filterCategory = $request->query('category');
      $postSelect = ["id", "title", "user_id", "image", 'category_id'];
      $category = null;
      if($filterCategory){
          $category = Category::select(["id"])->where('code', $filterCategory)->firstOrFail();
      }
      $withList = [
        'category' => function($query) {
          $query->select(['id', 'name']);
        },
        'user' => function($query){
          $query->select(['id', 'name']);
        },
        'properties' => function($query){
          $query->where("property.code", "=", "PRICE")->select(["title", "code"]);
        }
      ];
      $query = Post::with($withList)->select($postSelect);
      if($filterQuery){
        $query = $query->where("title", "like", "%$filterQuery%");
      }
      if($category) {
        $query = $query->where("category_id", $category->id);
      }

      $query = $query->orderBy('created_at', 'desc');
      return $query->paginate(16);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Get user from session
        $user = Auth::user();

        // Get Request json data (as collection)
        $requestData = $request->json();

        $categoryId = $requestData->get('category_id');
        // Validate the category and required properties
        $category = Category::with("properties")->findOrFail($categoryId);
        if (!empty($category->properties)) {
            foreach($category->properties as $property){
                if(!$requestData->get($property->code) && $property->peviot->required == true){
                    return response(["error" => $property->code." is required."], 400);
                }
            }
        }

        // Create New Post Instance and save request data
        $post = new Post;
        $post->user()->associate($user);
        $post->category()->associate($category);
        $post->title = $requestData->get("TITLE");
        $post->image = 'http://lorempixel.com/500/400/transport/';
        $post->save();

        // Fill the properties
        if (!empty($category->properties)) {
            foreach($category->properties as $property){
                $post->properties()->attach($property->id, ["value" => $requestData->get($property->code)]);
            }
        }

        // Send Notification Message
        Log::info("Trigger PostCreatedEvent");
        event(new PostCreatedEvent(["post_id" => $post->id, "message" => 'Hi there Pusher!']));
        Log::info("PostCreatedEvent triggered");

        // Return Response
        return response()->json(["id" => $post->id, "message" => __("Post Created Successfully")]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function show(Post $post)
    {
        $postSelect = ["id", "title", "user_id", 'category_id', "image"];
        $withList = [
            'user' => function($query){
                $query->select(['id', 'name']);
            },
            'category' => function($query){
                $query->select(['id', 'name']);
            },
            'properties' => function($query){
                $query->select(["title", "code"]);
            }
        ];
        $query = Post::with($withList)->select($postSelect);
        $post = $query->findOrFail($post->id);
        if (!empty($post->properties)) {
            foreach ($post->properties as $property){
                $property->title = __($property->title);
                $value = $property->pivot->value;
                $property->value = $value;
                unset($property->pivot);
            }
        }
        if (!empty($post->category)) {
            if (!empty($post->category->name)) {
                $post->category->name = __($post->category->name);
            }
        }
        return $post;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param  \App\Post $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Post $post)
    {
        $user = $request->user();
        if($post->user_id != $user->id){
            return response()->json(["message" => __("You are not allowed to delete this post")], 401);
        }
        $post->properties()->detach();
        $post->delete();
        return response()->json(["message" => __("Post deleted successfully")], 200);

    }
}
