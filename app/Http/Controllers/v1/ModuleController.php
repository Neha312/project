<?php

namespace App\Http\Controllers\v1;

use App\Models\Module;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ModuleController extends Controller
{
    /**
     * API of List Module
     *
     *@param  \Illuminate\Http\Request  $request
     *@return $module
     */
    public function list(Request $request)
    {
        //validation
        $this->validate($request, [
            'per_page'    => 'required|numeric',
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
            $modules  =   $modules->orderBy('id', 'DESC');
        }
        //searching
        if (isset($request->name)) {
            $modules->where("name", "LIKE", "%{$request->name}%");
        }
        //pagination
        $per_page = $request->per_page;
        $page    = $request->page;
        $modules = $modules->skip($per_page * ($page - 1))->take($per_page);
        return response()->json([
            "success" => true,
            "message" => "Module List",
            "data"    => $modules->get()
        ]);
    }
    /**
     * API of Create module
     *
     *@param  \Illuminate\Http\Request  $request
     *@return $module
     */
    public function create(Request $request)
    {
        //validation code
        $this->validate($request, [
            'module_code' => 'required|string|unique:modules,module_code',
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
    /**
     * API of get perticuler module details.
     *
     *@param $id
     *@return $module
     */
    public function view($id)
    {
        $module = Module::findOrFail($id);
        return response()->json([
            "success" => true,
            "message" => "Module retrieved successfully.",
            "data"    => $module
        ]);
    }
    /**
     * API of Update Module.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     */
    public function update(Request $request, $id)
    {
        //validation code
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

    /**
     * API of Delete Module.
     *
     *@param  \Illuminate\Http\Request  $request
     *@param $id
     */
    public function delete($id, Request $request)
    {
        //validation
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
    /**
     * API of Restore Module.
     *
     * @param $id
     */
    public function restoreData($id)
    {
        Module::onlyTrashed()->findOrFail($id)->restore();
        return response()->json([
            "success" => true,
            "message" => "Module Restored successfully.",
        ]);
    }
}
