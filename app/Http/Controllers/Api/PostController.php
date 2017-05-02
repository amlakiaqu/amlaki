<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Events\PostCreatedEvent;
use App\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
        \DB::connection()->enableQueryLog();
        $filterQuery = $request->query('query');
        $filterCategory = $request->query('category');
        $filterFilters = $request->query('filters');
        $useFilteredIds = false;
        $filteredPostIds = [];
        $postSelect = ["id", "title", "user_id", "image", 'category_id'];
        $category = null;

        if($filterFilters){
            $postIdsQuery = \DB::table('post')
                ->select(\DB::raw('`post`.id AS "id"'))
                ->join('post_property', 'post.id', '=', 'post_property.post_id')
                ->join('property', 'property.id', '=', 'post_property.property_id')
                ->join('user', 'user.id', '=', 'post.user_id')
                ->where(function($q) use ($filterFilters, &$useFilteredIds) {
                    foreach ($filterFilters as $propertyCode => $propertyValue){
                        if(strpos($propertyCode, '_') !== 0 && $propertyValue != null && $propertyValue != "ALL"){
                            $useFilteredIds = true;
                            $q->orWhere(function($q2) use($propertyCode, $propertyValue){
                                $q2->Where('property.code', '=', $propertyCode)->where('post_property.value', 'LIKE', '%'.$propertyValue.'%');
                            });
                        }
                    }
                });

            $filteredPostIds = $postIdsQuery->groupBy('id')->pluck('id')->toArray();
        }

        if ($filterCategory) {
            $category = Category::select(["id"])->where('code', $filterCategory)->firstOrFail();
        }

        $withList = [
            'category' => function ($query) {
                $query->select(['id', 'name']);
            },
            'user' => function ($query) {
                $query->select(['id', 'name']);
            },
            'properties' => function ($query) use ($filterFilters) {
                $query->select(["title", "code"]);
            }
        ];

        $query = Post::with($withList)->select($postSelect);
        if ($filterQuery) {
            $query = $query->where("title", "like", "%$filterQuery%");
        }
        if ($category) {
            $query = $query->where("category_id", $category->id);
        }
        if($useFilteredIds){
            $query = $query->whereIn('id', $filteredPostIds);
        }

        $query = $query->orderBy('created_at', 'desc');
        $result = $query->paginate(16);
        return $result;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
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
            foreach ($category->properties as $property) {
                if (!$requestData->get($property->code) && $property->peviot->required == true) {
                    return response(["error" => $property->code . " is required."], 400);
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
            foreach ($category->properties as $property) {
                $post->properties()->attach($property->id, ["value" => $requestData->get($property->code)]);
            }
        }

        // Send Notification Message
        event(new PostCreatedEvent(["post_id" => $post->id, "message" => 'Hi there Pusher!']));

        // Return Response
        return response()->json(["id" => $post->id, "message" => __("Post Created Successfully")]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post $post
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function show(Post $post)
    {
        $postSelect = ["id", "title", "user_id", 'category_id', "image"];
        $withList = [
            'user' => function ($query) {
                $query->select(['id', 'name']);
            },
            'category' => function ($query) {
                $query->select(['id', 'name'])->with("properties");
            },
            'properties' => function ($query) {
                $query->select(["title", "code"]);
            },
            "medias"
        ];
        $query = Post::with($withList)->select($postSelect);
        $post = $query->findOrFail($post->id);
        if (!empty($post->properties)) {
            foreach ($post->properties as $property) {
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
            if (!empty($post->category->properties)) {
                foreach ($post->category->properties as $property) {
                    $property->title = __($property->title);
                    $property->required = $property->pivot->required;
                    if ($property->extra_settings) {
                        $property->extra_settings = json_decode($property->extra_settings);
                    }
                    unset($property->pivot);
                }
            }
        }
        return $post;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Post $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $requestData = $request->json();
        foreach ($requestData as $propertyCode => $propertyValue) {
            $property = $post->properties()->where("code", "=", $propertyCode)->first();
            if ($property) {
                $post->properties()->updateExistingPivot($property->id, ["value" => $propertyValue]);
            }
        }
        $title = $requestData->get("TITLE");
        if ($title) {
            $post->title = $title;
        }
        $post->save();
        return response()->json(["message" => __('Post updated successfully')]);
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
        if ($post->user_id != $user->id) {
            return response()->json(["message" => __("You are not allowed to delete this post")], 401);
        }
        $post->properties()->detach();
        $post->delete();
        return response()->json(["message" => __("Post deleted successfully")], 200);

    }
}
