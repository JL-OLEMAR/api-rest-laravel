<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Post;
use App\Helpers\JwtAuth;

class PostController extends Controller{

    /*public function pruebas(Request $request) {
        return "Accion de pruebas de ENTRADAS-CONTROLLER";
    }*/

    public function __construct() {
        $this->middleware('api.auth', ['except' => ['index','show']]);
    }

    public function index(){
        $posts = Post::all()->load('category');

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'posts' => $posts
        ],200);
    }

    public function show($id){
        $post = Post::find($id)->load('category');

        if (is_object($post)) {
            $data = [
                'code' => 200,
                'status' => 'success',
                'post' => $post
            ];
        }else {
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'La entrada no existe.'
            ];
        }
        return response()->json($data, $data['code']);
    }

    public function store(Request $request){
        //Recoger los datos de la entrada por post
        $json = $request->input('json', null);
        $params = json_decode($json); //objeto
        $params_array = json_decode($json, true);

        if (!empty($params_array) && !empty($params_array)) {
            //Limpiar datos
            $params_array = array_map('trim', $params_array);

            //Conseguir usuario identificado
            $jwtAuth = new JwtAuth();
            $token = $request->header('Authorization', null);
            $user = $jwtAuth->checkToken($token, true);

            //Validar los datos
            $validate = \Validator::make($params_array,[
                'category_id' => 'required',
                'title' => 'required',
                'content' => 'required'
            ]);

            //Guardar la entrada
            if ($validate->fails()) {
                $data = [
                    'code' => 400,
                    'status' => 'error',
                    'message' => 'No se ha guardado el post, faltan datos.',
                    'errors' => $validate->errors()
                ];
            }else {
                $post = new Post();
                $post->user_id = $params_array['user_id'];
                $post->category_id = $params_array['category_id'];
                $post->title = $params_array['title'];
                $post->content = $params_array['content'];
                $post->save();

                $data = [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'La entrada se ha creado correctamente',
                    'post' => $post
                ];
            }
        }else {
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'Envia los datos correctamente.'
            ];
        }

        //Devolver resultados
        return response()->json($data, $data['code']);
    }

    public function update($id, Request $request){
        //Recoger datos por post
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        if (!empty($params_array)) {
            //Limpiar datos
            $params_array = array_map('trim', $params_array);

            //Validar los datos
            $validate = \Validator::make($params_array, [
                'user_id' => 'required',
                'category_id' => 'required',
                'title' => 'required'
            ]);

            //Quitar lo que no quiero actualizar
            unset($params_array['id']);
            unset($params_array['created_at']);

            //Actualizar el registro(Categoria)
            $post = Post::where('id', $id)->update($params_array);
            $data = [
                'code' => 200,
                'status' => 'success',
                'post' => $params_array
            ];

        } else {
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'No has enviado ninguna entrada.'
            ];
        }

        //Devolver respuesta
        return response()->json($data, $data['code']);
    }


}

