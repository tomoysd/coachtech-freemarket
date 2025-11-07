<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Http\Requests\ExhibitionRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /**
     * 商品一覧（おすすめ/マイリスト）
     *  /?tab=mylist
     */
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'recommend');

        // 検索語：クエリ優先、無ければセッション
        $keyword = $request->query('q', session('items_search'));
        // 検索語の保持/クリア
        if ($keyword !== null && $keyword !== '') {
            session(['items_search' => $keyword]);
        } else {
            session()->forget('items_search');
        }

        if ($tab === 'mylist') {
            if (!Auth::check()) {
                $items=collect();
            }else{
            // お気に入り一覧（中間テーブル favorites がある前提）
            $items = $request->user()
                ->favorites()        // ->belongsToMany(Item::class, 'favorites') などの想定
                ->latest('favorites.created_at')
                ->where("items.title", "LIKE","%$keyword%")
                ->get();
                }
        } else {
            // おすすめ：とりあえず新着順
            $items = Item::withCount('purchases')
                ->latest()
                ->where("title", "LIKE","%$keyword%")
                ->paginate(12);
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

        $categories = $item->categories;
        $user = Auth::user();
        $item->loadCount('purchases'); // ← これで $item->purchases_count が使える
        return view('items.show', compact('item', 'comments', 'likesCount', 'likedByMe', 'categories', 'user'));
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
