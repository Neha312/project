<?php

namespace App\Http\Controllers\v1;

use App\Models\Module;
use Illuminate\Http\Request;



class ModuleController extends Controller
{
    //listing of modules
    public function list()
    {
        $modules = Module::get();
        return response()->json([
            "success" => true,
            "message" => "Module List",
            "data"    => $modules
        ]);
    }
    //create module
    public function create(Request $request)
    {
        //validation code
        $this->validate($request, [
            'module_code' => 'required|string',
            'name'        => 'required|string',
            'is_active'   => 'nullable|boolean',
            'is_in_menu'  => 'nullable|boolean'
        ]);
        //create module
        $module = Module::create($request->only('module_code', 'name', 'is_active', 'is_in_menu'));
        //send response
        return response()->json([
            "success" => true,
            "message" => "Module created successfully.",
            "data"    => $module
        ]);
    }
    //view module
    public function view($id)
    {
        //find module
        $module = Module::findOrFail($id);
        //send response
        if (is_null($module)) {
            return $this->sendError('Module not found.');
        }
        return response()->json([
            "success" => true,
            "message" => "Module retrieved successfully.",
            "data"    => $module
        ]);
    }
    //update module
    public function update(Request $request, $id)
    {
        //validation code
        $this->validate($request, [
            'module_code' => 'required|string',
            'name'        => 'required|string',
            'is_active'   => 'nullable|boolean',
            'is_in_menu'  => 'nullable|boolean'
        ]);
        //update module
        Module::findOrFail($id)->update($request->only('module_code', 'name', 'is_active', 'is_in_menu'));
        //send response
        return response()->json([
            "success" => true,
            "message" => "Module Updated successfully.",
        ]);
    }
    //delete module
    public function delete($id, Request $request)
    {
        $this->validate($request, [
            'soft_delete' => 'required|bool'
        ]);
        if ($request->soft_delete) {
            Module::findOrFail($id)->delete();
        } else {
            Module::findOrFail($id)->forceDelete();
        }
        return response()->json([
            "success" => true,
            "message" => "Module deleted successfully.",
        ]);
    }
    public function restoreData($id)
    {
        Module::onlyTrashed()->findOrFail($id)->restore();
        return response()->json([
            "success" => true,
            "message" => "Module Restored successfully.",
        ]);
    }
}
