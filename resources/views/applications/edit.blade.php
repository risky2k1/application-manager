@extends('layouts.main')
@section('content')
    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <div class="card-toolbar">
                <h2>Thông Tin Đơn từ</h2>
            </div>
        </div>
        <!--end::Card header-->
        <div class="card-body">
            <form action="{{route('applications.update',$application)}}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                @php
                    $viewName = "application-manager::applications.components.edit.".$application->category->key;
                    $viewName = str_replace('_','-',$viewName)
                @endphp

                @include($viewName)
                <div class="d-flex justify-content-center mt-5">
                    <button class="btn btn-primary" type="submit">Lưu</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('javascript')
    @include('application-manager::applications.components.edit.javascript')
@endsection