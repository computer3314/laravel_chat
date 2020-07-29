<?php

namespace App\Http\Controllers;

use App\Message;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Pusher\Pusher;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //找所有使用者LOG
        $user = DB::select("select users.id, users.name, users.avatar, users.email, count(is_read) as unread 
        from users LEFT  JOIN  messages ON users.id = messages.from and is_read = 0 and messages.to = " . Auth::id() . "
        where users.id != " . Auth::id() . " 
        group by users.id, users.name, users.avatar, users.email");
        return view('home',['users'=>$user]);
    }
    public function getMessage($user_id)
    {
        $my_id=Auth::id();
        //典擊後更新未讀
        Message::where(['from'=> $user_id,'to'=> $my_id])->update(['is_read'=>1]);
        //找這個使用者的所有訊息
        //找這個使用者跟其他使用者的歷史訊息
     
        $messages=Message::where(function($query) use($user_id,$my_id)
        {
            $query->where('from',$my_id)->where('to',$user_id);
        })->orWhere(function ($query) use($user_id,$my_id){
            $query->where('from',$user_id)->where('to',$my_id);
        })
        ->get();
  
        return view('messages.index')->with('messages', $messages);
    }
    public function sendMessage(Request $request)
    {
        $from=Auth::id();
        $to=$request->receiver_id;
        $message=$request->message;
        $data=new Message();
        $data->from=$from;
        $data->to=$to;
        $data->message=$message;
        $data->is_read=0;
        $data->save();
         // pusher

        $pusher = new Pusher(
            "fa8b6b99a7a31fb96b14",
            "71ded8cca21485dd0e20",
            "1046754",
            array(
                'cluster' => "ap1",
                'useTLS' => true,
                'curl_options' => array( CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4 )
            )
        );

        $data = ['from' => $from, 'to' => $to]; // sending from and to user id when pressed enter
        $pusher->trigger('my-channel', 'my-event', $data);
    }
}
