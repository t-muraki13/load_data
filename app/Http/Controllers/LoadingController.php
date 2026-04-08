<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loading;
use App\Constants\Common;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;
use Carbon\Carbon;
use Illuminate\Contracts\Session\Session as SessionContract;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class LoadingController extends Controller
{
    public function index(Request $request)
    {
        $pagination = $request->pagination ?? 20;

        // 日付検索パラメータ
        $date = $request->input('date');
        //検索クエリパラメーター
        $query = $request->input('query');
        $parseDate = null;

        if ($date) {
            try {
                // データベースの日付形式に合わせる
                $parseDate = Carbon::parse($date)->format('Y-m-d');
            } catch (\Exception $e) {
                $parseDate = null;
            }
        }

        // 入庫日の時間順に並べる
        $receivingQuery = Loading::select('id', 'receiving', 'name', 'nameKana', 'number', 'content', 'charge', 'issue', 'remarks', 'place', 'is_new')
            ->when($parseDate, function ($query, $parseDate) {
                $query->whereDate('receiving', $parseDate)
                      ->orderBy('receiving', 'asc');
            })
            ->when($query, function ($query, $queryValue) {
                $query->where('name', 'like', "%{$queryValue}%")
                      ->orwhere('nameKana', 'like', "%{$queryValue}%")
                      ->orwhere('number', 'like', "%{$queryValue}%");
            });
        // 出庫日の時間順に並べる
        $issueQuery = Loading::select('id', 'receiving', 'name', 'nameKana', 'number', 'content', 'charge', 'issue', 'remarks', 'place', 'is_new')
            ->when($parseDate, function ($query, $parseDate) {
                $query->whereDate('issue', $parseDate)
                      ->orderBy('issue', 'asc');
            })
            ->when($query, function ($query, $queryValue) {
                $query->where('name', 'like', "%{$queryValue}%")
                      ->orwhere('nameKana', 'like', "%{$queryValue}%")
                      ->orwhere('number', 'like', "%{$queryValue}%");
            });
        
        $receivingLoadings = $receivingQuery->get();
        $issueLoadings = $issueQuery->get();

        $sort = $request->get('sort');
        $mergedLoadings = $receivingLoadings->merge($issueLoadings);

        //ソート処理
        if ($sort === Common::SORT_ORDER['receiving']) {
            $mergedLoadings = $mergedLoadings->sortBy('receiving')->values();
        } elseif ($sort === Common::SORT_ORDER['name']) {
            $mergedLoadings = $mergedLoadings->sortBy('name')->values();
        } elseif ($sort === Common::SORT_ORDER['charge']) {
            $mergedLoadings = $mergedLoadings->sortBy('charge')->values();
        } elseif ($sort === Common::SORT_ORDER['issue']) {
            $mergedLoadings = $mergedLoadings->sortBy('issue')->values();
        } elseif ($sort === Common::SORT_ORDER['place']) {
            $mergedLoadings = $mergedLoadings->sortBy('place')->values();
        }
        
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = $pagination;
        $currentResults = $mergedLoadings->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $paginatedLoadings = new LengthAwarePaginator($currentResults, $mergedLoadings->count(), $perPage, $currentPage, [
         'path' => LengthAwarePaginator::resolveCurrentPath(),
         'query' => $request->query(),
        ]);

        //新しく登録された件数のレコード数をカウント
        $badgeCount = Loading::where('is_new', true)->count();
        
        return view('top', [
            'loading' => $paginatedLoadings,
            'badgeCount' => $badgeCount,
        ]);
    }

    public function create()
    {
        return view('create');
    }

    public function store(Request $request)
    {
        if (!session()->has('session_started')) {
            session()->put('session_started', true);
        }

        $userId = auth()->check() ? auth()->id() : session()->getId();

        $lockKey = 'processing_' . $userId;

        $lock = Cache::lock($lockKey, 10);


        if (!$lock->get()) {
            return back()->with([
                'message' => '少々お待ちください。',
                'status' => 'info'])
            ->withInput();
        }

        $request->validate([
            'receiving' => ['required', 'date'],
            'name' => ['required', 'string', 'max:255'],
            'nameKana' => ['required', 'string', 'max:255', 'regex:/^[ァ-ヶー]+$/u'],
            'number' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:255'],
            'charge' => ['required', 'string', 'max:255'],
            'issue' => ['required', 'date', 'after:receiving'],
            'remarks' => ['nullable', 'string', 'max:255'],
            'place' =>['required', 'string', 'max:255'],
        ]);

        try {
            DB::transaction(function() use($request) {
                Loading::create([
                    'receiving' => $request->receiving,
                    'name' => $request->name,
                    'nameKana' => $request->nameKana,
                    'number' => $request->number,
                    'content' => $request->content,
                    'charge' => $request->charge,
                    'issue' => $request->issue,
                    'remarks' => $request->remarks ?? '',
                    'place' => $request->place,
                    'is_new' => true,
                ]);
            });

            return redirect()
                ->route('index')
                ->with(['message' => '情報を登録しました。',
                'status' => 'info']);

        } catch(Throwable $e) {
            Log::error('エラー発生: ' . $e);
            return back()->withErrors('エラーが発生しました')->withInput();
        } finally {
            $lock->release();
        }
    }

    public function edit($id)
    {
        $loading = Loading::findOrFail($id);

        return view('edit', compact('loading'));
    }

    public function confirm(Request $request, $id)
    {
        $request->validate([
            'receiving' => ['required', 'date'],
            'name' => ['required', 'string', 'max:255'],
            'nameKana' => ['required', 'string', 'max:255', 'regex:/^[ァ-ヶー]+$/u'],
            'number' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:255'],
            'charge' => ['required', 'string', 'max:255'],
            'issue' => ['required', 'date', 'after:receiving'],
            'remarks' => ['nullable', 'string', 'max:255'],
            'place' =>['required', 'string', 'max:255'],
        ]);

        $model = [
            'loading' => Loading::findOrFail($id),
            'receiving' => $request->receiving,
            'name' => $request->name,
            'nameKana' => $request->nameKana,
            'number' => $request->number,
            'content' => $request->content,
            'charge' => $request->charge,
            'issue' => $request->issue,
            'remarks' => $request->remarks ?? '',
            'place' => $request->place
        ];
        
        session()->flash('message', '情報を更新しますか？');
        session()->flash('status', 'confirm');

        return view('confirm', compact('model', 'id'));

    }

    public function update(Request $request, $id)
    {
        $loading = Loading::findOrFail($id);
        $loading->receiving = $request->receiving;
        $loading->name = $request->name;
        $loading->nameKana = $request->nameKana;
        $loading->number = $request->number;
        $loading->content = $request->content;
        $loading->charge = $request->charge;
        $loading->issue = $request->issue;
        $loading->remarks = $request->remarks ?? '';
        $loading->place = $request->place;
        $loading->is_new = true;
        $loading->save();

        return redirect()
        ->route('index')
        ->with(['message' => 'データを更新しました。',
        'status' => 'info']);
    }

    public function toggleComplete($id)
    {
        $loading = Loading::findOrFail($id);
        $loading->is_completed = !$loading->is_completed;
        $loading->save();

        return response()->json(['is_completed' => $loading->is_completed]);
    }

    public function markBadgeSeen(Request $request)
    {
        // 現在のユーザーに関連するバッジを「確認済み」にする
        Loading::where('id', $request->id)->update(['is_new' => false]);
    
        return response()->json(['status' => 'success']);
    }

}
