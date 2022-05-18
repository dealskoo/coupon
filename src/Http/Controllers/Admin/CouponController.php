<?php

namespace Dealskoo\Coupon\Http\Controllers\Admin;

use Carbon\Carbon;
use Dealskoo\Admin\Http\Controllers\Controller as AdminController;
use Dealskoo\Admin\Rules\Slug;
use Dealskoo\Coupon\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends AdminController
{
    public function index(Request $request)
    {
        abort_if(!$request->user()->canDo('coupons.index'), 403);
        if ($request->ajax()) {
            return $this->table($request);
        } else {
            return view('coupon::admin.coupon.index');
        }
    }

    private function table(Request $request)
    {
        $start = $request->input('start', 0);
        $limit = $request->input('length', 10);
        $keyword = $request->input('search.value');
        $columns = ['id', 'title', 'price', 'ship_fee', 'clicks', 'code', 'seller_id', 'product_id', 'category_id', 'country_id', 'brand_id', 'platform_id', 'approved_at', 'start_at', 'end_at', 'created_at', 'updated_at'];
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
        $can_view = $request->user()->canDo('coupons.show');
        $can_edit = $request->user()->canDo('coupons.edit');
        foreach ($coupons as $coupon) {
            $row = [];
            $row[] = $coupon->id;
            $row[] = $coupon->title . ' <span class="badge bg-success">' . $coupon->off . '% ' . __('Off') . '</span>';
            $row[] = $coupon->country->currency_symbol . $coupon->price . ' <del>' . $coupon->country->currency_symbol . $coupon->product->price . '</del>';
            $row[] = $coupon->country->currency_symbol . $coupon->ship_fee;
            $row[] = $coupon->clicks;
            $row[] = $coupon->code;
            $row[] = $coupon->seller->name;
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
            $view_link = '';
            if ($can_view) {
                $view_link = '<a href="' . route('admin.coupons.show', $coupon) . '" class="action-icon"><i class="mdi mdi-eye"></i></a>';
            }

            $edit_link = '';
            if ($can_edit) {
                $edit_link = '<a href="' . route('admin.coupons.edit', $coupon) . '" class="action-icon"><i class="mdi mdi-square-edit-outline"></i></a>';
            }
            $row[] = $view_link . $edit_link;
            $rows[] = $row;
        }
        return [
            'draw' => $request->draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $rows
        ];
    }

    public function show(Request $request, $id)
    {
        abort_if(!$request->user()->canDo('coupons.show'), 403);
        $coupon = Coupon::query()->findOrFail($id);
        return view('coupon::admin.coupon.show', ['coupon' => $coupon]);
    }

    public function edit(Request $request, $id)
    {
        abort_if(!$request->user()->canDo('coupons.edit'), 403);
        $coupon = Coupon::query()->findOrFail($id);
        return view('coupon::admin.coupon.edit', ['coupon' => $coupon]);
    }

    public function update(Request $request, $id)
    {
        abort_if(!$request->user()->canDo('coupons.edit'), 403);
        $request->validate([
            'slug' => ['required', new Slug('coupons', 'slug', $id, 'id')]
        ]);
        $coupon = Coupon::query()->findOrFail($id);
        $coupon->fill($request->only([
            'slug'
        ]));
        $coupon->approved_at = $request->boolean('approved', false) ? Carbon::now() : null;
        $coupon->save();
        return back()->with('success', __('admin::admin.update_success'));
    }
}
