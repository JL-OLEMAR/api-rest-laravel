<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Post;

class PostController extends Controller{

    /*public function pruebas(Request $request) {
        return "Accion de pruebas de ENTRADAS-CONTROLLER";
    }*/

    public function __construct() {
        $this->middleware('api.auth', ['except' => ['index','show']]);
    }

    public function index(){
        $posts = Post::all();

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'posts' => $posts
        ]);
    }
}
