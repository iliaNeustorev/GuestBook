<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    //return check on Admin

    protected function CheckIsAdmin () : bool {
        $CheckIsAdmin = false;
        $roles = Auth::user()->roles;
        if($roles !== null){
            $CheckIsAdmin = Auth::user()->roles->pluck('name')->contains('Админ') ? true : false;
        }
         return $CheckIsAdmin;
    }

    // return query with paginate and sortby

    protected function showUserMessages ()
    {
        $length = request('length');
        $query = Message::query();
        $filters = request('filters');
        $sortColumn = request('sortColumn');
        foreach ($filters as $column => $filter) {
            if($filter['value']) {
                $value = $filter['type'] == 'like' ? "%{$filter['value']}%" : $filter['value'];
                $query->where($column, $filter['type'], $value);
            }
        }
        return $query->orderBy($sortColumn['column'], $sortColumn['direction'])->paginate($length);
    }

    /*
    delete message from admin interface
    return Message all records
    */

    protected function deleteMessage ()
    {
        $id = request('id');
        Message::find($id)->delete();
        return Message::get();
    }

    /*
    change Message from admin interface,
    return Message all records
    */

    protected function changeMessageUser (Request $request) 
    {
        $request->validate([
            'text' => 'required|max:1000|min:2',
        ]);

        $input = $request->all();

        $id = $input['id'];
        $text = $input['text'];

       Message::where('id', $id)->update([
            'message' => $text 
        ]);

        return Message::get();
    }

    /*
    export Model Message with all records in csv file,
    return $url for downloadlink
    */
    protected function exportMessages ()
    {
        $messages = Message::get()->toArray();
        Storage::delete('public/exportMessages.csv');
        $columns = [
           'id',
           'author',
           'message',
           'created_at',
           'updated_at' 
        ];
            Storage::append('public/exportMessages.csv',implode(';',$columns));
        foreach ($messages as $message) {
            $category['author']  = iconv('utf-8', 'windows-1251//IGNORE', $message['author']);
            $category['message']  = iconv('utf-8', 'windows-1251//IGNORE',$message['message']);
            Storage::append('public/exportMessages.csv',implode(';',$message));
        }
            $url = Storage::url('exportMessages.csv');
            return $url;

    }
}
