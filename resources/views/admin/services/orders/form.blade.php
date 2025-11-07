@php
    $suffix = isset($serviceOrder) ? '-edit' : '';
@endphp

<div class="row rg-20 mb-20">
    <div class="col-md-6">
        <label for="service_id{{ $suffix }}" class="zForm-label-alt">{{ __('Service') }} <span
                class="text-danger">*</span></label>
        <select class="sf-select-two" name="service_id" id="service_id{{ $suffix }}"
                onchange="updateAmountAndSubtotal('{{ $suffix }}')">
            <option value="">{{ __('Select Service') }}</option>
            @foreach($activeServices as $service)
                <option value="{{ $service->id }}" data-price="{{ $service->price }}"
                    {{ (isset($serviceOrder) && $serviceOrder->service_id == $service->id) ? 'selected' : '' }}>
                    {{ $service->title }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label for="student_id{{$suffix}}" class="zForm-label-alt">{{ __('Student') }} <span
                class="text-danger">*</span></label>
        <select class="sf-select-two" name="student_id" id="student_id{{$suffix}}">
            @foreach($students as $student)
                <option
                    value="{{$student->id}}" {{ (isset($serviceOrder) && $serviceOrder->student_id == $student->id) ? 'selected' : '' }}>{{$student->name}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label for="amount{{ $suffix }}" class="zForm-label-alt">{{ __('Amount') }} <span
                class="text-danger">*</span></label>
        <input type="number" min="0" name="amount" id="amount{{ $suffix }}"
               placeholder="{{ __('Amount') }}" value="{{ $serviceOrder->amount ?? 0 }}"
               class="form-control zForm-control-alt" oninput="calculateSubtotal('{{ $suffix }}')">
    </div>
    <div class="col-md-6">
        <label for="discount{{ $suffix }}" class="zForm-label-alt">{{ __('Discount') }}</label>
        <input type="number" min="0" name="discount" id="discount{{ $suffix }}"
               placeholder="{{ __('Discount') }}" value="{{ $serviceOrder->discount ?? 0 }}"
               class="form-control zForm-control-alt" oninput="calculateSubtotal('{{ $suffix }}')">
    </div>
    <div class="col-md-6">
        <label for="sub_total{{ $suffix }}" class="zForm-label-alt">{{ __('Subtotal') }} <span
                class="text-danger">*</span></label>
        <input type="number" min="0" readonly name="sub_total" id="sub_total{{ $suffix }}"
               placeholder="{{ __('Subtotal') }}" value="{{ $serviceOrder->subtotal ?? 0 }}"
               class="form-control zForm-control-alt">
    </div>
</div>
