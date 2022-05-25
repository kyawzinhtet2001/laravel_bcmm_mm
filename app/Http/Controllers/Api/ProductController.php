<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * product create function
     * @param Request $request
     * @return Response Object
     * @author Kyaw zin htet
     * @created_at 25/5/2022
     */
    public function create(Request $request){
        $insert=[
            "name"=>$request->name,
            "code" => $request->code,
            "description"=>$request->description,
            "created_emp"=>$request->created_emp,
            "updated_emp"=>$request->updated_emp
        ];
        try{
            DB::commit();
            Product::insert($insert);
            return response()->json(["status"=>"ok","message"=>"Inserted Successfully"]);
        }
        catch(\Exception $ex){
            DB::rollBack();
            Log::debug($ex->getMessage());
            return response()->json(["status"=> "NG","message"=>"Something Wrong With Database."]);
        }
    }
    /**
     * reterieve all data from Products table.
     * @author kyaw zin htet
     */
    public function index(){
        $products=Product::where('deleted_at',null)->get();
        Log::info($products);
        #check product is empty
        if(empty($products)){
            return response()->json(["status"=>"NG","message"=>"something Wrong ok"]);
        }
        return response()->json(["status"=>"OK","data"=>$products]);
    }
}
