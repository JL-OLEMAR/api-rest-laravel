<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Post;
use App\Helpers\JwtAuth;
use App\Category;


class PostController extends Controller{

    /*public function pruebas(Request $request) {
        return "Accion de pruebas de ENTRADAS-CONTROLLER";
    }*/

    public function __construct() {
        $this->middleware('api.auth', ['except' => [
            'index',
            'show',
            'getImage',
            'getPostsByCategory',
            'getPostsByUser'
        ]]);
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
        $post = Post::find($id)->load('category')
                               ->load('user');

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
        //Comprobar si el usuario esta identficado
        $user = $this->getIdentity($request);

        //Recoger los datos de la entrada por post
        $json = $request->input('json', null);
        $params = json_decode($json); //objeto
        $params_array = json_decode($json, true); //array

        if ($user && !empty($params_array)) {

            //Limpiar datos
            $params_array = array_map('trim', $params_array);

            //Conseguir usuario identificado
            $jwtAuth = new JwtAuth();
            $token = $request->header('Authorization', null);
            $user = $jwtAuth->checkToken($token, true);

            //Validar los datos
            $validate = \Validator::make($params_array,[
                'content' => 'required',
                'category_id' => 'required',
                'title' => 'required',
                'image' => 'required'

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
                $post->user_id = $user->sub;
                $post->category_id = $params->category_id;
                $post->title = $params->title;
                $post->content = $params->content;
                $post->image = $params->image;
                $post->save();

                $data = [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'El post se ha creado correctamente',
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

        //Datos para devolver
        $data = array(
            'code' => 400,
            'status' => 'error',
            'message' => 'Datos enviados, son incorrectos.'
        );

        if (!empty($params_array)) {
            //Validar los datos
            $validate = \Validator::make($params_array, [
                'title' => 'required',
                'content' => 'required',
                'category_id' => 'required'
            ]);

            //Quitar lo que no quiero actualizar
            unset($params_array['id']);
            unset($params_array['user_id']);
            unset($params_array['created_at']);
            unset($params_array['user']);

            if ($validate->fails()) {
                $data['errors'] = $validate->errors();
                return response()->json($data, $data['code']);
            }

            //Comprobar si el usuario esta identficado
            $user = $this->getIdentity($request);

            //Buscar el registro a actualizar
            $post = Post::where('id', $id)
                    ->where('user_id', $user->sub)
                    ->first();

            if (!empty($post) && is_object($post)) {
                //Actualizar el registro(Categoria)
                $post->update($params_array);

                //Devolver algo
                $data = array(
                    'code' => 200,
                    'status' => 'success',
                    'post' => $post,
                    'changes' => $params_array
                );
            }
            /*$where = [
                'id' => $id,
                'user_id' => $user->sub
            ];
            $post = Post::updateOrCreate($where, $params_array);
            */
        }

        //Devolver respuesta
        return response()->json($data, $data['code']);
    }

    public function destroy($id, Request $request){
        //Comprobar si el usuario esta identficado
        $user = $this->getIdentity($request);

        //Conseguir el registro
        $post = Post::where('id', $id)
                    ->where('user_id', $user->sub)
                    ->first();

        if (!empty($post)) {
            //Borrarlo
            $post->delete();

            //Devolver algo
            $data = array(
                'code' => 200,
                'status' => 'success',
                'post' => $post
            );
        }else {
            $data = array(
                'code' => 404,
                'status' => 'error',
                'message' => 'El post no existe.'
            );
        }

        return response()->json($data, $data['code']);
    }

    private function getIdentity($request){
        $jwtAuth = new \JwtAuth();
        $token = $request->header('Authorization');
        $user = $jwtAuth->checkToken($token, true);

        return $user;
    }

    public function upload(Request $request){
        //Recoger la imagen de la peticion
        $image = $request->file('file0');

        //Validar imagen
        $validate = \Validator::make($request->all(), [
            'file0' => 'required|image|mimes:jpg,jpeg,png,gif'
        ]);

        //Guardar imagen
        if (!$image || $validate->fails()) {
            $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => 'Error al subir la imagen.'
            );
        }else {
            $image_name = time().$image->getClientOriginalName();
            \Storage::disk('images')->put($image_name, \File::get($image));

            $data = array(
                'code' => 200,
                'status' => 'success',
                'image' => $image_name
            );
        }

        //Devolver datos
        return response()->json($data, $data['code']);
    }

    public function getImage($filename){
        //Comprobar si existe el fichero
        $isset = \Storage::disk('images')->exists($filename);
        if ($isset) {
            //Conseguir la imagen
            $file = \Storage::disk('images')->get($filename);

            //Devolver la imagen
            return new Response($file, 200);
        }else {
            $data = array(
                'code' => 404,
                'status' => 'error',
                'message' => 'La imagen no existe.'
            );
            return response()->json($data, $data['code']);
        }
    }

    public function getPostsByCategory($id){
        $category = Category::find($id);
        $posts = Post::where('category_id', $id)->get();

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'posts' => $posts,
            'category' => $category
        ], 200);
    }

    public function getPostsByUser($id,Request $request){
        $posts = Post::where('user_id', $id)->get();

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'posts' => $posts
        ], 200);
    }
}

