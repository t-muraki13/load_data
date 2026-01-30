<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sales;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Throwable;
use Carbon\Carbon;
use Exception;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $pagination = $request->pagination ?? 20;
        $date = $request->input('date');
        $parseDate = null;
        //dd($request->pagination);

        if ($date) {
            try {
                $parseDate = Carbon::parse($date)->format('Y-m-d');
            } catch (Exception $e) {
                $parseDate = null;
            }
        }
        //dd($date);

        $query = Sales::select('id', 'receiving', 'name', 'nameKana', 'number', 'content', 'charge', 'is_new')
            ->when($parseDate, function($query, $parseDate) {
                $query->whereDate('receiving', $parseDate)
                      ->orderByRaw("CASE WHEN DATE(receiving) = ? THEN 1 ELSE 2 END", [$parseDate])
                      ->orderBy('receiving', 'asc');
            })
            ->sortOrder($request->sort);

        // ページネーション
        $sales = $query->paginate($pagination);
        $salesBadgeCount = Sales::where('is_new', true)->count();

        //dd($sales);
        return view('sales.index', compact('sales', 'salesBadgeCount'));
    }

    public function create()
    {
        return view('sales.create');
    }

    public function store(Request $request)
    {
        //dd($request);
        $request->validate([
            'receiving' => ['required', 'date'],
            'name' => ['required', 'string', 'max:255'],
            'nameKana' => ['required', 'string', 'max:255', 'regex:/^[ァ-ヶー]+$/u'],
            'number' => ['string', 'max:255'],
            'content' => ['required', 'string', 'max:255'],
            'charge' => ['required', 'string', 'max:255'],
        ]);

        try {
            DB::transaction(function() use($request) {
                Sales::create([
                    'receiving' => $request->receiving,
                    'name' => $request->name,
                    'nameKana' => $request->nameKana,
                    'number' => $request->number,
                    'content' => $request->content,
                    'charge' => $request->charge,
                    'is_new' => true,
                ]);
            });
        } catch(Throwable $e) {
            Log::error($e);
            throw $e;
        }

        return redirect()
        ->route('sales.index')
        ->with(['message' => '情報を登録しました。',
        'status' => 'info']);
    }
    
    public function edit($id)
    {
        //dd($id);
        $sales = Sales::findOrFail($id);

        return view('sales.edit', compact('sales'));
    }

    public function confirm(Request $request, $id)
    {
        //dd($request, $id);
        $request->validate([
            'receiving' => ['required', 'date'],
            'name' => ['required', 'string', 'max:255'],
            'nameKana' => ['required', 'string', 'max:255', 'regex:/^[ァ-ヶー]+$/u'],
            'number' => ['string', 'max:255'],
            'content' => ['required', 'string', 'max:255'],
            'charge' => ['required', 'string', 'max:255'],
        ]);

        $sales = Sales::findOrFail($id);
        $receiving = $request->receiving;
        $name = $request->name;
        $nameKana = $request->nameKana;
        $number = $request->number;
        $content = $request->content;
        $charge = $request->charge;
                
        // フラッシュメッセージをセッションに保存
        session()->flash('message', '情報を更新しますか？');
        session()->flash('status', 'confirm');

        return view('sales.confirm', compact('sales', 'receiving', 'name', 'nameKana', 'number', 'content', 'charge', 'id'));
    }

    public function update(Request $request, $id)
    {
        //dd($request, $id);
        $sales = Sales::findOrFail($id);
        $sales->receiving = $request->receiving;
        $sales->name = $request->name;
        $sales->nameKana = $request->nameKana;
        $sales->number = $request->number;
        $sales->content = $request->content;
        $sales->charge = $request->charge;
        $sales->is_new = true;
        $sales->save();

        return redirect()
        ->route('sales.index')
        ->with(['message' => 'データを更新しました。',
        'status' => 'info']);
    }

    public function destroy($id)
    {
        //dd($id);
        Sales::findOrFail($id)->delete();

        return redirect()
        ->route('sales.index')
        ->with(['message' => '情報を完了にしました。',
        'status' => 'alert']);
    }

    public function expiredRouteIndex()
    {
        $expiredRoute = Sales::onlyTrashed()->get();

        return view('sales.expired-route', compact('expiredRoute'));
    }

    public function expiredRouteDestroy($id)
    {
        Sales::onlyTrashed()->findOrFail($id)->forceDelete();

        return redirect()->route('expired-route.index');
    }

    public function expiredRouteRestore($id)
    {
        //ソフトデリートした情報を復元
        $sales = Sales::onlyTrashed()->findOrFail($id);
        $sales->restore();

        return redirect()
        ->route('expired-route.index')
        ->with(['message' => '完了情報を復元しました。',
        'status' => 'alert']);
    }

    public function markBadgeSeen(Request $request)
    {
        // 現在のユーザーに関連するバッジを「確認済み」にする
        Sales::where('is_new', true)->update(['is_new' => false]);
    
        return response()->json(['status' => 'success']);
    }
}
