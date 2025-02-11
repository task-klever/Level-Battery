<?php

namespace App\Http\Controllers\Front;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use DB;
use App\Services\ElasticsearchService;

class ProductController extends Controller
{
    protected $elasticsearchService;

    public function __construct(ElasticsearchService $elasticsearchService)
    {
        $this->elasticsearchService = $elasticsearchService;
    }

    public function index(Request $request)
    {
        $g_setting = DB::table('general_settings')->where('id', 1)->first();
        $shop = DB::table('page_shop_items')->where('id', 1)->first();
        $products = null;  // Initialize the variable

        // Handle search queries
        $query = $request->input('q');

        // Initialize filter variables
        $brand = $request->input('brand');
        $pattern = $request->input('pattern');
        $oem = $request->input('oem');
        $width = $request->input('width');
        $height = $request->input('height');
        $rim = $request->input('rim');
        $runflat = $request->input('runflat');
        $load_speed = $request->input('load_speed');
        $origin = $request->input('origin');
        $year = $request->input('year');
        
        // Define filter arrays based on the search query
        $filters = []; // Initialize filters array

        if ($brand) {
            $filters['brand_id'] = $brand; // Add brands to filters if brand is set
        }
        if ($pattern) {
            $filters['pattern_id'] = $pattern; // Add patterns to filters if pattern is set
        }
        if ($oem) {
            $filters['oem_id'] = $oem; // Add OEMs to filters if OEM is set
        }
        if ($width) {
            $filters['width'] = $width; // Add width to filters if width is set
        }
        if ($height) {
            $filters['height'] = $height; // Add height to filters if height is set
        }
        if ($rim) {
            $filters['rim'] = $rim; // Add rim to filters if rim is set
        }
        if ($runflat) {
            $filters['runflat'] = $runflat; // Add runflat to filters if runflat is set
        }
        if ($load_speed) {
            $filters['load_speed'] = $load_speed; // Add load speed to filters if load speed is set
        }
        if ($origin) {
            $filters['origin_id'] = $origin; // Add origins to filters if origin is set
        }
        if ($year) {
            $filters['year'] = $year; // Add year to filters if year is set
        }

        if (isset($query) || !empty($filters)) {
            // Search products in Elasticsearch
            $searchResults = $this->elasticsearchService->searchProducts($query, $filters);
            
            if($searchResults && isset($searchResults['hits']['hits'])){
                // Extract IDs from Elasticsearch hits
                $productIds = collect($searchResults['hits']['hits'])->pluck('_id');
                
                if ($productIds->isNotEmpty()) {
                    // Fetch product details from the database
                    $products = DB::table('products')
                        ->whereIn('id', $productIds)
                        ->orderBy('product_order', 'asc')
                        ->paginate(12);
                }
            }

            if(!$products) {
                // Search products in Database if no result from Elasticsearch
                $products = $this->searchProducts($query, $filters);
            }
        } 
        
        if(!$products) {
            // Default case: Fetch products from the database
            $products = DB::table('products')
                ->orderBy('product_order', 'asc')
                ->where('product_status', 'Show')
                ->paginate(12);
        }

        // Define filter arrays
        $brands = [
            ['id' => 1, 'name' => 'Michelin'],
            ['id' => 2, 'name' => 'Bridgestone'],
            ['id' => 3, 'name' => 'Goodyear'],
            ['id' => 4, 'name' => 'Continental'],
            ['id' => 5, 'name' => 'Pirelli']
        ];

        $patterns = [
            ['id' => 1, 'name' => 'All Season'],
            ['id' => 2, 'name' => 'Summer'],
            ['id' => 3, 'name' => 'Winter'],
            ['id' => 4, 'name' => 'All Terrain'],
            ['id' => 5, 'name' => 'Mud Terrain']
        ];

        $oems = [
            ['id' => 1, 'name' => 'BMW'],
            ['id' => 2, 'name' => 'Mercedes'],
            ['id' => 3, 'name' => 'Audi'],
            ['id' => 4, 'name' => 'Volkswagen'],
            ['id' => 5, 'name' => 'Toyota']
        ];

        $origins = [
            ['id' => 1, 'name' => 'Germany'],
            ['id' => 2, 'name' => 'Japan'],
            ['id' => 3, 'name' => 'USA'],
            ['id' => 4, 'name' => 'Italy'],
            ['id' => 5, 'name' => 'France']
        ];
        
        return view('pages.shop', compact('shop', 'g_setting', 'products', 'query', 'brands', 'patterns', 'oems', 'origins'));
    }

