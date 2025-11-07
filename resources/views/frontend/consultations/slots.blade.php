@forelse($slots->chunk(6) as $slotGroup)
    <li>
        <div class="slot-items">
            @foreach($slotGroup as $slot)
                <div class="item">
                    <label for="slot{{ $slot->id }}">{{ \Carbon\Carbon::createFromFormat('H:i', $slot->start_time)->format('g:i a') }}</label>
                    <input type="radio" name="consultation_slot_id"
                           id="slot{{ $slot->id }}" value="{{ $slot->id }}"/>
                </div>
            @endforeach
        </div>
    </li>
@empty
    <li>
        <span>{{__('No slot found')}}</span>
    </li>
@endforelse
