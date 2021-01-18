<?php

namespace App\Http\Controllers;

use App\Models\Branchs;
use App\Models\Districts;
use App\Models\Provinces;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {

        $result = User::query();

        $result->select('users.*', 'branchs.branch_name')
            ->join('branchs', 'users.branch_id', 'branchs.id');

        if ($request->name != '') {
            $result->where('users.name', $request->name);
        }

        if ($request->enabled != '') {
            if ($request->enabled == "1") {
                $result->where('users.enabled', '1');
            } else {
                $result->where('users.enabled', '<>', '1');
            }
        }

        if ($request->branch_id != '') {
            $result->where('branch_id', $request->branch_id);
        }

        if ($request->email != '') {
            $result->where('email', $request->email);
        }

        $all_users = $result->orderBy('users.id', 'desc')
            ->get();

        if ($request->page != '') {
            $result->offset(($request->page - 1) * 25);
        }

        $users = $result->orderBy('users.id', 'desc')
            ->limit(25)
            ->get();

        $pagination = [
            'offsets' =>  ceil(sizeof($all_users) / 25),
            'offset' => $request->page ? $request->page : 1,
            'all' => sizeof($all_users)
        ];

        $branchs = Branchs::where('enabled', '1')->get();

        return view('users', compact('users', 'pagination', 'branchs'));
    }


    public function insert(Request $request)
    {
        $user = new User;
        $user->name = $request->name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->branch_id = $request->branch_id;
        $user->is_admin = '0';
        $user->enabled = '1';
        $user->phone_no = $request->phone_no;

        try {
            if ($user->save()) {
                return redirect('users')->with(['error' => 'insert_success']);
            } else {
                return redirect('users')->with(['error' => 'not_insert']);
            }
        } catch (\Throwable $th) {
            return redirect('users')->with(['error' => 'not_insert']);
        }
    }

    public function edit($id)
    {
        $user = User::where('id', $id)->first();
        $branchs = Branchs::where('branchs.enabled', '1')->get();

        return view('editUser', compact('user', 'branchs'));
    }

    public function update(Request $request)
    {
        $user = [
            'name' => $request->name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'branch_id' => $request->branch_id,
            'is_admin' => Auth::user()->id == $request->id ? '1' : '0',
            'enabled' => '1',
            'phone_no' => $request->phone_no
        ];

        if (User::where('id', $request->id)->update($user)) {
            return redirect('users')->with(['error' => 'insert_success']);
        } else {
            return redirect('users')->with(['error' => 'not_insert']);
        }
    }

    public function delete($id)
    {
        $user_data = User::where('id', $id)->first();

        $user = [
            'enabled' => $user_data->enabled == "1" ? "0" : "1"
        ];

        if (User::where('id', $id)->update($user)) {
            return redirect('users')->with(['error' => 'insert_success']);
        } else {
            return redirect('users')->with(['error' => 'not_insert']);
        }
    }
}