    private function searchProducts($query = null, $filters = [])
    {
        // Start building the query
        $productQuery = DB::table('products')
            ->orderBy('product_order', 'asc')
            ->where('product_status', 'Show');

        // Add search query conditions if provided
        if ($query) {
            $productQuery->where(function($q) use ($query) {
                $q->where('product_name', 'like', '%' . $query . '%')
                  ->orWhere('product_content_short', 'like', '%' . $query . '%')
                  ->orWhere('product_content', 'like', '%' . $query . '%')
                  ->orWhere('seo_title', 'like', '%' . $query . '%')
                  ->orWhere('seo_meta_description', 'like', '%' . $query . '%');
            });
        }

        // Add filters to the query
        if (!empty($filters)) {
            foreach ($filters as $key => $value) {
                if (is_array($value)) {
                    $productQuery->whereIn($key, $value); // For array filters
                } else {
                    $productQuery->where($key, $value); // For single value filters
                }
            }
        }

        // Execute the query and paginate results
        $products = $productQuery->paginate(12);

        return $products;
    }

    public function detail($slug)
    {
        $g_setting = DB::table('general_settings')->where('id', 1)->first();
        $product_detail = DB::table('products')->where('product_slug', $slug)->first();
        if(!$product_detail) {
            return abort(404);
        }
        return view('pages.product_detail', compact('g_setting','product_detail'));
    }

    public function add_to_cart(Request $request)
    {
        $product_id = $request->input('product_id');
        $product_qty = $request->input('product_qty');

        $product_detail = DB::table('products')->where('id', $product_id)->first();

        // Check if items available in stock
        if($product_qty > $product_detail->product_stock)  {
            return redirect()->back()->with('error', 'Sorry! There are only '.$product_detail->product_stock.' item(s) in stock');
        }

        // Check if items already added to cart
        if(session()->has('cart_product_id'))
        {
            $arr_cart_product_id = array();
            $i=0;
            foreach(session()->get('cart_product_id') as $value) {
                $arr_cart_product_id[$i] = $value;
                $i++;
            }

            if(in_array($product_id,$arr_cart_product_id)) {
                return redirect()->back()->with('error', 'This product is already added to the shopping cart.');
            }
        }

        session()->push('cart_product_id', $product_id);
        session()->push('cart_product_qty', $product_qty);

        return redirect()->back()->with('success', 'Item is added to the cart successfully!');
    }

    public function cart()
    {
        $g_setting = DB::table('general_settings')->where('id', 1)->first();
        return view('pages.cart', compact('g_setting'));
    }

    public function cart_item_delete($id)
    {
        $arr_cart_product_id = array();
        $arr_cart_product_qty = array();

        $i=0;
        foreach(session()->get('cart_product_id') as $value) {
            $arr_cart_product_id[$i] = $value;
            $i++;
        }

        $i=0;
        foreach(session()->get('cart_product_qty') as $value) {
            $arr_cart_product_qty[$i] = $value;
            $i++;
        }

        session()->forget('cart_product_id');
        session()->forget('cart_product_qty');

        for($i=0;$i<count($arr_cart_product_id);$i++)
        {
            if($arr_cart_product_id[$i] == $id)
            {
                continue;
            }
            else
            {
                session()->push('cart_product_id', $arr_cart_product_id[$i]);
                session()->push('cart_product_qty', $arr_cart_product_qty[$i]);
            }
        }

        return redirect()->back()->with('success', 'Item is deleted from the cart successfully!');
    }

