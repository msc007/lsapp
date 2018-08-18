@extends('layouts/app')


@section('content')
    <h1>Posts</h1>
    @if(count($posts) > 0)
        @foreach($posts as $post)
            <div class="card bg-light p-3">
                <!-- This is better way: <a href="{{route('posts.show', $post->id)}}">-->
                <h3><a href="/posts/{{$post->id}}">{{$post->title}}</a></h3>
                <small>Written on {{$post->created_at}}</small>
            </div>
        @endforeach
        <!-- pagination-->
        {{$posts->links()}}
    @else
        <p>No posts found</p>
    @endif
@endsection

 