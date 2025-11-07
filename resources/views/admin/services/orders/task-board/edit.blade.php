<form class="ajax" data-handler="commonResponseWithPageLoad"
      action="{{route('admin.service_orders.task-board.store', [$order->id, $orderTask->id])}}" method="POST">
    @csrf
    <div class="modal-header">
        <h5 class="fs-18 fw-600 lh-22 text-title-text">{{__('Update Task')}}</h5>
        <button type="button" class="w-32 h-32 d-flex justify-content-center align-items-center bd-one bd-c-stroke rounded-circle bg-transparent fs-20 text-para-text " data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-times"></i></button>
    </div>
    <div class="modal-body task-modalBody-height overflow-y-auto">
        @include('admin.services.orders.task-board.form')
    </div>
    <div class="modal-footer">
        <button type="button" class="sf-btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
        <button type="submit" class="sf-btn-primary">{{__('Save')}}</button>
    </div>
</form>
