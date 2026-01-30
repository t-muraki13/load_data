<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\LoadingController;
use App\Models\Loading;
use Illuminate\Http\Request;

class LoadingDataController extends Controller
{
    public function getData(Request $request) {
        // 日付検索パラメータ
        $date = $request->input('date');
        //検索クエリパラメーター
        $query = $request->input('query');
        
        $baseQuery = Loading::query();
        //dd($baseQuery);
        //日付で検索する
        if ($date) {
            $receivingQuery = clone $baseQuery;
            $issueQuery = clone $baseQuery;

            $receivingLoadings = $receivingQuery
                ->whereDate('receiving', $date)
                ->get();

            $issueLLoadings = $issueQuery
                ->whereDate('issue', $date)
                ->get();

            $loadings = $receivingLoadings->merge($issueLLoadings)->unique('id')->values();
        } else {
            $loadings = $baseQuery->get();
        }
        //検索クエリによるフィルタリング
        if ($query) {
            $loadings = $loadings->filter(function ($load) use ($query) {
                //大文字・小文字を区別しない検索
                $searchQuery = mb_strtolower($query);
                return str_contains(mb_strtolower($load->name ?? ''), $searchQuery)  ||
                       str_contains(mb_strtolower($load->nameKana ?? ''), $searchQuery) ||
                       str_contains(mb_strtolower($load->number ?? ''), $searchQuery);
            })->values();
        }

        //フロントエンドでの処理のために生のデータを返す
        return response()->json([
            'loadings' => $loadings->toArray(),
            'message' => 'success'
        ]);
    }

}
