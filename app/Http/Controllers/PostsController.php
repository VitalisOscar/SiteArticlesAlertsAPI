<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class PostsController extends Controller
{

    function createPost(Request $request){
        try{
            // Validate request
            $validator = validator($request->post(), [
                'site_id' => 'required|exists:sites,id',
                'title' => 'required|string',
                'description' => 'required|string',
            ]);

            if($validator->fails()){
                return $this->json(
                    false,
                    Lang::get('app.validation_errors'),
                    null,
                    $validator->errors()->all()
                );
            }

            // Now create the post
            $post = new Post([
                'title' => $request->post('title'),
                'description' => $request->post('description'),
                'site_id' => $request->post('site_id'),
            ]);

            if($post->save()){
                return $this->json(true, Lang::get('app.post_created'), $post);
            }

            return $this->json(false, Lang::get('app.unexpected_error'));

        }catch(Exception $e){
            return $this->json(false, Lang::get('app.server_error'));
        }

    }

}