    public function update_cart(Request $request)
    {
        $error_msg = 0;

        // Storing old data into array
        $old_cart_product_id = array();
        $old_cart_product_qty = array();

        $i=0;
        foreach(session()->get('cart_product_id') as $value) {
            $old_cart_product_id[$i] = $value;
            $i++;
        }

        $i=0;
        foreach(session()->get('cart_product_qty') as $value) {
            $old_cart_product_qty[$i] = $value;
            $i++;
        }

        // Removing old data from session
        session()->forget('cart_product_id');
        session()->forget('cart_product_qty');

        // Storing new data into array
        $new_cart_product_id = array();
        $new_cart_product_qty = array();

        $i=0;
        foreach($request->product_id as $value) {
            $new_cart_product_id[$i] = $value;
            $i++;
        }

        $i=0;
        foreach($request->product_qty as $value) {
            $new_cart_product_qty[$i] = $value;
            $i++;
        }

        for($i=0;$i<count($new_cart_product_id);$i++)
        {
            $product_detail = DB::table('products')->where('id', $new_cart_product_id[$i])->first();
            if($new_cart_product_qty[$i] > $product_detail->product_stock)
            {
                session()->push('cart_product_id', $new_cart_product_id[$i]);
                session()->push('cart_product_qty', $old_cart_product_qty[$i]);

                $error_msg = 1;
            }
            else
            {
                session()->push('cart_product_id', $new_cart_product_id[$i]);
                session()->push('cart_product_qty', $new_cart_product_qty[$i]);
            }
        }

        if($error_msg==1)
        {
            return redirect()->back()->with('error', 'Those quantity will not be updated that are more than stock.');
        }

        return redirect()->back()->with('success', 'Cart is updated successfully!');
    }

    public function checkout()
    {
        if(!session()->get('cart_product_id'))
        {
            return redirect()->to('/');
        }
        $g_setting = DB::table('general_settings')->where('id', 1)->first();
        $shipping_data = DB::table('shippings')->orderBy('shipping_order', 'asc')->get();

        if(!session()->get('shipping_id'))
        {
            session()->put('shipping_id', 0);
            session()->put('shipping_cost', '0');
        }

        if(!session()->get('coupon_id'))
        {
            session()->put('coupon_id', 0);
            session()->put('coupon_code', '');
            session()->put('coupon_amount', '0');
        }

        return view('pages.checkout', compact('g_setting', 'shipping_data'));
    }

    public function shipping_update(Request $request)
    {
        $shipping_id = $request->input('shipping_id');
        $shipping_detail = DB::table('shippings')->where('id', $shipping_id)->first();

        session()->put('shipping_id', $shipping_id);
        session()->put('shipping_cost', $shipping_detail->shipping_cost);

        return redirect()->back()->with('success', 'Shipping method is selected successfully!');
    }

    public function coupon_update(Request $request)
    {
        $coupon_code = $request->input('coupon_code');
        $today = date('Y-m-d');

        $coupon_detail = DB::table('coupons')->where('coupon_code', $coupon_code)->first();
		if(!$coupon_detail) {
            return redirect()->back()->with('error', 'Wrong coupon code!');
        }

        $coupon_id = $coupon_detail->id;
        $coupon_discount = $coupon_detail->coupon_discount;
        $coupon_type = $coupon_detail->coupon_type;

        if($coupon_detail->coupon_existing_use == $coupon_detail->coupon_maximum_use) {
            return redirect()->back()->with('error', 'Coupon code is maximum time used!');
        }

        if($today < $coupon_detail->coupon_start_date) {
            return redirect()->back()->with('error', 'Date of this coupon code is not come yet!');
        }

        if($today > $coupon_detail->coupon_end_date) {
            return redirect()->back()->with('error', 'Date of this coupon code is expired!');
        }

        if($coupon_type== 'Percentage') {
            $arr['coupon_amount'] = (session()->get('subtotal') * $coupon_discount)/100;
        } else {
            $arr['coupon_amount'] = $coupon_discount;
        }

        session()->put('coupon_code', $coupon_code);
        session()->put('coupon_amount', $arr['coupon_amount']);
        session()->put('coupon_id', $coupon_id);

        if(!session()->get('shipping_cost')) {
            $temp1 = 0;
        } else {
            $temp1 = session()->get('shipping_cost');
        }

        $final_price = (session()->get('subtotal')+$temp1)-session()->get('coupon_amount');
	    $arr['final_price'] = $final_price;

        return redirect()->back()->with('success', 'Coupon is selected successfully!');
    }
}
