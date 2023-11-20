<div class="row">
    <div class="col-sm-6">
        <label class="fs-5 form-label mb-2" for="reason">
            <span class="required">Loại đề xuất</span>
        </label>
        <select class="form-select @error('reason') is-invalid @enderror"
                data-control="select2"
                data-placeholder="Chọn Lý do"
                data-allow-clear="true"
                data-hide-search="true"
                name="reason"
                id="reason">
            <option value="">-- --</option>
            @foreach(config('application-manager.application.type.request_application') as $reason)
                <option value="{{$reason}}" @selected($application->reason == $reason)>{{trans('application-manager::vi.'.$reason)}}</option>
            @endforeach
        </select>
        <div class="fv-plugins-message-container invalid-feedback">@error('reason') {{$message}} @enderror</div>
        <!--end::Input group-->
    </div>
    <div class="col-sm-6">
        <!--begin::Input group-->
        <!--begin::Label-->
        <label class="required fw-semibold fs-6 mb-2" for="name">Tên đề xuất</label>
        <!--end::Label-->
        <!--begin::Input-->
        <input type="text"
               class="form-control mb-3 mb-lg-0 @error('name') is-invalid @enderror"
               name="name"
               value="{{$application->name}}"
               placeholder="Thời gian bắt đầu"
               autocomplete="off"
               id="name"
        />
        <!--end::Input-->
        <div class="fv-plugins-message-container invalid-feedback">@error('name') {{$message}} @enderror</div>
        <!--end::Input group-->
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <!--begin::Input group-->
        <!--begin::Label-->
        <label class="required fw-semibold fs-6 mb-2" for="user_id">Người đề nghị</label>
        <!--end::Label-->
        <!--begin::Input-->
        <select class="form-select @error('user_id') is-invalid @enderror"
                data-control="select2"
                data-placeholder="Chọn Lý do"
                data-allow-clear="true"
                data-hide-search="true"
                name="proponent_id"
                id="proponent_id">
            <option value="">-- --</option>
            @foreach($users as $proponent)
                <option value="{{$proponent->id}}" @selected($application->user_id == $proponent->id)>{{$proponent->name}}</option>
            @endforeach
        </select>
        <!--end::Input-->
        <div class="fv-plugins-message-container invalid-feedback">@error('user_id') {{$message}} @enderror</div>
        <!--end::Input group-->
    </div>
    <div class="col-sm-6">
        <!--begin::Input group-->
        <!--begin::Label-->
        <label class="fw-semibold fs-6 mb-2" for="money_amount">Số tiền</label>
        <!--end::Label-->
        <!--begin::Input-->
        <input type="text"
               class="form-control mb-3 mb-lg-0 @error('money_amount') is-invalid @enderror"
               name="money_amount"
               value="{{$application->money_amount}}"
               placeholder="Số tiền"
               id="money_amount"
        />
        <!--end::Input-->
        <div class="fv-plugins-message-container invalid-feedback">@error('money_amount') {{$message}} @enderror</div>
        <!--end::Input group-->
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <!--begin::Input group-->
        <!--begin::Label-->
        <label class="fw-semibold fs-6 mb-2" for="bank_account">Thông tin tài khoản</label>
        <!--end::Label-->
        <!--begin::Input-->
        <textarea name="bank_account" id="bank_account" cols="30" rows="10" class="form-control mb-3 mb-lg-0 @error('bank_account') is-invalid @enderror"
                  placeholder="Số tài khoản - Ngân hàng (chi nhánh) - Chủ tài khoản (nếu người nhận tiền khác người đề xuất)">{{$application->bank_account}}</textarea>
        <!--end::Input-->
        <div class="fv-plugins-message-container invalid-feedback">@error('bank_account') {{$message}} @enderror</div>
        <!--end::Input group-->
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <!--begin::Input group-->
        <!--begin::Label-->
        <label class="fw-semibold fs-6 mb-2" for="delivery_time">Thời gian cần hàng</label>
        <!--end::Label-->
        <!--begin::Input-->
        <select class="form-select @error('delivery_time') is-invalid @enderror"
                data-control="select2"
                data-placeholder="Chọn Lý do"
                data-allow-clear="true"
                data-hide-search="true"
                name="delivery_time"
                id="delivery_time">
            <option value="">-- --</option>
            @foreach(config('application-manager.application.shift') as $shift)
                <option value="{{$shift}}" @selected($application->delivery_time == $shift)>{{trans('application-manager::vi.'.$shift)}}</option>
            @endforeach
        </select>
        <!--end::Input-->
        <div class="fv-plugins-message-container invalid-feedback">@error('delivery_time') {{$message}} @enderror</div>
        <!--end::Input group-->
    </div>
    <div class="col-sm-6">
        <!--begin::Input group-->
        <!--begin::Label-->
        <label class="fw-semibold fs-6 mb-2" for="delivery_date">Ngày cần hàng</label>
        <!--end::Label-->
        <!--begin::Input-->
        <input type="text" name="delivery_date" id="delivery_date" value="{{$application->delivery_date}}" class="form-control date-picker mb-3 mb-lg-0 @error('delivery_date') is-invalid @enderror" aria-autocomplete="none">
        <!--end::Input-->
        <div class="fv-plugins-message-container invalid-feedback">@error('delivery_date') {{$message}} @enderror</div>
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
        <input type="file" name="attached_files[]" id="attached_files" class="form-control mb-3 mb-lg-0 @error('attached_files') is-invalid @enderror" multiple>
        <!--end::Input-->
        <div class="fv-plugins-message-container invalid-feedback">@error('attached_files') {{$message}} @enderror</div>
        <!--end::Input group-->
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
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
                <option value="{{$reviewer->id}}" @selected($reviewer->id == $application->reviewer_id)>{{$reviewer->name}}</option>
            @endforeach
        </select>
        <!--end::Input-->
        <div class="fv-plugins-message-container invalid-feedback">@error('reviewer_id') {{$message}} @enderror</div>
        <!--end::Input group-->
    </div>
    <div class="col-sm-6">
        <!--begin::Input group-->
        <!--begin::Label-->
        <label class="fw-semibold fs-6 mb-2" for="consider_id">Người theo dõi</label>
        <!--end::Label-->
        <!--begin::Input-->
        <select class="form-select @error('consider_id') is-invalid @enderror"
                data-control="select2"
                data-placeholder="Người theo dõi"
                data-allow-clear="true"
                data-hide-search="true"
                name="consider_id[]"
                id="consider_id">
            <option value="">-- --</option>
            @foreach($users as $consider)
                <option value="{{$consider->id}}" @selected(in_array($consider->id, $application->considers->pluck('id')->toArray()))>{{$consider->name}}</option>
            @endforeach
        </select>
        <!--end::Input-->
        <div class="fv-plugins-message-container invalid-feedback">@error('consider_id') {{$message}} @enderror</div>
        <!--end::Input group-->
    </div>
</div>