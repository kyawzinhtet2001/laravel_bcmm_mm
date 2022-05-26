<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use Exception;
/**
 * This is product endpoint crud controller
 *
 * @author kyaw zin htet
 * @create 25/5/2022
 */
class ProductController extends Controller
{
    /**
     * product create function
     * @param Request $request
     * @return Response Object
     * @author Kyaw zin htet
     * @create 25/5/2022
     */
    public function create(Request $request)
    {
        $insert = [
            "name" => $request->name,
            "code" => $request->code,
            "description" => $request->description,
            "created_emp" => $request->created_emp,
            "updated_emp" => $request->updated_emp
        ];
        try {
            DB::commit();
            if(Product::where('code',$insert["code"])->exists()){
                return response()->json(["status" => "NG", "message" => "The Code is Duplicated but code need to be unique"]);
            }
            Product::insert($insert);
            return response()->json(["status" => "ok", "message" => "Inserted Successfully"]);
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::debug($ex->getMessage());
            return response()->json(["status" => "NG", "message" => "Something Wrong With Database."]);
        }
    }
    public function show(int $id){
        $p=Product::find($id);
        if($p!==null){
            return response()->json(["status" => "OK", "data" => $p]);
        }
        return response()->json(["status" => "NG", "message" => "No Data Found"]);
    }
    /**
     * reterieve all data from Products table.
     * @author kyaw zin htet
     * @create 25/5/2022
     */
    public function index()
    {
        $products = Product::where('deleted_at', null)->get();
        Log::info($products);
        #check product is empty
        if ($products->isEmpty()) {
            return response()->json(["status" => "NG", "message" => "No Data Found"]);
        }
        return response()->json(["status" => "OK", "data" => $products]);
    }
    /**
     * This update the Product.
     * @param Request $request
     * @param int $id
     * @return jsonstrng
     * @create 26/5/2022
     */
    public function update(Request $request, int $id)
    {
        $update = [
            "name" => $request->name,
            "code" => $request->code,
            "description" => $request->description,
            "updated_emp" => $request->logged_in_id,
            "updated_at" => now()
        ];
        DB::beginTransaction();
        try {
            $product = Product::where('id', $id)->get()->first();
            // dd($product);
            // dd(!empty($product));
            if ($product!=null){
                $product->update($update);
                DB::commit();
                return response()->json(['status' => "OK", "message" => "This is updated"]);
            }
            #throw error because product not found.
            throw new \Exception();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::debug($e->getMessage());
            return response()->json(['status' => "NG", "message" => "Product does not existss"]);
        }
    }
    /**
     * This is Product delete function.
     * @param int $id
     * @return string $json
     * @author kyawzinhtet
     * @create 26/5/2022
     */
    public function destory(int $id){
        DB::beginTransaction();
        try{
            $product=Product::where('id',$id)->get()->first();
            #check product is null
            if($product!=null){
                $product->delete();
                DB::commit();
                return response()->json(["status"=>"OK","message" => "product is deleted"]);
            }
            #throw Exceptin because product not found.
            throw new Exception();
        }catch(\Exception $e){
            DB::rollBack();
            Log::debug($e->getMessage());
            return response()->json(["status"=>"NG","message" => "product is already deleted"]);
        }
    }
}
