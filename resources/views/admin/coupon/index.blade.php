@extends('admin::layouts.panel')

@section('title',__('coupon::coupon.coupons_list'))
@section('body')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a
                                href="{{ route('admin.dashboard') }}">{{ __('admin::admin.dashboard') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('coupon::coupon.coupons_list') }}</li>
                    </ol>
                </div>
                <h4 class="page-title">{{ __('coupon::coupon.coupons_list') }}</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="coupons_table" class="table table-centered w-100 dt-responsive nowrap">
                            <thead class="table-light">
                            <tr>
                                <th>{{ __('coupon::coupon.id') }}</th>
                                <th>{{ __('coupon::coupon.title') }}</th>
                                <th>{{ __('coupon::coupon.price') }}</th>
                                <th>{{ __('coupon::coupon.ship_fee') }}</th>
                                <th>{{ __('coupon::coupon.clicks') }}</th>
                                <th>{{ __('coupon::coupon.code') }}</th>
                                <th>{{ __('coupon::coupon.seller') }}</th>
                                <th>{{ __('coupon::coupon.product') }}</th>
                                <th>{{ __('coupon::coupon.category') }}</th>
                                <th>{{ __('coupon::coupon.country') }}</th>
                                <th>{{ __('coupon::coupon.brand') }}</th>
                                <th>{{ __('coupon::coupon.platform') }}</th>
                                <th>{{ __('coupon::coupon.approved_at') }}</th>
                                <th>{{ __('coupon::coupon.start_at') }}</th>
                                <th>{{ __('coupon::coupon.end_at') }}</th>
                                <th>{{ __('coupon::coupon.created_at') }}</th>
                                <th>{{ __('coupon::coupon.updated_at') }}</th>
                                <th>{{ __('coupon::coupon.action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        $(function () {
            let table = $('#coupons_table').dataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('admin.coupons.index') }}",
                "language": language,
                "pageLength": pageLength,
                "columns": [
                    {'orderable': true},
                    {'orderable': true},
                    {'orderable': true},
                    {'orderable': true},
                    {'orderable': true},
                    {'orderable': true},
                    {'orderable': true},
                    {'orderable': true},
                    {'orderable': true},
                    {'orderable': true},
                    {'orderable': true},
                    {'orderable': true},
                    {'orderable': true},
                    {'orderable': true},
                    {'orderable': true},
                    {'orderable': true},
                    {'orderable': true},
                    {'orderable': false},
                ],
                "order": [[0, "desc"]],
                "drawCallback": function () {
                    $('.dataTables_paginate > .pagination').addClass('pagination-rounded');
                    $('#coupons_table tr td:nth-child(18)').addClass('table-action');
                    delete_listener();
                }
            });
            table.on('childRow.dt', function (e, row) {
                delete_listener();
            });
        });
    </script>
@endsection
