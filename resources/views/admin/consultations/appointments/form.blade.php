<div class="row rg-30">
    <div class="col-md-6">
        <label for="consultant" class="zForm-label-alt">{{ __('Consultant') }}</label>
        <select class="sf-select-two" id="consultant" name="consultant">
            @foreach($consultants as $consultant)
                <option value="{{$consultant->id}}"
                        data-fee="{{$consultant->fee}}"
                    {{ isset($appointment) && $appointment->consulter_id == $consultant->id ? 'selected' : '' }}>
                    {{ $consultant->name }} - ({{ showPrice($consultant->fee) }})
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label for="student" class="zForm-label-alt">{{ __('Student') }}</label>
        <select class="sf-select-two" id="student" name="student">
            @foreach($students as $student)
                <option value="{{$student->id}}"
                    {{ isset($appointment) && $appointment->user_id == $student->id ? 'selected' : '' }}>
                    {{ $student->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-lg-6">
        <label for="inputDateTime" class="zForm-label-alt">{{__('Date')}} <span>*</span></label>
        <input type="date" name="date" required
               class="form-control zForm-control {{isset($appointment) ? '' : 'date-disable-previous'}}"
               id="inputDateTime" placeholder="{{__('Enter Date')}}"
               value="{{ isset($appointment) ? $appointment->date : '' }}"/>
    </div>
    <div class="col-lg-6">
        <label for="inputSelectTimeSlot" class="zForm-label-alt">{{ __('Select Time Slot') }} <span>*</span></label>
        <div class="dropdown slotDropdown">
            <button class="dropdown-toggle d-flex align-items-center cg-8 dropdownToggle-slot"
                    id="dropdown-text" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                    aria-expanded="false">
                {{ isset($appointment) && $appointment->slot ? $appointment->slot->start_time : '-- Select a Time Slot --' }}
            </button>
            <input type="hidden" name="consultation_slot" id="consultation_slot"
                   value="{{ isset($appointment) ? $appointment->consultation_slot_id : '' }}">
            <input type="hidden" id="consultation_slot_id_edit" name="consultation_slot_id"
                   value="{{ isset($appointment) ? $appointment->consultation_slot_id : '' }}">

            <ul class="dropdown-menu" id="slot-block">
                <li>
                    <span>{{__('No slot found')}}</span>
                </li>
            </ul>
            <span id="slot-error" class="text-danger"></span>
        </div>
    </div>
    <div class="col-md-6 payment-block">
        <label for="gateway" class="zForm-label-alt">{{ __('Gateway') }}</label>
        <select class="sf-select-two" id="gateway" name="gateway">
            @foreach($gateways as $gateway)
                <option value="{{$gateway->slug}}"
                    {{ isset($appointment) && $appointment->payment->gateway->slug == $gateway->slug ? 'selected' : '' }}>
                    {{ $gateway->title }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6 payment-block">
        <label for="gateway_currency" class="zForm-label-alt">{{ __('Payment Currency') }}</label>
        <select class="sf-select-two" id="gateway_currency" name="gateway_currency">
            <option value="{{ isset($appointment) ? $appointment->payment->payment_currency : '' }}">
                {{ isset($appointment) ? $appointment->payment->payment_currency : '-- Select Currency --' }}
            </option>
        </select>
    </div>
    <div class="col-lg-12">
        <label for="status" class="zForm-label-alt">{{__('Narration')}}</label>
        <textarea name="narration"
                  class="form-control zForm-control-alt">{!! $appointment->narration ?? '' !!}</textarea>
    </div>
    <div class="col-lg-6">
        <label for="" class="zForm-label-alt">{{__('Consultation Type')}}</label>
        <div class="d-flex align-items-center g-13">
            <div class="zForm-wrap-radio">
                <input required type="radio" value="{{CONSULTATION_TYPE_PHYSICAL}}"
                       class="form-check-input" id="inputRadioOffline" name="consultation_type"
                    {{ isset($appointment) && $appointment->consultation_type == CONSULTATION_TYPE_PHYSICAL ? 'checked' : '' }}/>
                <label for="inputRadioOffline">{{__('In Person')}}</label>
            </div>
            <div class="zForm-wrap-radio">
                <input required type="radio" value="{{CONSULTATION_TYPE_VIRTUAL}}"
                       class="form-check-input" id="inputRadioOnline" name="consultation_type"
                    {{ isset($appointment) && $appointment->consultation_type == CONSULTATION_TYPE_VIRTUAL ? 'checked' : '' }}/>
                <label for="inputRadioOnline">{{__('Virtual')}}</label>
            </div>
        </div>
    </div>
</div>
