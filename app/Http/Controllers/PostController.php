<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use App\Jobs\MakeDiv;
use App\Jobs\MakeSum;
use App\Jobs\FindMaxPrime;
use App\Jobs\ConvertCelsius;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $posts = Post::all();

        return view('posts.index', compact('posts', 'user'));
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $path = $request->photo->store('public/images');

        Post::create(
            [
            'image' => Storage::url($path),
            'description' => $request->description,
            'user_id' => $user->id
            ]    
        );
            return redirect('/dashboard');


    }

    
    /**
     * like the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function like(Post $post)
    {
        $user = auth()->user();

       $numOfLikes = Like::where('user_id', '=', $user->id)
            ->where('post_id', '=', $post->id)
            ->count();

        if ($numOfLikes > 0) {
            Like::where('user_id', '=', $user->id)
                ->where('post_id', '=', $post->id)
                ->delete();
            
            return response()->json(
            [
                'success' => true,
                'message' => 'like removido com sucesso',
                'like' => false
            ]);    
        }  

        Like::create([
            'post_id' => $post->id,
            'user_id' => $user->id
        ]);

        return response()->json(
            [
                'success' => true,
                'message' => 'Like criado com sucesso',
                'like' => true
            ]
        );
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
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
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
    }

    public function prime($limit)
    {
        FindMaxPrime::dispatch($limit, auth()->id());

        return 'Primo sendo executado. Aguarde....';
    }





    public function soma($num1, $num2)
    {
        MakeSum::dispatch($num1, $num2);
        return 'A soma esta sendo feita. Aguarde....';
    }

    public function celsius($farenheit)
    {
        ConvertCelsius::dispatch($farenheit);
        return 'A soma esta sendo feita. Aguarde....';
    }

    public function div($num1, $num2)
    {
        MakeDiv::dispatch($num1, $num2, auth()->id());

        return ;
    }
}
