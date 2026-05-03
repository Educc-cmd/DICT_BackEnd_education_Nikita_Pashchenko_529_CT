<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuestbookController extends Controller
{
    public function index()
    {
        $comments = DB::table('comments')->get();

        $infoMessage = session('infoMessage', '');

        return view('guestbook', [
            'comments' => $comments,
            'infoMessage' => $infoMessage
        ]);
    }

    public function store(Request $request)
    {
        if ($request->filled(['name', 'email', 'text'])) {

            DB::table('comments')->insert([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'text' => $request->input('text'),
                'date' => date('Y-m-d H:i:s')
            ]);

            return redirect()->route('guestbook.index');
        }

        return redirect()->route('guestbook.index')
            ->with('infoMessage', 'Заполните поля формы');
    }
}
