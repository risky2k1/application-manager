@extends('layouts.main')
@section('content')
    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <div class="card-toolbar">
                <h2 class="text-uppercase">Thông Tin {{trans('application-manager::vi.'.request()->route('type'))}}</h2>
            </div>
        </div>
        <!--end::Card header-->
        <div class="card-body">
            <form action="{{route('applications.store',['type'=>request()->route('type')])}}" method="post" enctype="multipart/form-data">
                @csrf
                @if(request()->route('type') == config('application-manager.application.default') )
                    @include('application-manager::applications.components.create.request-application')
                @else
                    @include('application-manager::applications.components.create.leaving-application')
                @endif
                <div class="d-flex justify-content-center mt-5">
                    <button class="btn btn-primary" type="submit">Gửi</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('javascript')
    @include('application-manager::applications.components.create.javascript')
@endsection