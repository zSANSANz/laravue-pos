<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\EmployeeCollection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Employee;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $r)
    {
        return new EmployeeCollection(Employee::where('name','LIKE',"%".$r->get('search')."%")->orderBy('id','desc')->paginate(10));
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
        $request->validate([
            'name' => 'required',
            'image_name' => 'required',
            'address' => 'required',
            'phone_number' => 'required',
            'slug' => 'required',
        ]);
        $name =  $request->name;
        $slug = str_slug($name,'-');
        $address = $request->address;
        $phone_number = $request->phone_number;
        $image = $request->file('photo');

        // return $request->all();

        $imageName = $image->getClientOriginalName();
        $image->move(public_path('/images/employees'), $imageName);

        $insert = Category::create([
            'name' => $name,
            'address' => $address,
            'phone_number' => $phone_number,
            'slug' => $slug,
            'image_name' => $imageName
        ]);

        return $insert;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Category::findOrFail($id);
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
        $name = $request->name;
        $description = $request->description;
        $slug = str_slug($name);


        if($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $image_name = $photo->getClientOriginalName();
            $photo->move(public_path('/images/categories'), $image_name);

            Category::find($id)->update([
                'name' => $name,
                'description' => $description,
                'slug' => $slug,
                'image_name' => $image_name
            ]);
        }else{
            Category::find($id)->update([
                'name' => $name,
                'description' => $description,
                'slug' => $slug,
            ]);

        }

        return response(['success'=>true], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cat = Category::find($id);
        $cat->delete();

        return [
            'status' => 'true',
            'msg' => 'Kategori '.$cat->name.' Berhasil dihapus'
        ];
    }
}
