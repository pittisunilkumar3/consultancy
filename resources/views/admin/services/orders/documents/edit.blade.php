<div class="modal-body p-sm-25 p-15">
    <!-- Header -->
    <div
        class="d-flex justify-content-between align-items-center g-10 pb-20 mb-17 bd-b-one bd-c-stroke">
        <h4 class="fs-18 fw-600 lh-22 text-title-black">{{__('Update File')}}</h4>
        <button type="button"
                class="bd-one bd-c-stroke rounded-circle w-24 h-24 bg-transparent text-para-text fs-13"
                data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-times"></i></button>
    </div>
    <!-- Body -->
    <form method="POST" id="fileSaveForm" class="ajax reset"
          action="{{route('admin.service_orders.save-file', $file->student_service_order_id)}}"
          data-handler="commonResponseWithPageLoad">
        @csrf
        <input type="hidden" name="file" id="file-id" value="{{$file->file}}">
        <div class="pb-25">
            <label for="file_name" class="zForm-label-alt">{{__("File Name")}}</label>
            <input name="file_name" type="text" value="{{$file->name}}" class="form-control zForm-control-alt" id="file_name"
                   placeholder="{{ __('File Name') }}"/>
        </div>
        <!-- Button -->
        <div class="d-flex g-12">
            <button type="submit" class="sf-btn-primary">{{__("Save File")}}</button>
        </div>
    </form>
</div>
