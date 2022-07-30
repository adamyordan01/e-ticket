<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        return view('products.index');
    }

    public function getProducts()
    {
        $products = Product::orderBy('name', 'asc');
        
        return DataTables::of($products)
            ->addIndexColumn()
            ->editColumn('created_at', function ($product) {
                return Carbon::parse($product->created_at)->format('d-m-Y');
            })
            ->editColumn('updated_at', function ($product) {
                return Carbon::parse($product->updated_at)->format('d-m-Y');
            })
            ->editColumn('date_event', function ($product) {
                if ($product->date_event == null) {
                    return '-';
                } else {
                    return Carbon::parse($product->date_event)->format('d-m-Y');
                }
            })
            ->editColumn('price', function ($product) {
                return 'Rp. ' . number_format($product->price, 0, ',', '.');
            })
            ->editColumn('tax', function ($product) {
                return $tax = $product->tax . '%';
            })
            ->editColumn('action', function ($product) {
                $buttonEdit = '<button data-toggle="tooltip" data-placement="top" title="Edit Produk" data-id="'.$product->id.'" id="editButton" class="btn btn-info"><i class="fas fa-edit"></i></button>';
                $buttonDelete = '<button data-toggle="tooltip" data-placement="top" title="Delete Produk" data-id="'.$product->id.'" id="deleteButton" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>';

                return $buttonEdit;
            })
            ->addColumn('image', function($product) {
                if ($product->image) {
                    return '<img src="'.asset('product/'.$product->image).'" width="120px">';
                } else {
                    return '<img src="https://via.placeholder.com/100">';
                }
            })
            ->editColumn('status', function ($product) {
                if ($product->status == 1) {
                    return '<span class="badge badge-success">Aktif</span>';
                } else {
                    return '<span class="badge badge-dark">Tidak Aktif</span>';
                }
            })
            ->rawColumns(['action', 'image', 'status'])
            ->make(true);
    }

    public function getProductDetail(Request $request)
    {
        $product_id = $request->product_id;

        $productDetail = Product::find($product_id);
        return response()->json([
            'detail' => $productDetail
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required',
            'tax' => 'required|numeric',
            'date_event' => 'required|date',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'name.required' => 'Nama Produk harus diisi',
            'price.required' => 'Harga Produk harus diisi',
            'tax.required' => 'Pajak Produk harus diisi',
            'tax.numeric' => 'Pajak Produk harus berupa angka',
            'date_event.required' => 'Tanggal Event harus diisi',
            'date_event.date' => 'Tanggal Event harus berupa tanggal',
            'image.required' => 'Foto Produk harus diisi',
            'image.image' => 'Foto Produk harus berupa gambar',
            'image.mimes' => 'Foto Produk harus berupa gambar yang memiliki ekstensi jpeg, png, jpg, gif, svg',
            'image.max' => 'Foto Produk tidak boleh lebih dari 2048 kb',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 0,
                'error' => $validator->errors()->toArray(),
            ]);
        } else {
            // check if image exists
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $name = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/product');
                $image->move($destinationPath, $name);
            } else {
                $name = '';
            }

            $price = str_replace('.', '', $request->price);
            // dd($price);

            $product = new Product();
            $product->product_code = strtotime(date('Y-m-d H:i:s')) . rand(0, 9);
            $product->name = $request->name;
            $product->price = $price;
            $product->tax = $request->tax;
            $product->date_event = $request->date_event;
            $product->image = $name;
            $product->status = 1;
            $query = $product->save();

            if ($query) {
                return response()->json([
                    'code' => 1,
                    'message' => 'Product created successfully',
                ]);
            } else {
                return response()->json([
                    'code' => 0,
                    'message' => 'Something went wrong',
                ]);
            }
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $product = Product::where('id', $id)->first();

        return response()->json($product);
    }

    public function update(Request $request)
    {
        $product_id = $request->product_id;

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required',
            'tax' => 'required|numeric',
            'date_event' => 'required|date',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 0,
                'error' => $validator->errors()->toArray(),
            ]);
        } else {
            // check if image exists
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $name = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/product');
                $image->move($destinationPath, $name);

                $price = str_replace('.', '', $request->price);

                $product = Product::find($product_id);
                $product->name = $request->name;
                $product->price = $price;
                $product->tax = $request->tax;
                $product->date_event = $request->date_event;
                $product->status = $request->status;
                $product->image = $name;
                $query = $product->save();
            }

            $price = str_replace('.', '', $request->price);

            $product = Product::find($product_id);
            $product->name = $request->name;
            $product->price = $price;
            $product->tax = $request->tax;
            $product->date_event = $request->date_event;
            $product->status = $request->status;
            // $product->image = $name;
            $query = $product->save();

            if ($query) {
                return response()->json([
                    'code' => 1,
                    'message' => 'Product updated successfully',
                ]);
            } else {
                return response()->json([
                    'code' => 0,
                    'message' => 'Something went wrong',
                ]);
            }
        }
    }

    public function destroy($id)
    {
        $data = Product::where('id', $id)->first(['image']);
        \File::delete('public/product/' . $data->image);
        $product = Product::where('id', $id)->delete();
        return response()->json($product);
    }
}
