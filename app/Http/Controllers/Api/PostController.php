<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
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

      $query = $query->orderBy('created_at');
//      dd($query->toSql().'$category = '.$category);
      return $query->paginate(16);
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
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
