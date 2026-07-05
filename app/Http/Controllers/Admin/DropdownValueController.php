<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use Illuminate\Support\Str;


class DropdownValueController extends Controller
{
    public function index()
    {
        $dropdowns = DB::table('dropdown_values')
            ->orderBy('sort_order')
            ->get();

        return view('Admin.dropdown.index', compact('dropdowns'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'module'=>'required',
            'dropdown_name'=>'required',
            'option_text'=>'required',
            'type'=>'required',
            'sort_order'=>'required|numeric',
            'status'=>'required'
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status'=>0,
                'errors'=>$validator->errors()
            ]);
        }

		$optionValue = Str::slug($request->option_text, '_');
        DB::table('dropdown_values')->insert([
            'module'=>$request->module,
            'dropdown_name'=>$request->dropdown_name,
			'option_text'=>$request->option_text,
            'option_value'=>$optionValue,
            'type'=>$request->type,
            'sort_order'=>$request->sort_order,
            'status'=>$request->status,
            'created_at'=>now(),
            'updated_at'=>now()
        ]);

        return response()->json([
            'status'=>1,
            'msg'=>'Saved Successfully'
        ]);

    }

    public function edit($id)
    {
        return response()->json(

            DB::table('dropdown_values')->where('id',$id)->first()

        );
    }

    public function update(Request $request,$id)
    {

        $validator = Validator::make($request->all(), [

            'module'=>'required',

            'dropdown_name'=>'required',

            'option_text'=>'required',

            'sort_order'=>'required|numeric',

            'status'=>'required'

        ]);

        if($validator->fails())
        {
            return response()->json([
                'status'=>0,
                'errors'=>$validator->errors()
            ]);
        }

		$optionValue = Str::slug($request->option_text, '_');
        DB::table('dropdown_values')
        ->where('id',$id)
        ->update([

            'module'=>$request->module,

            'dropdown_name'=>$request->dropdown_name,

            //'option_value'=>$optionValue,

            'option_text'=>$request->option_text,

            'sort_order'=>$request->sort_order,

            'status'=>$request->status,

            'updated_at'=>now()

        ]);

        return response()->json([
            'status'=>1,
            'msg'=>'Updated Successfully'
        ]);

    }

    public function show($id)
    {
        return response()->json(

            DB::table('dropdown_values')->where('id',$id)->first()

        );
    }

    public function destroy($id)
    {

        DB::table('dropdown_values')
        ->where('id',$id)
        ->delete();

        return response()->json([
            'status'=>1
        ]);

    }

}
