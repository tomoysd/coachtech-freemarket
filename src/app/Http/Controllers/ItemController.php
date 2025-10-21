<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Http\Requests\ExhibitionRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /**
     * 商品一覧（おすすめ/マイリスト）
     * /?tab=recommend (default) or /?tab=mylist
     */
    public function index(ExhibitionRequest $request)
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
        $categories = Category::orderBy('sort_order')->get();
        $conditions = Item::CONDITIONS; // ← これをBladeに渡す

        return view('items.create', compact('categories', 'conditions'));
    }

    /**
     * 出品登録
     */
    public function store(ExhibitionRequest $request)
    {
        $this->authorize('create', Item::class);

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('items', 'public'); // storage/app/public/items/...
        }

        // items へ保存（image_url にパス、condition はそのまま文字）
        $item = Item::create([
            'user_id'     => Auth::id(),
            'title'       => $request->title,
            'brand'       => $request->brand,
            'description' => $request->description,
            'price'       => $request->price,
            'condition'   => $request->condition, // ← 文字列
            'image_url'   => $path,               // ← カラム名に合わせる
        ]);

        // カテゴリ（多対多）を同期
        $item->categories()->sync($request->category_ids);

        return redirect()->route('items.show', $item)->with('success', '商品を出品しました');
    }
}
