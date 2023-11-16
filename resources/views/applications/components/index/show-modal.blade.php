<div class="modal fade" tabindex="-1" id="show-modal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title text-uppercase">Chi tiết {{trans('application-manager::vi.'.request()->route('type'))}}</h3>

                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
                <!--end::Close-->
            </div>

            <div class="modal-body">
                <div class="container-fluid fs-3 px-4">
                    <div class="row mb-5">
                        <div class="col-md-6 row">
                            <div class="col-4 fw-bold">Mã đơn từ:</div>
                            <div class="col-8">{{$application->code}}</div>
                        </div>
                        <div class="col-md-6 row">
                            <div class="col-4 fw-bold">Người phê duyệt:</div>
                            <div class="col-8">{{$application->reviewer->name}}</div>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <div class="col-md-6 row">
                            <div class="col-4 fw-bold">Người tạo đơn:</div>
                            <div class="col-8">{{$application->user->name}}</div>
                        </div>
                        <div class="col-md-6 row">
                            <div class="col-4 fw-bold">Phòng ban:</div>
                            <div class="col-8">{{$application->user->roles->first()?->text}}</div>
                        </div>
                    </div>
                    @if($application->type == config('application-manager.application.default'))
                        <div class="row mb-5">
                            <div class="col-md-6 row">
                                <div class="col-4 fw-bold">Tên đề suất:
                                </div>
                                <div class="col-8">{{$application->name}}</div>
                            </div>
                            <div class="col-md-6 row">
                                <div class="col-4 fw-bold">Thông tin tài khoản:
                                </div>
                                <div class="col-8">{{$application->bank_account}}</div>
                            </div>
                        </div>
                        <div class="row mb-5">
                            <div class="col-md-6 row">
                                <div class="col-4 fw-bold">Người đề nghị:
                                </div>
                                <div class="col-8">{{$application->proponent->name}}</div>
                            </div>
                            <div class="col-md-6 row">
                                <div class="col-4 fw-bold">Ngày cần hàng:</div>
                                <div class="col-8">{{carbon($application->delivery_date,'Y-m-d','d-m-Y')}}</div>
                            </div>
                        </div>
                    @endif
                    <div class="row mb-5">
                        <div class="col-md-6 row">
                            <div class="col-4 fw-bold">Trạng thái:
                            </div>
                            <div class="col-8">
                                <label class="{{$application->state->class()}} application-state-label"
                                       @if($application->isPending && auth()->id() == $application->reviewer->id)data-bs-toggle="modal" data-bs-target="#state_modal"
                                       data-id="{{$application->id}}"
                                       data-url="{{route('applications.update.state',$application)}}"@endif
                                >{{$application->state->text()}}</label></div>
                        </div>
                        @if($application->type == config('application-manager.application.default'))
                            <div class="col-md-6 row">
                                <div class="col-4 fw-bold">Số tiền:</div>
                                <div class="col-8">{{number_format($application->money_amount)}}</div>
                            </div>
                        @endif
                    </div>
                    <div class="row mb-5">
                        <div class="col-md-6 row">
                            <div class="col-4 fw-bold">Lý do:
                            </div>
                            <div class="col-8">{{trans('application-manager::vi.'.$application->reason)}}</div>
                        </div>
                        <div class="col-md-6 row">
                            <div class="col-4 fw-bold">Ngày tạo:
                            </div>
                            <div class="col-8">{{carbon($application->created_at,'Y-m-d','d-m-Y')}}</div>
                        </div>
                    </div>
                    @if($application->type != config('application-manager.application.default'))
                        <div class="row mb-5">
                            <div class="col-md-6 row">
                                <div class="col-4 fw-bold">Tổng ngày nghỉ:
                                </div>
                                <div class="col-8">{{$application->number_of_day_off}}</div>
                            </div>
                            <div class="col-md-6 row">
                                <div class="col-4 fw-bold">Mô tả:
                                </div>
                                <div class="col-8 ">{{$application->description}}</div>
                            </div>
                        </div>
                    @endif
                    <div class="row mb-5">
                        <div class="col-md-6 row">
                            <div class="col-4 fw-bold">Loại đơn từ:
                            </div>
                            <div class="col-8">{{trans('application-manager::vi.'.$application->type)}}</div>
                        </div>
                    </div>
                    <div class="separator mb-5"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <span class="text-dark pl-3 d-inline-block py-1 fw-bold">Người theo dõi</span>
                            @if($application->considers->count() > 0)
                                @foreach($application->considers as $consider)
                                    <div class="mt-2">
                                        <p> - {{ $consider->name }}</p>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>