<?php

namespace Dealskoo\Coupon\Http\Controllers\Seller;

use Carbon\Carbon;
use Dealskoo\Coupon\Models\Coupon;
use Dealskoo\Product\Models\Product;
use Dealskoo\Seller\Http\Controllers\Controller as SellerController;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class CouponController extends SellerController
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->table($request);
        } else {
            return view('coupon::seller.coupon.index');
        }
    }

    private function table(Request $request)
    {
        $start = $request->input('start', 0);
        $limit = $request->input('length', 10);
        $keyword = $request->input('search.value');
        $columns = ['id', 'title', 'price', 'ship_fee', 'clicks', 'code', 'product_id', 'category_id', 'country_id', 'brand_id', 'platform_id', 'approved_at', 'start_at', 'end_at', 'created_at', 'updated_at'];
        $column = $columns[$request->input('order.0.column', 0)];
        $desc = $request->input('order.0.dir', 'desc');
        $query = Coupon::query();
        if ($keyword) {
            $query->where('title', 'like', '%' . $keyword . '%');
            $query->orWhere('slug', 'like', '%' . $keyword . '%');
        }
        $query->orderBy($column, $desc);
        $count = $query->count();
        $coupons = $query->skip($start)->take($limit)->get();
        $rows = [];
        foreach ($coupons as $coupon) {
            $row = [];
            $row[] = $coupon->id;
            $row[] = $coupon->title . ' <span class="badge bg-success">' . $coupon->off . '% ' . __('Off') . '</span>';
            $row[] = $coupon->country->currency_symbol . $coupon->price . ' <del>' . $coupon->country->currency_symbol . $coupon->product->price . '</del>';
            $row[] = $coupon->country->currency_symbol . $coupon->ship_fee;
            $row[] = $coupon->clicks;
            $row[] = $coupon->code;
            $row[] = $coupon->product->name;
            $row[] = $coupon->category->name;
            $row[] = $coupon->country->name;
            $row[] = $coupon->brand ? $coupon->brand->name : '';
            $row[] = $coupon->platform ? $coupon->platform->name : '';
            $row[] = $coupon->approved_at != null ? Carbon::parse($coupon->approved_at)->format('Y-m-d H:i:s') : null;
            $row[] = $coupon->start_at != null ? Carbon::parse($coupon->start_at)->format('Y-m-d') : null;
            $row[] = $coupon->end_at != null ? Carbon::parse($coupon->end_at)->format('Y-m-d') : null;
            $row[] = Carbon::parse($coupon->created_at)->format('Y-m-d H:i:s');
            $row[] = Carbon::parse($coupon->updated_at)->format('Y-m-d H:i:s');
            $edit_link = '';
            $destroy_link = '';
            if ($coupon->approved_at == null) {
                $edit_link = '<a href="' . route('seller.coupons.edit', $coupon) . '" class="action-icon"><i class="mdi mdi-square-edit-outline"></i></a>';
                $destroy_link = '<a href="javascript:void(0);" class="action-icon delete-btn" data-table="coupons_table" data-url="' . route('seller.coupons.destroy', $coupon) . '"> <i class="mdi mdi-delete"></i></a>';
            }
            $row[] = $edit_link . $destroy_link;
            $rows[] = $row;
        }
        return [
            'draw' => $request->draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $rows
        ];
    }

    public function create(Request $request)
    {
        $products = Product::approved()->where('seller_id', $request->user()->id)->get();
        return view('coupon::seller.coupon.create', ['products' => $products]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string'],
            'code' => ['required', 'string'],
            'product_id' => ['required', 'exists:products,id'],
            'price' => ['required', 'numeric'],
            'ship_fee' => ['required', 'numeric'],
            'activity_date' => ['required', 'string']
        ]);
        $between = explode(' - ', $request->input('activity_date'));
        $start = date('Y-m-d', strtotime($between[0]));
        $end = date('Y-m-d', strtotime($between[1]));
        $product = Product::approved()->where('seller_id', $request->user()->id)->findOrFail($request->input('product_id'));
        $coupon = new Coupon(Arr::collapse([$request->only([
            'title', 'code', 'product_id', 'price', 'ship_fee'
        ]), $product->only([
            'seller_id', 'category_id', 'country_id', 'brand_id', 'platform_id'
        ]), ['start_at' => $start, 'end_at' => $end]]));
        $coupon->save();
        return redirect(route('seller.coupons.index'));
    }

    public function edit(Request $request, $id)
    {
        $coupon = Coupon::where('seller_id', $request->user()->id)->findOrFail($id);
        $products = Product::approved()->where('seller_id', $request->user()->id)->get();
        return view('coupon::seller.coupon.edit', ['coupon' => $coupon, 'products' => $products]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => ['required', 'string'],
            'code' => ['required', 'string'],
            'product_id' => ['required', 'exists:products,id'],
            'price' => ['required', 'numeric'],
            'ship_fee' => ['required', 'numeric'],
            'activity_date' => ['required', 'string']
        ]);
        $between = explode(' - ', $request->input('activity_date'));
        $start = date('Y-m-d', strtotime($between[0]));
        $end = date('Y-m-d', strtotime($between[1]));
        $product = Product::approved()->where('seller_id', $request->user()->id)->findOrFail($request->input('product_id'));
        $coupon = Coupon::where('seller_id', $request->user()->id)->findOrFail($id);
        $coupon->fill(Arr::collapse([$request->only([
            'title', 'code', 'product_id', 'price', 'ship_fee'
        ]), $product->only([
            'seller_id', 'category_id', 'country_id', 'brand_id', 'platform_id'
        ]), ['start_at' => $start, 'end_at' => $end]]));
        $coupon->save();
        return redirect(route('seller.coupons.index'));
    }

    public function destroy(Request $request, $id)
    {
        return ['status' => Coupon::where('seller_id', $request->user()->id)->where('approved_at', null)->where('id', $id)->delete()];
    }
}
