@php
    $suffix = isset($serviceOrderInvoice) ? '-edit' : '';
@endphp

<div class="row rg-20 mb-20">
    <div class="col-md-12">
        <label for="student_id{{$suffix}}" class="zForm-label-alt">{{ __('Student') }} <span
                class="text-danger">*</span></label>
        <select onchange="updateServiceOrder(this, '{{ $suffix }}')" class="sf-select-checkbox student-id" name="student_id" id="student_id{{$suffix}}">
            <option class="d-none" disabled @if(!isset($serviceOrderInvoice)) selected @endif>{{__('Select Student')}}</option>
            @foreach($students as $student)
                <option value="{{$student->id}}" {{ (isset($serviceOrderInvoice) && $serviceOrderInvoice->student_id == $student->id) ? 'selected' : '' }}>{{$student->name}}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-12">
        <label for="service_order_id{{ $suffix }}" class="zForm-label-alt">{{ __('Service Order') }} <span class="text-danger">*</span></label>
        <select class="sf-select-checkbox service-order" name="service_order_id" id="service_order_id{{ $suffix }}">
            <option class="d-none" disabled>{{ __('Select Service Order') }}</option>
            @foreach($serviceOrders as $order)
                <option value="{{ $order->id }}" {{ (optional($serviceOrderInvoice->service_order)->id == $order->id) ? 'selected' : '' }}>
                    {{ $order->orderID }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-12">
        <label for="details{{ $suffix }}" class="zForm-label-alt">{{ __('Details') }} <span
                class="text-danger">*</span></label>
        <textarea id="details{{ $suffix }}" name="details" class="min-h-0 form-control zForm-control-alt">{!! $serviceOrderInvoice->details ?? '' !!}</textarea>
    </div>
    <div class="col-md-12">
        <label for="due_date{{ $suffix }}" class="zForm-label-alt">{{ __('Due Date') }}</label>
        <input type="datetime-local" name="due_date" id="due_date{{ $suffix }}"
               placeholder="{{ __('Date') }}" value="{{ $serviceOrderInvoice->due_date ?? '' }}"
               class="form-control zForm-control-alt date-time-disable-previous">
    </div>
    <div class="col-md-12">
        <label for="amount{{ $suffix }}" class="zForm-label-alt">{{ __('Amount') }} <span
                class="text-danger">*</span></label>
        <input type="number" min="1" name="amount" id="amount{{ $suffix }}"
               placeholder="{{ __('Amount') }}" value="{{ $serviceOrderInvoice->total ?? 0 }}"
               class="form-control zForm-control-alt">
    </div>
</div>
