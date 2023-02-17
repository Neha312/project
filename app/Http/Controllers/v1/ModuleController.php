<?php

namespace App\Http\Controllers\v1;

use App\Models\Module;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class ModuleController extends Controller
{
    public function list()
    {
        $modules = Module::get();
        return response()->json([
            "success" => true,
            "message" => "Module List",
            "data"    => $modules
        ]);
    }
    public function create(Request $request)
    {

        $this->validate($request, [
            'module_code' => 'required|string',
            'name'        => 'required|string',
            'is_active'   => 'nullable|boolean',
            'is_in_menu'  => 'nullable|boolean'
        ]);

        $module = Module::create($request->only('module_code', 'name', 'is_active', 'is_in_menu'));
        return response()->json([
            "success" => true,
            "message" => "Module created successfully.",
            "data"    => $module
        ]);
    }
    // public function view()
    // {
    //     $modules = Module::all();
    //     return response()->json([
    //         "success" => true,
    //         "message" => "Module List.",
    //         'data'    => $modules
    //     ]);
    // }
    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'module_code' => 'required|string',
            'name'        => 'required|string',
            'is_active'   => 'nullable|boolean',
            'is_in_menu'  => 'nullable|boolean'
        ]);
        Module::findOrFail($id)->update($request->only('module_code', 'name', 'is_active', 'is_in_menu'));
        return response()->json([
            "success" => true,
            "message" => "Module Updated successfully.",
        ]);
    }
    public function delete($id)
    {
        Module::findOrFail($id)->delete();
        return response()->json([
            "success" => true,
            "message" => "Module deleted successfully.",
        ]);
    }
}
