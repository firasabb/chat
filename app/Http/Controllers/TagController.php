<?php

namespace App\Http\Controllers;

use App\Tag;
use Illuminate\Http\Request;
use Validator;
use URL;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::orderBy('id', 'desc')->paginate(20);
        return view('tags.tags', ['tags' => $tags]);
    }


    public function adminIndex($tags = null)
    {
        if(!$tags){
            $tags = Tag::orderBy('id', 'desc')->paginate(20);
        } else {
            $tags = $tags->paginate(20);
        }
        return view('admin.tags.tags', ['tags' => $tags]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function adminAdd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:40',
            'url' => 'required|string'
        ]);

        if($validator->fails()){
            return redirect('/admin/dashboard/tags')->withErrors($validator)->withInput();
        } 

        $tag = new Tag();
        $tag->name = strToLower($request->name);
        $request->url = Str::slug($request->url, '-');
        $check = Tag::where(['deleted_at' => NULL, 'url' => $request->url])->first();
        if(!empty($check)){
            return redirect('/admin/dashboard/tags/')->withErrors('The url has already been taken.')->withInput();
        }
        $tag->url = $request->url;
        $tag->save();

        return redirect('/admin/dashboard/tags')->with('status', 'A new tag has been created!');


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function adminShow($id)
    {
        $tag = Tag::findOrFail($id);
        return view('admin.tags.show', ['tag' => $tag]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function adminEdit(Request $request, $id)
    {
        $tag = Tag::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:40',
            'url' => 'string'
        ]);

        if($validator->fails()){
            return redirect('/admin/dashboard/tag/' . $id)->withErrors($validator)->withInput();
        } 

        $tag->name = strToLower($request->name);
        $request->url = Str::slug($request->url, '-');
        if($request->url != $tag->url){
            $check = Tag::where(['deleted_at' => NULL, 'url' => $request->url])->first();
            if(!empty($check)){
                return redirect('/admin/dashboard/tag/' . $id)->withErrors('The url has already been taken.')->withInput();
            }
            $tag->url = $request->url;
        }
        $tag->save();

        return redirect('/admin/dashboard/tag/' . $id)->with('status', 'This tag has been edited');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function adminDestroy($id)
    {
        $tag = Tag::findOrFail($id);
        $tag->delete();
        return redirect('/admin/dashboard/tags/')->with('status', 'A tag has been deleted!');
    }


    /**
     * Search the tags for admins.
     *
     * @param  Request
     * @return \Illuminate\Http\Response
     */

    public function adminSearchTags(Request $request){

        $validator = Validator::make($request->all(), [
            'id' => 'integer|nullable',
            'name' => 'string|max:300|nullable'
        ]);

        if($validator->fails() || empty($request->all())){
            return redirect('/admin/dashboard/tags/')->withErrors($validator)->withInput();
        }

        $name = $request->name;
        $id = $request->id;
        
        $where_arr = array();

        if($name){

            $name_where = ['name', 'LIKE', '%' . $name . '%'];
            array_push($where_arr, $name_where);

        } if ($id){

            $id_where = ['id', '=', $id];
            array_push($where_arr, $id_where);

        }

        $tags = Tag::where($where_arr);

        if(empty($tags)){
            return $this->adminIndex();
        }
        return $this->adminIndex($tags);
    }


}
