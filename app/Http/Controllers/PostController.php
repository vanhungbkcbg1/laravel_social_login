<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Filters\Title;
use App\Post;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Spatie\Permission\Models\Role;

class PostController extends Controller
{
    //

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query =Post::query();
        $pipeline = app(Pipeline::class)
                    ->send($query)
                    ->through([
                       Title::class
                    ])
                    ->thenReturn()->get();

        dd($pipeline);

        $posts = Post::orderBy('id','DESC')->paginate(5);
        return view('posts.index',compact('posts'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'content' => 'required',
        ]);
        $title =$request->input("title");
        $content =$request->input("content");
        $post =Post::create(['title'=>$title,'content'=>$content]);
        return redirect()->route('posts.index')
            ->with('success', 'Post created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
//        dump($post->comments);die();
        return view('posts.show',compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        return view('posts.edit',compact('post'));
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
        request()->validate([
            'name' => 'required',
            'detail' => 'required',
        ]);


        $post->update($request->all());


        return redirect()->route('posts.index')
            ->with('success','Post updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();


        return redirect()->route('posts.index')
            ->with('success','Post deleted successfully');
    }

    /**
     * Remove the specified resource from storage.
     * router is /posts/{post}/comments
     * when defined router as above laravel will auto mapping param to entity
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function addComment(Request $request,Post $post){
        $content = $request->input('content');
        dd($post->latestComment());die();
        $comment=new Comment(['content'=>$content]);
        $post->comments()->save($comment);
        return redirect()->route('posts.index')
            ->with('success','Comment created successfully');
    }
}
