<?php

namespace App\Http\Controllers\User;

use App\User;
use function bcrypt;
use function dd;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use function response;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        return $this->showAll($users);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6|max:10'
        ];

        $this->validate($request, $rules);

        $fields = $request->all();
        $fields['password'] = bcrypt($request->password);
        $fields['verified'] = User::USUARIO_NO_VERIFICADO;
        $fields['verification_token'] = User::generarVerificationToken();
        $fields['admin'] = User::USUARIO_REGULAR;

        $user = User::create($fields);

        return response()->json(['data' => $user], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return $this->showOne($user);
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
        $user = User::findOrFail($id);

        $rules = [
            'email' => 'email|unique:users,email,' . $user->id,
            'password' => 'confirmed|min:6|max:10',
            'admin' => 'in:' . User::USUARIO_REGULAR . ',' . User::USUARIO_REGULAR,
        ];

        $this->validate($request, $rules);

        if ($request->has('name')){
            $user->name = $request->name;
        }
        if ($request->has('email') && $user->email != $request->email){
                $user->verified = User::USUARIO_NO_VERIFICADO;
                $user->verification_token = User::generarVerificationToken();
                $user->email = $request->email;
        }
        if ($request->has('password')){
            $user->password = bcrypt($request->password);
        }
        if ($request->has('admin')){
            if (!$user->esVerificado()){
                return  $this->errorResponse('Unicamente los usuarios verificados pueden cambiar su valor de administrador', 409);
            }
            $user->admin =$request->admin;
        }
        if (!$user->isDirty()){
            return $this->errorResponse('Se deben especificar al menos un valor diferente para actualizar', 422);

        }

        $user->save();

        return response()->json(['data' => $user],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return response()->json(['data' => $user], 200);
    }
}
