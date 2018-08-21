<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Post;
//To use sql query
use DB;

class PostsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //guest still can see list of posts & individual posts 
        $this->middleware('auth')->except(['index', 'show']);

    }

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
            'body' => 'required',
            'cover_image' => 'image | mimes:jpeg,jpg,png | nullable | max:1999'

        ]);

        // Handle File Upload
        if($request->hasFile('cover_image')){
            // Get filename with the extension
            $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just extension
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            // Upload Image
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);

        } else {
            $fileNameToStore = 'noimage.jpg';
        }

        //Create Post
        $post = new Post;
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->user_id = auth()->user()->id;
        $post->cover_image = $fileNameToStore;
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

        // Check for correct user
        if(auth()->user()->id !== $post->user_id){
            return redirect('posts')->with('error', 'Unauthorized Page');
        }
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
                'body' => 'required',
                'cover_image' => 'image | mimes:jpeg,jpg,png | nullable | max:1999'
            ]);
    
        // Handle File Upload
        if($request->hasFile('cover_image')){
            // Get filename with the extension
            $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just extension
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            // Upload Image
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);

        }

            //Update Post
            $post = Post::find($id);
            $post->title = $request->input('title');
            $post->body = $request->input('body');

            //if there is a cover image to update
            if($request->hasFile('cover_image')) {
                //delete previous image except noimage.jpg
                if ($post->cover_image != 'noimage.jpg') {
                    Storage::delete('public/cover_images/' . $post->cover_image);
                }
                $post->cover_image = $fileNameToStore;
            }
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

        // Check for correct user
        if(auth()->user()->id !== $post->user_id){
            return redirect('posts')->with('error', 'Unauthorized Page');
        }
        
        if($post->cover_image != 'noimage.jpg'){
            // Delete image
            Storage::delete('public/cover_images/'.$post->cover_images);

        }
        $post->delete();
        return redirect('/posts')->with('success','Post Removed');
    }
}
