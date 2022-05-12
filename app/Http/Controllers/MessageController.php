<?php

namespace App\Http\Controllers;

use App\Mail\NewMessage;
use App\Models\Message;
use App\Models\User;
use Carbon\Carbon;
use DateTimeZone;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class MessageController extends Controller
{
     //return Message::ALL with parametrs format JSON

    public function getMessages ()
    {
        return Message::orderBy('created_at', 'ASC')->paginate(5);
    }

    //return Message::ALL with parametrs format JSON

    public function submitMessage (Request $request)
    {
        $user = Auth::user() ? Auth::user() : User::where('name','Гость')->first();

        $request->validate([
            'name' => 'max:255',
            'text' => 'required|max:1000|min:2',
        ]);

        $input = $request->all();

        $author = Auth::user() ? Auth::user()->name : $input['name'];

        $message = new Message();
        $message->user_id = $user->id;
        $message->author = $author;
        $message->message = $input['text'];
        $message->save();

        if($user->email != 'jjnn95555@gmail.com') {
            $carbon = Carbon::now(new DateTimeZone('Europe/Moscow'));
            $data = [
                'user' => $author,
                'message' => $input['text'],
                'date' => $carbon,
            ];
            
            Mail::to('jjnn95555@gmail.com')->send(new NewMessage($data));
            }
        return Message::orderBy('created_at', 'ASC')->paginate(5);  
    }

    //return Message::ALL with parametrs format JSON
    public function deleteMessage ()
    {
        $id = request('id');
        $checkingDate = Message::where('id', $id)->first()->created_at;

        if(Auth::user()->roles->pluck('name')->contains('Админ')){
            Message::find($id)->delete();
        } else {
            if($this->CheckTimesMesssage($checkingDate)) {
                Message::where('user_id', auth::user()->id)->find($id)->delete();
            } else {
                throw new Exception('"время удаления вышло"');
            }
        }
        return Message::orderBy('created_at', 'ASC')->paginate(5);
    }

     //return Message::ALL with parametrs format JSON
    public function changeMessage (Request $request) 
    {
        
        $request->validate([
            'text' => 'required|max:1000|min:2',
        ]);

        $input = $request->all();

        $id = $input['id'];
        $text = $input['text'];
        $checkingDate = Message::where('id', $id)->first()->created_at;

        if($this->CheckTimesMesssage($checkingDate)) {
        Message::where('user_id', auth::user()->id)->where('id', $id)->update([
                'message' => $text 
            ]);
        } else {
            throw new Exception('"время измения вышло"');
        }

        return Message::orderBy('created_at', 'ASC')->paginate(5);
    }
    //return bool function check times
    protected function CheckTimesMesssage($date) : bool 
    {
        $checkingDate = $date;
        $current = Carbon::now();
        $difference = $checkingDate->diffInHours($current);
        if($difference > 2) {
            return false;
        }
        return true;
    }
}