<?php

namespace App\Http\Controllers\v1;

use App\Models\Module;
use Illuminate\Http\Request;



class ModuleController extends Controller
{
    //listing of modules
    public function list(Request $request)
    {
        //validation
        $this->validate($request, [
            'perpage'    => 'required|numeric',
            'page'       => 'required|numeric',
            'sort_field' => 'nullable|string',
            'sort_order' => 'nullable|in:asc,desc',
            'name'       => 'nullable|string',
        ]);
        $modules = Module::query();
        //sorting
        if ($request->sort_field && $request->sort_order) {
            $modules  =   $modules->orderBy($request->sort_field, $request->sort_order);
        } else {
            $modules =   $modules->orderBy('id', 'DESC');
        }
        //searching
        if (isset($request->name)) {
            $modules->where("name", "LIKE", "%{$request->name}%");
        }
        //pagination
        $perpage = $request->perpage;
        $page    = $request->page;
        $modules = $modules->skip($perpage * ($page - 1))->take($perpage);
        return response()->json([
            "success" => true,
            "message" => "Module List",
            "data"    => $modules->get()
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
