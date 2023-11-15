@extends('layouts.main')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <h2>Quản lí đơn từ</h2>
            </div>
        </div>
    </div>
    <div class="card card-flush bgi-position-y-center bgi-no-repeat mt-5 mb-10">
        <!--begin::Card body-->
        <div class="card-body">
            <!--begin::Navs-->
            <div class="d-flex overflow-auto">
                <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-semibold flex-nowrap">
                    @foreach(config('application-manager.application.type') as $type=>$value)
                        <!--begin::Nav item-->
                        <li class="nav-item">
                            <a class="nav-link text-active-primary me-6
                            @if(request()->route('type')==$type) active @elseif(!request()->route('type') && $type==config('application-manager.application.default')) active @endif"
                               href="{{route('applications.index',['type'=>$type])}}">
                                @switch($type)
                                    @case(config('application-manager.application.default'))
                                        Đơn đề nghị
                                        @break
                                    @default
                                        Đơn xin nghỉ
                                @endswitch
                            </a>
                        </li>
                        <!--end::Nav item-->
                    @endforeach
                </ul>
            </div>
            <!--begin::Navs-->
        </div>
        <!--end::Card body-->
    </div>

    <div class="card mt-5">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <form class="card-title" action="" method="get" id="form_filter">
                <div class="row w-700px">
                    <div class="col-md-4"> <!-- First column for select -->
                        <div class="input-group">
                            <select
                                    class="form-select application-state-filter"
                                    id="state"
                                    name="state"
                                    data-tags="false"
                                    data-control="select2"
                                    data-placeholder="Trạng thái"
                            >
                                <option value="null">Tất cả</option>
                                <option value="pending" @selected(request()->input('state') == 'pending')>Chờ duyệt</option>
                                <option value="approved" @selected(request()->input('state') == 'approved')>Đã duyệt</option>
                                <option value="declined" @selected(request()->input('state') == 'declined')>Không duyệt</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-8"> <!-- Second column for input and button -->
                        <div class="input-group d-flex justify-content-end">
                            <input type="text" name="keyword" placeholder="Nhập từ khóa" class="form-control "
                                   value="{{request()->get('keyword')}}">
                            <button class="btn btn-sm btn-success" type="submit">Tìm kiếm</button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="card-toolbar">
                <!--begin::Toolbar-->
                <a href="{{route('applications.export',['type'=>request()->route('type')])}}" class="btn btn-sm btn-light btn-active-light-primary me-5">
                    Xuất<i class="ms-2 fa-solid fa-file-export"></i>
                </a>

                <a href="{{route('applications.create',['type'=>request()->route('type')])}}">
                    <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                        <!--begin::Add customer-->
                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal">
                            Thêm
                            <i class="fa-solid fa-plus text-white"></i>
                        </button>
                        <!--end::Add customer-->
                    </div>
                </a>
                <!--end::Toolbar-->
            </div>
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        @if($applications->count() > 0)
            <div class="card-body pt-0">
                <!--begin::Table-->
                <div id="kt_customers_table_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer" id="kt_customers_table">
                            <!--begin::Table head-->
                            <thead>
                            <!--begin::Table row-->
                            <tr class="text-center fw-bold fs-7 text-uppercase gs-0">
                                <th class="text-start">STT</th>
                                <th>Người tạo</th>
                                <th>Mã đơn</th>
                                <th>Trạng thái</th>
                                <th>Lý do</th>
                                <th>Phòng ban</th>
                                <th>Tệp đính kèm</th>
                                <th>Số ngày</th>
                                <th>Ngày tạo</th>
                                <th class="text-end sorting_disabled" rowspan="1" colspan="1" aria-label="Actions">Hành động</th>
                            </tr>
                            <!--end::Table row-->
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody class="text-center fw-semibold text-gray-600">
                            @foreach($applications as $application)
                                <tr class="odd text-gray-800">
                                    <td>
                                        <a href="#">{{$loop->increment}}</a>
                                    </td>
                                    <td>
                                        <a href="{{route('users.show',$application->user)}}" class="text-gray-800 text-hover-primary mb-1">{{$application->user->name}}</a>
                                    </td>
                                    <td>{{$application->code}}</td>
                                    <td>
                                        <label class="{{$application->state->class()}} application-state-label"
                                               @if($application->isPending)data-bs-toggle="modal" data-bs-target="#state_modal" data-id="{{$application->id}}"
                                               data-url="{{route('applications.update.state',$application)}}"@endif
                                        >{{$application->state->text()}}</label></td>
                                    <td>{{trans('application-manager::vi.'.$application->reason)}}</td>
                                    <td>{{$application->user->roles->first()?->text}}</td>
                                    <td>
                                        <a href="{{asset('storage/'.$application->attached_files)}}"><i class="fa-solid fa-file"></i></a>
                                    </td>
                                    <td>{{$application->number_of_day_off}}</td>
                                    <td>{{carbon($application->created_at,'Y-m-d','d-m-Y')}}</td>
                                    <td class="d-flex align-items-center justify-content-end">
                                        <a href="{{route('applications.edit',$application)}}" class="col-auto me-5" data-bs-toggle="tooltip" data-bs-placement="bottom" aria-label="Sửa"
                                           data-bs-original-title="Sửa" data-kt-initialized="1"><i class="fa-solid fa-pen p-2"></i></a>
                                        <button type="button" class="border border-white delete_button" data-delete="Đơn từ">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <!--end::Table body-->
                        </table>
                        {{$applications->links()}}
                    </div>
                </div>
                <!--end::Table-->
            </div>
        @else
            @if(check_url_parameters(request()))
                <h3 class="mt-5 text-center text-danger">Không tìm thấy Đơn từ nào</h3>
            @else
                <h3 class="mt-5 text-center text-danger">Chưa có Đơn từ nào.</h3>
            @endif
        @endif
        <!--end::Card body-->
    </div>
    <div class="modal fade" tabindex="-1" id="state_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Duyệt đơn từ</h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body">
                    <p>Duyệt đơn từ đã chọn?</p>
                </div>

                <div class="modal-footer">
                    <form method="post" action="#" class="update-state-form">
                        @csrf
                        @method('PATCH')
                        <input type="text" name="state" hidden class="input-application-state">
                        <input type="text" name="application_id" hidden class="input-application-id">
                        <button type="button" class="btn btn-sm btn-danger" onclick="updateApplicationState('declined')">Không duyệt</button>
                        <button type="button" class="btn btn-sm btn-primary" onclick="updateApplicationState('approved')">Duyệt</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('javascript')
    @include('pages.applications.components.index.javascript')
@endsection