<div class="row">
    <div class="col-sm-6">
        <label class="fs-5 form-label mb-2" for="reason">
            <span class="required">Lý do</span>
        </label>
        <select class="form-select @error('reason') is-invalid @enderror"
                data-control="select2"
                data-placeholder="Chọn Lý do"
                data-allow-clear="true"
                name="reason"
                id="reason"
                required>
            <option value="">-- --</option>
            @foreach($leavingApplicationReasons as $reason)
                <option value="{{$reason->name}}" @selected(old('reason') == $reason->name)>{{trans('application-manager::vi.'.$reason->name)}}</option>
            @endforeach
        </select>
        <div class="fv-plugins-message-container invalid-feedback">@error('reason') {{$message}} @enderror</div>
        <!--end::Input group-->
    </div>
    <div class="col-sm-6">
        <!--begin::Input group-->
        <!--begin::Label-->
        <label class="fw-semibold fs-6 mb-2" for="is_paid_leave">Tính công</label>
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
            <option value="1" @selected(old('is_paid_leave' ) == '1')>Có</option>
            <option value="0" @selected(old('is_paid_leave' ) == '0')>Không</option>
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
                    <button type="button" data-repeater-create class="justify-content-center border border-1" style="background-color: #F15A22">
                        <i class="fa-solid fa-plus text-white"></i>
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
                @if(!empty(old('row_repeater')))
                    @foreach(old('row_repeater') as $row)
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
                                        <option value="{{$shift}}" @selected($row['start_shift'] == $shift)>{{trans('application-manager::vi.'.$shift)}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text"
                                       class="form-control mb-3 mb-lg-0 @error('start_date') is-invalid @enderror date-picker"
                                       name="start_date"
                                       placeholder="Thời gian bắt đầu"
                                       autocomplete="off"
                                       value="{{$row['start_date']}}"
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
                                        <option value="{{$shift}}" @selected($row['end_shift'] == $shift)>{{trans('application-manager::vi.'.$shift)}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text"
                                       class="form-control mb-3 mb-lg-0 @error('end_date') is-invalid @enderror edit-ajax date-picker"
                                       name="end_date"
                                       placeholder="Thời gian kết thúc"
                                       autocomplete="off"
                                       value="{{$row['end_date']}}"
                                />
                            </td>
                            <td class="col-1">
                                <div data-repeater-delete class="delete"><i class="fa-solid fa-trash"></i></div>
                            </td>
                        </tr>
                    @endforeach
                @endif
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
                                <option value="{{$shift}}">{{trans('application-manager::vi.'.$shift)}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text"
                               class="form-control mb-3 mb-lg-0 @error('start_date') is-invalid @enderror date-picker"
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
                                <option value="{{$shift}}">{{trans('application-manager::vi.'.$shift)}}</option>
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
        <textarea name="description" id="description" cols="30" rows="10"
                  class="form-control mb-3 mb-lg-0 @error('phone') is-invalid @enderror" required>{{old('description')}}</textarea>
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
        <input type="file" name="attached_files[]" id="attached_files"
               class="form-control mb-3 mb-lg-0 @error('attached_files') is-invalid @enderror" multiple>
        <!--end::Input-->
        <div class="fv-plugins-message-container invalid-feedback">@error('attached_files') {{$message}} @enderror</div>
        <!--end::Input group-->
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <!--begin::Input group-->
        <!--begin::Label-->
        <label class="fw-semibold fs-6 mb-2" for="reviewer_id"><span class="required">Người kiểm duyệt</span></label>
        <!--end::Label-->
        <!--begin::Input-->
        <select class="form-select @error('reviewer_id') is-invalid @enderror"
                data-control="select2"
                data-placeholder="Người kiểm duyệt"
                data-allow-clear="true"
                name="reviewer_id"
                id="reviewer_id"
                required>
            <option value="">-- --</option>
            @foreach($users as $reviewer)
                <option value="{{$reviewer->id}}" @selected(old('reviewer_id') == $reviewer->id)>{{$reviewer->name}}</option>
            @endforeach
        </select>
        <!--end::Input-->
        <div class="fv-plugins-message-container invalid-feedback">@error('reviewer_id') {{$message}} @enderror</div>
        <!--end::Input group-->
    </div>
    <div class="col-sm-6">
        <!--begin::Input group-->
        <!--begin::Label-->
        <label class="fw-semibold fs-6 mb-2" for="consider_id"><span class="required">Người theo dõi</span></label>
        <!--end::Label-->
        <!--begin::Input-->
        <select class="form-select @error('consider_id') is-invalid @enderror"
                data-control="select2"
                data-placeholder="Người theo dõi"
                data-allow-clear="true"
                name="consider_id[]"
                multiple="multiple"
                id="consider_id"
                required>
            <option value="">-- --</option>
            @foreach($users as $consider)
                <option value="{{$consider->id}}"@if(!empty(old('consider_id')))
                    @selected(in_array($consider->id,old('consider_id')))
                        @endif>{{$consider->name}}</option>
            @endforeach
        </select>
        <!--end::Input-->
        <div class="fv-plugins-message-container invalid-feedback">@error('consider_id') {{$message}} @enderror</div>
        <!--end::Input group-->
    </div>
</div>