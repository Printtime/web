<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Product;
use App\Model\Catalog;
use App\Model\Type;
use App\Model\TypeVar;
use App\Model\Variable;
use DB;

class ProductController extends Controller
{
    public function show($catalog, $product)
    {   
        $product = Product::findOrfail($product);
        $catalog = Catalog::findOrfail($catalog);
        return view('product.show', ['catalog'=> $catalog, 'product'=> $product]);
    }

   public function order(Request $request, $product)
    {
        if($request->ajax()){
            return view('product.order.index', ['product'=> $product]);
        } 
    }

    public function orderSend(Request $request, $product)
    {	
        if($request->ajax()){

			    $client = new \GuzzleHttp\Client();

			    $qtext = urlencode($request->email.' '.$request->phone.' '.$request->comment);
 				$q = 'https://api.telegram.org/bot'.$_ENV["TELEGRAM_BOT_ID"].'/sendMessage?chat_id='.$_ENV["TELEGRAM_CHAT_ID"].'&text='.$qtext.'';

 				$body = $client->get($q)->getBody();

				$obj = json_decode($body);
				

 				if($obj->ok == true)  {
 					return view('product.order.success');
 				}

        }
    }

   public function product($product)
    {   

        $types = DB::table('types')->where('product_id', $product)->get();

        $rows = DB::table('type_var')
            ->Join('types', 'type_var.type_id', '=', 'types.id')
            ->Join('vars', 'type_var.var_id', '=', 'vars.id')
            ->where('types.product_id', $product)
            ->select('*', 'type_var.id as type_var_id')
            ->get();

        $headers = DB::table('types')
            ->where('types.product_id', $product)
            ->Join('type_var', 'type_var.type_id', '=', 'types.id')
            ->Join('vars', 'vars.id', '=', 'type_var.var_id')
            ->groupby('vars.id')
            ->get();


            foreach ($types as $type) {
                foreach ($rows as $row) {
                    if($type->id == $row->type_id)
                    {   
                        $type->data[$row->var_id] = $row;
                    }
                }

                foreach($headers as $header) {

                    $type->res[$header->id] = 'no-data';

                    if(isset($type->data[$header->id])) {
                        $type->res[$header->id] = $type->data[$header->id];
                    }
                }
            }
        return view('product.table',  compact('rows', 'types', 'headers'));
    }


   public function product_all($catalog)
    {   

        $types = DB::table('products')
            ->orderBy('products.order_group', 'asc')->orderBy('products.title', 'asc')
            ->Join('types', 'types.product_id', '=', 'products.id')
            ->select('products.*', 'products.title as products_title', 'types.*')
            ->where('products.catalog_id', $catalog)
            
            ->get();



        $rows = DB::table('type_var')
            ->Join('types', 'type_var.type_id', '=', 'types.id')
                ->Join('products', 'types.product_id', '=', 'products.id')
            ->Join('vars', 'type_var.var_id', '=', 'vars.id')
            ->select('*', 'type_var.id as type_var_id')
                ->where('products.catalog_id', $catalog)
            ->get();
            


        $headers = DB::table('types')
            ->Join('products', 'types.product_id', '=', 'products.id')
            ->where('products.catalog_id', $catalog)
            ->Join('type_var', 'type_var.type_id', '=', 'types.id')
            ->Join('vars', 'vars.id', '=', 'type_var.var_id')
            ->groupby('vars.id')
            ->orderBy('vars.order', 'asc')
            ->get();


            foreach ($types as $type) {
                foreach ($rows as $row) {
                    if($type->id == $row->type_id)
                    {   
                        $type->data[$row->var_id] = $row;
                    }
                }

                foreach($headers as $header) {

                    $type->res[$header->id] = 'no-data';

                    if(isset($type->data[$header->id])) {
                        $type->res[$header->id] = $type->data[$header->id];
                    }
                }
            }

            $catalog = Catalog::findOrfail($catalog);
            $title = $catalog->title;

        return view('product.table',  compact('rows', 'types', 'headers', 'title'));
    }


   public function products()
    {   
       #$catalogs = Catalog::with('products2order')->orderBy('order', 'asc')->get();
       $catalogs = Catalog::orderBy('order', 'asc')->get();
       return view('product.index',  compact('catalogs'));
    }


}
