<div class="row">
    <div class="col-sm-6">
        <label class="fs-5 form-label mb-2" for="reason">
            <span class="required">Lý do</span>
        </label>
        <select class="form-select @error('reason') is-invalid @enderror"
                data-control="select2"
                data-placeholder="Chọn Lý do"
                data-allow-clear="true"
                data-hide-search="true"
                name="reason"
                id="reason">
            <option value="">-- --</option>
            @foreach(config('application-manager.application.type.leave_application') as $reason)
                <option value="{{$reason}}">{{$reason}}</option>
            @endforeach
        </select>
        <div class="fv-plugins-message-container invalid-feedback">@error('reason') {{$message}} @enderror</div>
        <!--end::Input group-->
    </div>
    <div class="col-sm-6">
        <!--begin::Input group-->
        <!--begin::Label-->
        <label class="required fw-semibold fs-6 mb-2" for="is_paid_leave">Tính công</label>
        <!--end::Label-->
        <!--begin::Input-->
        <select class="form-select @error('is_paid_leave') is-invalid @enderror"
                data-control="select2"
                data-placeholder="Tính công"
                data-allow-clear="true"
                data-hide-search="true"
                name="is_paid_leave"
                id="is_paid_leave">
            <option value="">-- --</option>
            <option value="1">Có</option>
            <option value="0">Không</option>
        </select>
        <!--end::Input-->
        <div class="fv-plugins-message-container invalid-feedback">@error('is_paid_leave') {{$message}} @enderror</div>
        <!--end::Input group-->
    </div>
</div>
<div class="row">
    <div class="repeater-table">
        <div class="row mt-5">
            <div class="d-flex">
                <div class="col">
                    <h5>Thời gian nghỉ</h5>
                </div>
                <div class="col-auto d-flex">
                    <button type="button" data-repeater-create class="justify-content-center btn_add">
                        <i class="fa-solid fa-plus text-white fa-3x"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <!--begin::Table-->
            <table class="table align-middle gs-0 gy-4 form-control mb-3 mb-lg-0">
                <thead>
                <tr>
                    <th class="col-2">Ca bắt đầu</th>
                    <th class="col-2">Thời gian bắt đầu</th>
                    <th class="col-2">Ca bắt kết thúc</th>
                    <th class="col-2">Thời gian kết thúc</th>
                </tr>
                </thead>
                <tbody data-repeater-list="row_repeater">
                {{--                                    @foreach(config('application-manager.application.shift') as $shift)--}}
                <tr data-repeater-item>
                    <td>
                        <select class="form-select @error('start_shift') is-invalid @enderror"
                                data-control="select2"
                                data-placeholder="Ca bắt đầu"
                                data-allow-clear="true"
                                data-hide-search="true"
                                name="start_shift">
                            <option value="">-- --</option>
                            @foreach(config('application-manager.application.shift') as $shift)
                                <option value="{{$shift}}">{{$shift}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text"
                               class="form-control mb-3 mb-lg-0 @error('start_date') is-invalid @enderror edit-ajax date-picker"
                               name="start_date"
                               placeholder="Thời gian bắt đầu"
                               autocomplete="off"
                        />
                    </td>
                    <td>
                        <select class="form-select @error('end_shift') is-invalid @enderror"
                                data-control="select2"
                                data-placeholder="Ca kết thúc"
                                data-allow-clear="true"
                                data-hide-search="true"
                                name="end_shift"
                        >
                            <option value="">-- --</option>
                            @foreach(config('application-manager.application.shift') as $shift)
                                <option value="{{$shift}}">{{$shift}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text"
                               class="form-control mb-3 mb-lg-0 @error('end_date') is-invalid @enderror edit-ajax date-picker"
                               name="end_date"
                               placeholder="Thời gian kết thúc"
                               autocomplete="off"
                        />
                    </td>
                    <td class="col-1">
                        <div data-repeater-delete class="delete"><i class="fa-solid fa-trash"></i></div>
                    </td>
                </tr>
                <tr data-repeater-item>
                    <td>
                        <select class="form-select @error('start_shift') is-invalid @enderror"
                                data-control="select2"
                                data-placeholder="Ca bắt đầu"
                                data-allow-clear="true"
                                data-hide-search="true"
                                name="start_shift">
                            <option value="">-- --</option>
                            @foreach(config('application-manager.application.shift') as $shift)
                                <option value="{{$shift}}">{{$shift}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text"
                               class="form-control mb-3 mb-lg-0 @error('start_date') is-invalid @enderror edit-ajax date-picker"
                               name="start_date"
                               placeholder="Thời gian bắt đầu"
                               autocomplete="off"
                        />
                    </td>
                    <td>
                        <select class="form-select @error('end_shift') is-invalid @enderror"
                                data-control="select2"
                                data-placeholder="Ca kết thúc"
                                data-allow-clear="true"
                                data-hide-search="true"
                                name="end_shift">
                            <option value="">-- --</option>
                            @foreach(config('application-manager.application.shift') as $shift)
                                <option value="{{$shift}}">{{$shift}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text"
                               class="form-control mb-3 mb-lg-0 @error('end_date') is-invalid @enderror edit-ajax date-picker"
                               name="end_date"
                               placeholder="Thời gian kết thúc"
                               autocomplete="off"
                        />
                    </td>
                    <td>
                        <div data-repeater-delete class="delete"><i class="fa-solid fa-trash"></i></div>
                    </td>
                    <input type="hidden" class="edit-ajax" name="id" data-column="id">
                </tr>
                </tbody>
                <!--end::Table body-->
            </table>
            <!--end::Table-->
        </div>
    </div>

</div>
<div class="row">
    <div class="col-sm-12">
        <!--begin::Input group-->
        <!--begin::Label-->
        <label class="required fw-semibold fs-6 mb-2" for="description">Mô tả</label>
        <!--end::Label-->
        <!--begin::Input-->
        <textarea name="description" id="description" cols="30" rows="10" class="form-control mb-3 mb-lg-0 @error('phone') is-invalid @enderror" required></textarea>
        <!--end::Input-->
        <div class="fv-plugins-message-container invalid-feedback">@error('description') {{$message}} @enderror</div>
        <!--end::Input group-->
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <!--begin::Input group-->
        <!--begin::Label-->
        <label class="fw-semibold fs-6 mb-2" for="attached_files">Tệp đính kèm</label>
        <!--end::Label-->
        <!--begin::Input-->
        <input type="file" name="attached_files" id="attached_files" class="form-control mb-3 mb-lg-0 @error('attached_files') is-invalid @enderror">
        <!--end::Input-->
        <div class="fv-plugins-message-container invalid-feedback">@error('attached_files') {{$message}} @enderror</div>
        <!--end::Input group-->
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <!--begin::Input group-->
        <!--begin::Label-->
        <label class="fw-semibold fs-6 mb-2" for="reviewer_id">Người kiểm duyệt</label>
        <!--end::Label-->
        <!--begin::Input-->
        <select class="form-select @error('reviewer_id') is-invalid @enderror"
                data-control="select2"
                data-placeholder="Người kiểm duyệt"
                data-allow-clear="true"
                data-hide-search="true"
                name="reviewer_id"
                id="reviewer_id">
            <option value="">-- --</option>
            @foreach($users as $reviewer)
                <option value="{{$reviewer->id}}">{{$reviewer->name}}</option>
            @endforeach
        </select>
        <!--end::Input-->
        <div class="fv-plugins-message-container invalid-feedback">@error('reviewer_id') {{$message}} @enderror</div>
        <!--end::Input group-->
    </div>
</div>