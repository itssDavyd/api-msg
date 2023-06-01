<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::guard('api')->check();

        if ($user) {
            $posts = Post::all()->sortDesc();
            return response()->json([
                'status' => 200,
                'message' => 'Se han devuelto el listado de post correctamente.',
                'data' => $posts
            ]);
        }
        return response()->json([
            'status' => 401,
            'message' => 'User not authenticated'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::guard('api');
        if ($user->check()) {
            $validator = Validator::make($request->all(), [
                'title' => 'required|min:6',
                'description' => 'required|max:255'
            ]);

            if ($validator->fails()) {
                return \response()->json([
                    'status' => 400,
                    'message' => $validator->errors()
                ]);
            }

            $input = $request->all();
            $input['user_id'] = $user->user()->id;
            $input['fecha_creacion'] = date('Y-m-d H:i:s');
            $input['fecha_modificacion'] = date('Y-m-d H:i:s');
            $post = Post::create($input);

            return \response()->json([
                'status' => 200,
                'message' => 'Creado correctamente el post',
                'data' => $post
            ]);
        } else {
            return \response()->json([
                'status' => 401,
                'message' => 'User dont auth.'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public
    function show(Request $request)
    {
        if (Auth::guard('api')->check()) {
            $post = Post::find($request->post);

            if (!$post) {
                return \response()->json([
                    'status' => 400,
                    'message' => 'No se ha podido encontrar el post buscado.'
                ]);
            }

            return \response()->json([
                'status' => 200,
                'message' => 'Post encontrado correctamente',
                'data' => $post
            ]);
        } else {
            return \response()->json([
                'status' => 401,
                'message' => 'User dont auth.'
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public
    function update(Request $request)
    {
        $user = Auth::guard('api');
        $post = Post::find($request->post);

        if ($user->check() && $post->user_id == $user->user()->id) {

            $validator = Validator::make($request->all(), [
                'title' => 'min:6',
                'description' => 'max:255'
            ]);

            if ($validator->fails()) {
                return \response()->json([
                    'status' => 400,
                    'message' => $validator->errors()
                ]);
            }

            if ($post) {
                $input = $request->all();
                $input['fecha_modificacion'] = date('Y-m-d H:i:s');
                $post->update($input);

                return \response()->json([
                    'status' => 200,
                    'message' => 'Actualizado correctamente el post',
                    'data' => $post->id
                ]);
            } else {
                return \response()->json([
                    'status' => 400,
                    'message' => 'No se ha encontrado el post a actualizar.'
                ]);
            }
        }

        return \response()->json([
            'status' => 401,
            'message' => 'User dont auth.'
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public
    function destroy(Request $request)
    {
        $user = Auth::guard('api');
        $post = Post::find($request->post);

        if ($user->check() && $post->user_id == $user->user()->id) {
            //Si el usuario actual esta logueado en la pagina y el usuario_id es igual al de la session que intenta borrar le dejas.
            $post->delete();

            return \response()->json([
                'status' => 200,
                'message' => 'Borrado correctamente el post',
                'data' => $post->id
            ]);
        }
        return \response()->json([
            'status' => 401,
            'message' => 'User dont auth.'
        ]);
    }
}
