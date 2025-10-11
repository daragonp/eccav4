<?php

namespace App\Http\Controllers;

use App\DataTables\UserDataTable;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Role;

class AdminController extends Controller
{
    //

    /*  public function index()
    {

        $user = auth()->user();
        if ($user) {
            return view('admin.dashboard');
        } else {
            return view('auth\login');
        }
    } */

    public function index()
    {
        $stats = [
            'users'     => \App\Models\User::count(),
            'verses'    => \App\Models\Verse::count(),
            'schedules' => \App\Models\Schedule::count(),
            'banners'   => \App\Models\Banner::count(),
            'news'      => \App\Models\News::count(),
        ];

        $latestVerses = \App\Models\Verse::orderBy('date', 'desc')->take(5)->get(['id', 'date', 'image', 'video']);
        $latestNews   = \App\Models\News::orderBy('created_at', 'desc')->take(5)->get(['id', 'title', 'created_at']);

        return view('admin.dashboard', compact('stats', 'latestVerses', 'latestNews'));
    }


    public function profile()
    {

        $user = auth()->user();
        //$userinfo = User::all();
        //dd($user);
        if ($user) {
            return view('admin.profile', compact('user'));
        } else {
            return view('auth\login');
        }
    }

    public function user()
    {

        $roles = Role::all();

        return view('admin.new-user', compact('roles'));
    }

    public function ustore(Request $request)
    {

        $userdata = new User();

        $userdata->role_id = $request->select;

        $userdata->name = $request->name;

        $userdata->email = $request->email;

        $userdata->password = $request->password;

        $userdata->created_at = Carbon::now();

        $userdata->updated_at = Carbon::now();

        $userdata->save();

        return redirect()->back()->with('mensaje', 'El usuario ha sido creado');
    }

    public function ushow(UserDataTable $user)
    {

        return $user->render('admin.user.show-user');
    }

    public function uview($id)
    {
        //
        $user = User::findorFail($id);

        return view('admin.user.view-user', compact('user'));
    }

    public function uedit($id, Request $request)
    {

        $userdata = User::find($id);

        $userdata->role_id = $request->role_id;

        $userdata->name = $request->name;

        $userdata->email = $request->email;

        $userdata->password = $request->password;

        $userdata->updated_at = Carbon::now();

        $userdata->save();

        return redirect()->back()->with('mensaje', 'Los datos del usuario han sido actualizados');
    }

    public function udelete($id)
    {

        $user = User::find($id);

        $user->delete();

        return redirect()->back()->with('mensaje', 'Los datos han sido eliminados');
    }

    public function destroy($id)
    {
        //
        $user = User::findorFail($id);

        $user->deleted_at = Carbon::now();

        $user->save();

        return redirect()->back()->with('success', 'La publicación no está disponible al público');
    }

    public function activate($id)
    {
        //
        $user = User::findorFail($id);

        $user->deleted_at = NULL;

        $user->save();

        return redirect()->back()->with('success', 'La publicación ha sido activada al público');
    }
    public function center()
    {

        $hoy = Carbon::now();

        return view('admin.topbar', compact('hoy'));
    }
}
