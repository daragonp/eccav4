<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Role;
use Illuminate\Http\Request;
use App\DataTables\RoleDataTable;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $allroles = Role::orderBy('id', 'ASC')->paginate(2);

        return view('allroles', compact('allroles'));

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

    public function roles(){

        return view('admin.new-role');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $role = new Role();

        $role->name = $request->name;

        $role->created_at = Carbon::now();

        $role->updated_at = Carbon::now();

        $role->save();

        return redirect()->back()->with('mensaje', 'El rol ha sido creado.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(RoleDataTable $role)
    {
        //
        return $role->render('admin.role.show-role');

    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        //
        $role = Role::find($id);

        $role->name = $request->name;

        $role->updated_at = Carbon::now();

        $role->save();

        return redirect()->back()->with('mensaje', 'El role ha sido actualizado');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        //
        $role = Role::find($id);

        return view('admin.update-role', compact('role'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        //
        $role = Role::find($id);

        $role->delete();

        return redirect()->back()->with('mensaje', 'El role ha sido eliminado');
    }
}
