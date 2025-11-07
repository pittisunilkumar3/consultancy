<table class="table zTable zTable-last-item-right" id="serviceOrderDataTable{{ ucfirst($status) }}">
    <thead>
    <tr>
        <th>
            <div class="text-nowrap">{{ __('Order ID') }}</div>
        </th>
        <th>
            <div class="text-nowrap">{{ __('Student') }}</div>
        </th>
        <th>
            <div>{{ __('Service') }}</div>
        </th>
        <th>
            <div>{{ __('Amount') }}</div>
        </th>
        <th>
            <div class="text-nowrap">{{ __('Payment Status') }}</div>
        </th>
        <th>
            <div>{{ __('Status') }}</div>
        </th>
        <th>
            <div class="text-nowrap">{{ __('Created Date') }}</div>
        </th>
        <th>
            <div class="text-nowrap text-center">{{ __('Manage Progress') }}</div>
        </th>
        <th class="{{auth()->user()->role != USER_ROLE_STUDENT ? '' : 'd-none'}}">
            <div>{{ __('Action') }}</div>
        </th>
    </tr>
    </thead>
</table>
