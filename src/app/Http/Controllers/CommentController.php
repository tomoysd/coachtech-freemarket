<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;
use App\Models\Item;


class CommentController extends Controller
{
    public function store(CommentRequest $request, $item_id)
    {
        $item_id->comments()->create([
            'user_id' => $request->user()->id,
            'body'    => $request->validated('body'),
        ]);

        return redirect()->route('items.show', $item_id)->with('message', 'コメントを投稿しました');
    }
}
