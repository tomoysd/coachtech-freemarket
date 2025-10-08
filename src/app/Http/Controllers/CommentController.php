<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;
use App\Models\Item;


class CommentController extends Controller
{
    public function store(CommentRequest $request, Item $item)
    {
        $item->comments()->create([
            'user_id' => $request->user()->id,
            'body'    => $request->validated('body'),
        ]);

        return redirect()->route('items.show', $item)->with('message', 'コメントを投稿しました');
    }
}
