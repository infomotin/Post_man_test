<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Contracts\Validation\Validator as ValidationValidator;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return response()->json([
            'message' => 'Success',
            'data' => $users,
            'success' => true
        ], 201);
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validetor = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
        if ($validetor->fails()) {

            // $validetor['won_error'] = "Not Valide";
            return response()->json([
                'errors' => $validetor->errors(),
                'success' => false,
            ], 401);
        }
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            return response()->json([
                'message' => "User Added Success",
                'success' => true,
                'data' => $user['name'] + "Added Success ",
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => "Some thing Wrong",
                'success' => false,
            ], 400);
        }

        return $request->all();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {

        $data =  User::find($id);
        return response()->json([
            'message' => 'Error',
            'data' => $data,
            'success' => true
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
        // validator block
        $validetor = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',

        ]);
        if ($validetor->fails()) {
            return response()->json([
                'message' => 'Validator Failed',
                'data' => $validetor->error(),
                'success' => false
            ], 400);
        }

        try {
            $editData = User::find($id);
            $editData->name = $request->name;
            $editData->email = $request->email;
            // $editData->name = $request->name;
            $editData->save();
            return response()->json([
                'message' => 'Success',
                'data' => User::find($id),
                'success' => true
            ], 200);
        } catch (Exception $e) {

            return response()->json([
                'message' => 'Un Success',
                'data' => User::find($id),
                'success' => false,
                'error' => $e
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // dd($id);
        $user = User::find($id);
        $user->delete();
        return 'ok';
    }
}
