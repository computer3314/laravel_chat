@extends('layouts.app')
@php
  use App\Message;                      
@endphp
@section('content')

<div class="container-fluid">
   <div class="row">
       <div class="col-md-4">
           <div class="user-wrapper">
                <ul class="users">
                    @foreach ($users as $user)
                     
                   @php
                    $newmessage=Message::where('from',$user->id)->where('to',Auth::id())->orwhere('from',Auth::id())->where('to',$user->id)->orderBy('id', 'desc')->value("message");
                  
                   @endphp
                <li class="user" id="{{ $user->id }}">
                  @if($user->unread)
                <span class="pending">{{ $user->unread}}</span>
                    @endif
                  
                  
                      
                        <div class="media">
                            
                            <div class="media-left">
                             <img src="{{ $user->avatar }}" alt="" class="media-object">
                            </div>
                            <div class="media-body">
                                <p class="name">{{ $user->name }}</p>
                                <p class="email">{{ $newmessage }}</p>
                            </div>
                        </div>
                     </li>
                    @endforeach
                   
                    
                </ul>
            </div>
       </div>
       <div class="col-md-8" id="messages">
         
       </div>
   </div>
</div>
@endsection
