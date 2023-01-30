<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // properties all
        $property = Property::with('User')->with('TypeProperty')->with('images')->paginate(10);
        return response()->json([
            'status' => "success",
            'image_path' => env("APP_URL", "localhost:8000")."/images",
            "data" => $property,
        ], 200);
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
    public function store(Request $request)
    {
        $fields = $request->validate([
            'name_property' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|integer',
            'type_property' => 'required|integer',
            'id_kota' => 'required|integer',
            'id_user' => 'required|integer',
            'images' => 'required|array|file|image'
        ]);
        // ini create
        $imageName = time().'.'.$request->images->name().$request->images->extension();  
     
        $request->images->move(public_path('images'), $imageName);

        $fields['images'] = $imageName;

        $create = Property::create($fields);
        $image_create = Image::create([
            'id_property' => $create->id,
            'image' => $fields['images']
        ]);
        return response()->json([
            'status' => "success",
            "data" => $create,
            'images' => $image_create
        ], 200);


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $property_detail = Property::with('User')->with('images')->with('TypeProperty')->with('offer')->with('kota')->find($id);
        return response()->json([
            'status' => "success",
            "data" => $property_detail,
        ], 200);   
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $fields = $request->validate([
            'name_property' => 'string',
            'description' => 'string',
            'price' => 'integer',
            'type_property' => 'integer',
            'id_kota' => 'integer',
            'id_user' => 'integer',
            'images' => 'file|image'
        ]);
        // ini update
        if($fields['images']){
            $imageName = time().'.'.$request->images->extension();  
     
        $request->images->move(public_path('images'), $imageName);

        $fields['images'] = $imageName;
        $image_where = Image::where('id_property', $id)->first();
        if (File::exists(public_path('images')."/".$image_where->image)) {
            File::delete(public_path('images')."/".$image_where->image);
        }
        $imageupdate = $image_where->update([
            'id_property' => $id,
            'image' => $fields['images']
        ]);

        }

        $update = Property::find($id)->update($fields);
        return response()->json([
            'status' => "success",
            "data" => $update,
        ], 200);
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
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Property::destroy($id);
        return response()->json([
            'status' => "success",
        ], 200);   
    }
}
