<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdateTopic;
use App\Http\Resources\TopicResource;
use App\Models\Post;
use App\Models\Topic;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show']);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $topics = Topic::LatestFirst()->paginate(3);
        return TopicResource::collection($topics);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Requests\StoreUpdateTopic  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUpdateTopic $request)
    {
        $topic = new Topic;
        $topic->title = $request->title;
        $topic->user()->associate($request->user());
        
        $post = new Post;
        $post->body = $request->body;
        $post->user()->associate($request->user());

        $topic->save();
        $topic->posts()->save($post);

        return new TopicResource($topic);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $topic = Topic::where('id', $id)->firstOrFail();
        return new TopicResource($topic);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Requests\StoreUpdateTopic  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreUpdateTopic $request, $id)
    {
        $topic = Topic::where('id', $id)->firstOrFail();
        
        $this->authorize('update', $topic);
        $topic->update($request->all());

        return new TopicResource($topic);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $topic = Topic::where('id', $id)->firstOrFail();
        
        $this->authorize('delete', $topic);
        $topic->delete();

        return response(null, 204);
    }
}
