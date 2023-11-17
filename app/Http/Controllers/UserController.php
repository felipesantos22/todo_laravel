<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use PHPUnit\TextUI\XmlConfiguration\ValidationResult;

class UserController extends Controller
{


    //Web
    public function index()
    {
        $users = User::paginate(4);
        return view('users', compact('users'));
    }

    //Api
    public function indexApi()
    {
        $users = User::all();
        return $users;
    }


    //Web
    public function create()
    {
        return view('form');
    }

    //Api
    public function storeApi(Request $request)
    {
        try {
            $rules = [
                'name' => 'required|string|max:255:users',
                'email' => 'required|email|unique:users',
            ];
            $request->validate($rules);
            User::create($request->all());
            return response()->json(['message' => 'Item criado com sucesso']);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()], 422);
        }
    }

    //Web
    public function store(Request $request)
    {
        $users = User::create($request->all());
        return redirect('/user')->with('success', 'Usuário criado com sucesso!');
    }


    //Api
    public function showApi($id)
    {
        $users = User::find($id);
        if (!$users) {
            return response()->json(['message' => 'User not found'], 404);
        } else {
            return $users;
        }
    }

    //Web
    public function show($id)
    {
        $user = User::find($id);
        if ($user) {
            return view('details', compact('user'));
        }
    }


    //Api
    public function searchApi(Request $request)
    {
        try {
            $name = $request->input('name');
            dd($name);
            $users = User::where('name', 'like', "%$name%")->get();
            dd($users);
            return response()->json(['users' => $users]);
        } catch (ValidationResult $e) {
            return response()->json(['error' => 'O parâmetro "name" é obrigatório.'], 400);
        }
    }



    // Web
    public function searchWeb(Request $request)
    {
        $name = $request->input('name');
        $users = User::where('name', 'like', "%$name%")->get();
        return view('users', compact('users'));
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, int $id)
    {
        //
    }


    //Web
    public function destroy($id)
    {
        $users = User::find($id);
        if ($users) {
            $users->delete();
        }
        return redirect('/user');
    }


    //Api
    public function destroyApi($id)
    {
        $users = User::find($id);
        if (!$users) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $users->delete();
        return response()->json(['message' => 'User excluído com sucesso']);
    }
}
