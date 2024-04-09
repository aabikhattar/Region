<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Person;
use App\Models\MainPerson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // --- store
    public function store(Request $request)
    {
        try{
            DB::beginTransaction();

            $database_connection = request()->header('database_connection');

            config(['database.default' => env('DB_CONNECTION')]);
            //-- create main user
            MainPerson::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'database_connection' => $database_connection
            ]);

            config(['database.default' => $database_connection]);
            Person::create([
                'full_name' => $request->full_name,
                'username' => $request->username
            ]);

            DB::commit();

            return response()->json([
                "success" => true,
                "message" => "Created successfully"
            ]);

        }catch(Exception $ex){
            DB::rollback();
            return response()->json([
                "success" => false,
                "message" => $ex->getMessage()
            ]);
        }
    }

    // --- index all the users for admin (so the connection will be the default connection)
    public function index(Request $request)
    {
        try {
            config(['database.default' => env('DB_CONNECTION')]);
            $result = MainPerson::paginate(isset($request->per_page) ? $request->per_page : env('PAGINATION'));

            return response()->json([
                "success" => true,
                "data" => $result
            ]);

        } catch (Exception $ex) {
            return response()->json([
                "success" => false,
                "message" => $ex->getMessage()
            ]);
        }
    }
}
