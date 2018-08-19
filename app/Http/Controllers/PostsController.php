<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
//To use sql query
use DB;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /* Eloquent Version  */
        //show recent post at the top
        //$posts = Post::orderBy('id', 'desc')->get();

        //Pagination kick in when there is 10+ posts
        $posts = Post::orderBy('id', 'desc')->paginate(10);
        return view('posts/index')->with('posts', $posts);
       
       
        /*REF:
            $post = Post::where('title', 'Post Two')->get();
            $posts = Post::orderBy('id', 'desc')->take(1)->get();
        */


        /*SQL Version
            $posts = DB::select('SELECT * FROM posts');
            return view('posts/index')->with('posts', $posts);
        */
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //post request input validation
        $this-> validate($request, [
            'title' => 'required',
            'body' => 'required'

        ]);

        //Create Post
        $post = new Post;
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->save();

        return redirect('/posts')->with('success','Post Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        return view('posts/show')->with('post', $post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);
        return view('posts/edit')->with('post', $post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
            //post request input validation
            $this-> validate($request, [
                'title' => 'required',
                'body' => 'required'

            ]);
    
            //Update Post
            $post = Post::find($id);
            $post->title = $request->input('title');
            $post->body = $request->input('body');
            $post->save();
    
            return redirect('/posts')->with('success','Post Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        $post->delete();
        return redirect('/posts')->with('success','Post Removed');
    }
}
