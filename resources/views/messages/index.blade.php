<div class="message-wrapper">
    <ul class="messages">
        @foreach ($messages as $message)
        <li class="message clearfix">
            {{-- 如果是本人訊息在右邊,其他人在左邊--}}
        <div class="{{ ($message->from ==Auth::id()) ? 'sent':'received' }}">
                <p>{{ $message->message }}</p>
                <p class="date">{{  date('Y/m/d h:i:a',strtotime($message->created_at)) }}</p>
            </div>

        </li>
        @endforeach
 
    </ul>
</div>
<div class="input-text">
    <input type="text" name="message" class="submit">

</div>