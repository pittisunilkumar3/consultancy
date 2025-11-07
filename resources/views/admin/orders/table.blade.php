<table class="table zTable zTable-last-item-right" id="orderDataTable{{ ucfirst($status) }}">
    <thead>
    <tr>
        <th><div class="text-nowrap">{{ __('Transaction ID') }}</div></th>
        <th><div class="text-nowrap">{{ __('User Name') }}</div></th>
        <th><div>{{ __('Email') }}</div></th>
        <th><div>{{ $orderType === 'consultation' ? __('Consultant') : ($orderType === 'event' ? __('Event') : __('Course')) }}</div></th>
        <th><div>{{ __('Amount') }}</div></th>
        <th><div>{{ __('Gateway') }}</div></th>
        @if (strtolower($status) === 'pending')
            <th><div class="text-nowrap">{{ __('Payment Info') }}</div></th>
        @endif
        <th><div class="text-nowrap">{{ __('Order Date') }}</div></th>
        <th><div>{{ __('Status') }}</div></th>
        @if (strtolower($status) === 'pending')
            <th><div>{{ __('Action') }}</div></th>
        @endif

    </tr>
    </thead>
</table>
