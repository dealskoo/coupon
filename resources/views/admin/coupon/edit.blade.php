@extends('admin::layouts.panel')

@section('title', __('coupon::coupon.edit_coupon'))
@section('body')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a
                                href="{{ route('admin.dashboard') }}">{{ __('admin::admin.dashboard') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('coupon::coupon.edit_coupon') }}</li>
                    </ol>
                </div>
                <h4 class="page-title">{{ __('coupon::coupon.edit_coupon') }}</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.coupons.update', $coupon) }}" method="post">
                        @csrf
                        @method('PUT')
                        @if (!empty(session('success')))
                            <div class="alert alert-success">
                                <p class="mb-0">{{ session('success') }}</p>
                            </div>
                        @endif
                        @if (!empty($errors->all()))
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <p class="mb-0">{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="title" class="form-label">{{ __('coupon::coupon.title') }}</label>
                                <input type="text" class="form-control" id="title" name="title" readonly
                                    value="{{ old('title', $coupon->title) }}"
                                    placeholder="{{ __('coupon::coupon.title_placeholder') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="product_id" class="form-label">{{ __('coupon::coupon.product') }}</label>
                                <input type="text" class="form-control" id="title" name="title" readonly
                                    value="{{ old('title', $coupon->product->name) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="slug" class="form-label">{{ __('coupon::coupon.slug') }}</label>
                                <input type="text" class="form-control" id="slug" name="slug" required
                                    value="{{ old('title', $coupon->slug) }}" autofocus tabindex="1"
                                    placeholder="{{ __('coupon::coupon.slug_placeholder') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="country_id" class="form-label">{{ __('coupon::coupon.country') }}</label>
                                <input type="text" class="form-control" id="country_id" name="country_id" readonly
                                    value="{{ $coupon->country->name }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="category_id"
                                    class="form-label">{{ __('coupon::coupon.category') }}</label>
                                <input type="text" class="form-control" id="category_id" name="category_id" readonly
                                    value="{{ $coupon->category->name }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="seller_id" class="form-label">{{ __('coupon::coupon.seller') }}</label>
                                <input type="text" class="form-control" id="seller_id" name="seller_id" readonly
                                    value="{{ $coupon->seller->name }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="brand_id" class="form-label">{{ __('coupon::coupon.brand') }}</label>
                                <input type="text" class="form-control" id="brand_id" name="brand_id" readonly
                                    value="{{ $coupon->brand->name }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="platform_id"
                                    class="form-label">{{ __('coupon::coupon.platform') }}</label>
                                <input type="text" class="form-control" id="platform_id" name="platform_id" readonly
                                    value="{{ $coupon->platform->name }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="product_price"
                                    class="form-label">{{ __('coupon::coupon.product_price') }}</label>
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text">{!! request()->country()->currency_symbol !!}</span>
                                    <input type="number" class="form-control" id="product_price" name="product_price"
                                        readonly value="{{ $coupon->product->price }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">{{ __('coupon::coupon.price') }}</label>
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text">{!! request()->country()->currency_symbol !!}</span>
                                    <input type="number" class="form-control" id="price" name="price" readonly
                                        value="{{ old('price', $coupon->price) }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="ship_fee" class="form-label">{{ __('coupon::coupon.ship_fee') }}</label>
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text">{!! request()->country()->currency_symbol !!}</span>
                                    <input type="number" class="form-control" id="ship_fee" name="ship_fee" readonly
                                        value="{{ $coupon->ship_fee }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="code" class="form-label">{{ __('coupon::coupon.code') }}</label>
                                <input type="text" class="form-control" id="code" name="code" readonly
                                    value="{{ $coupon->code }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="activity_date" class="form-label">{{ __('coupon::coupon.start_at') }}
                                    - {{ __('coupon::coupon.end_at') }}</label>
                                <input type="text" class="form-control date" id="activity_date" name="activity_date"
                                    value="{{ $coupon->start_at->format('m/d/Y') . ' - ' . $coupon->end_at->format('m/d/Y') }}"
                                    readonly>
                            </div>
                            <div class="col-md-6 mb-3 mt-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="approved" name="approved"
                                        tabindex="2" value="1" {{ $coupon->approved_at ? 'checked' : '' }}>
                                    <label for="approved"
                                        class="form-check-label">{{ __('coupon::coupon.approved') }}</label>
                                </div>
                            </div>
                        </div> <!-- end row -->
                        <div class="text-end">
                            <button type="submit" class="btn btn-success mt-2" tabindex="5"><i
                                    class="mdi mdi-content-save"></i> {{ __('admin::admin.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
