<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brand = Brand::orderBy('id', 'DESC')->paginate();
        return view('backend.brand.index')->with('brands', $brand);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.brand.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'string|required',
        ]);
        $data = $request->all();
        $slug = Str::slug($request->title);
        $count = Brand::where('slug', $slug)->count();
        if ($count > 0) {
            $slug = $slug . '-' . date('ymdis') . '-' . rand(0, 999);
        }
        $data['slug'] = $slug;
        // return $data;
        $status = Brand::create($data);
        if ($status) {
            request()->session()->flash('success', 'Brand created successfully');
        } else {
            request()->session()->flash('error', 'Error, Please try again');
        }
        return redirect()->route('brand.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $brand = Brand::find($id);
        if (!$brand) {
            request()->session()->flash('error', 'Brand not found');
        }
        return view('backend.brand.edit')->with('brand', $brand);
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
        // Temukan brand berdasarkan ID
        $brand = Brand::find($id);

        // Validasi input
        $this->validate($request, [
            'title' => 'string|required',
            'status' => 'required|in:active,inactive',
        ]);

        // Ambil semua data dari request
        $data = $request->all();

        // Set title dan slug
        $brand->title = $data['title'];
        $brand->slug = \Str::slug($data['title']); // Update slug berdasarkan title
        $brand->status = $data['status'];

        // Simpan perubahan
        $status = $brand->save();

        // Set flash message berdasarkan status penyimpanan
        if ($status) {
            $request->session()->flash('success', 'Brand updated successfully');
        } else {
            $request->session()->flash('error', 'Error, Please try again');
        }

        // Redirect kembali ke daftar brand
        return redirect()->route('brand.index');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $brand = Brand::find($id);
        if ($brand) {
            $status = $brand->delete();
            if ($status) {
                request()->session()->flash('success', 'Brand deleted successfully');
            } else {
                request()->session()->flash('error', 'Error, Please try again');
            }
            return redirect()->route('brand.index');
        } else {
            request()->session()->flash('error', 'Brand not found');
            return redirect()->back();
        }
    }
}
