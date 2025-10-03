<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * 商品一覧（おすすめ/マイリスト）
     * /?tab=recommend (default) or /?tab=mylist
     */
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'recommend');

        if ($tab === 'mylist') {
            $this->middleware('auth'); // ルートミドルウェアでも守っているが二重保険
            if (!auth()->check()) {
                return redirect()->route('login');
            }
            // お気に入り一覧（中間テーブル favorites がある前提）
            $items = $request->user()
                ->favorites()        // ->belongsToMany(Item::class, 'favorites') などの想定
                ->latest('favorites.created_at')
                ->get();
        } else {
            // おすすめ：とりあえず新着順
            $items = Item::latest()->paginate(12);
        }

        return view('items.index', compact('items', 'tab'));
    }

    /**
     * 商品詳細
     */
    public function show(Item $item)
    {
        $comments = $item->comments()->with('user')->latest()->get();

        // いいね数
        $likesCount = $item->likes()->count();

        // 自分がいいね済みか
        $likedByMe = auth()->check()
            ? $item->likes()->where('user_id', auth()->id())->exists()
            : false;

        // カテゴリ（単一/複数どちらでも拾えるように）
        $categories = method_exists($item, 'categories')
            ? $item->categories
            : collect($item->category ? [$item->category] : []);

        return view('items.show', compact('item', 'comments', 'likesCount', 'likedByMe', 'categories'));
    }

    /**
     * 出品フォーム
     */
    public function create()
    {
        $this->authorize('create', Item::class); // ポリシーが無ければ削除可
        return view('items.create');
    }

    /**
     * 出品登録
     */
    public function store(Request $request)
    {
        $this->authorize('create', Item::class);

        $validated = $request->validate([
            'title'        => ['required', 'string', 'max:100'],
            'description'  => ['nullable', 'string'],
            'price'        => ['required', 'integer', 'min:1'],
            'condition_id' => ['required', 'integer', 'in:1,2,3,4'],
            'image'        => ['nullable', 'image', 'max:4096'],
        ]);

        // 画像保存（任意）
        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('items', 'public');
        }

        $validated['user_id'] = auth()->id();

        $item = Item::create($validated);

        return redirect()->route('items.show', $item)->with('message', '商品を出品しました');
    }
}
