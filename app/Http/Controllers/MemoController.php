<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Memo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;
use Carbon\Carbon;
use Exception;

class MemoController extends Controller
{
    public function index()
    {
        $memo = Memo::select('id', 'content', 'is_new')->get();
        $memoBadgeCount = Memo::where('is_new', true)->count();

        return view('memo.index', compact('memo', 'memoBadgeCount'));
    }

    public function create()
    {
        return view('memo.create');
    }

    public function store(Request $request)
    {
        //dd($request);
        $request->validate([
            'content' => ['required', 'string', 'max:255'],
        ]);

        try {
            DB::transaction(function() use($request) {
                Memo::create([
                    'content' => $request->content,
                    'is_new' => true,
                ]);
            });
        } catch(Throwable $e) {
            Log::error($e);
            throw $e;
        }

        return redirect()
        ->route('memo.index')
        ->with(['message' => '登録しました。',
        'status' => 'info']);
    }

    public function edit($id)
    {
        $memo = Memo::findOrFail($id);

        return view('memo.edit', compact('memo'));
    }

    public function confirm(Request $request, $id)
    {
        //dd($request, $id);
        $request->validate([
            'content' => ['required', 'string', 'max:255'],
        ]);

        $memo = Memo::findOrFail($id);
        $content = $request->content;

        // フラッシュメッセージをセッションに保存
        session()->flash('message', '情報を更新しますか？');
        session()->flash('status', 'confirm');

        return view('memo.confirm', compact('memo', 'content', 'id'));
    }

    public function update(Request $request, $id)
    {
        $memo = Memo::findOrFail($id);
        $memo->content = $request->content;
        $memo->is_new = true;
        $memo->save();

        return redirect()
        ->route('memo.index')
        ->with(['message' => '更新しました。',
        'status' => 'info']);
    }

    public function destroy($id)
    {
        Memo::findOrFail($id)->delete();

        return redirect()
        ->route('memo.index')
        ->with(['message' => '完了にしました。',
        'status' => 'alert']);
    }

    public function expiredMemoIndex()
    {
        $expiredMemo = Memo::onlyTrashed()->get();
        return view('memo.expired-memo', compact('expiredMemo'));
    }

    public function expiredMemoDestroy($id)
    {
       Memo::onlyTrashed()->findOrFail($id)->forceDelete();

       return redirect()->route('expired-memo.index');
    }

    public function expiredMemoRestore($id)
    {
        $memo = Memo::onlyTrashed()->findOrFail($id);
        $memo->restore();

        return redirect()
        ->route('expired-memo.index')
        ->with(['message' => '復元しました。',
        'status' => 'info']);
    }

    public function markBadgeSeen(Request $request)
    {
        // 現在のユーザーに関連するバッジを「確認済み」にする
        Memo::where('is_new', true)->update(['is_new' => false]);
    
        return response()->json(['status' => 'success']);
    }

}

