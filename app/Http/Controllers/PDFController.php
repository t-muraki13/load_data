<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TCPDF;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\View;
use App\Models\Loading;

class PDFController extends Controller
{
    public function generatePDF(Request $request)
    {
        //　日付を取得
        $parseDate = $request->query('date');
        $query = $request->query('query');

        // クエリを組み立ててデータを取得
        $receivingQuery = Loading::select('id', 'receiving', 'name', 'nameKana', 'number', 'content', 'charge', 'issue', 'remarks', 'place')
            ->when($parseDate, function ($query, $parseDate) {
                $query->whereDate('receiving', $parseDate)
                      ->orderBy('receiving', 'asc');
            })
            ->when($query, function ($query, $queryValue) {
                $query->where('name', 'like', "%{$queryValue}%")
                      ->orWhere('nameKana', 'like', "%{$queryValue}%")
                      ->orWhere('number', 'like', "%{$queryValue}%");
            });

        $issueQuery = Loading::select('id', 'receiving', 'name', 'nameKana', 'number', 'content', 'charge', 'issue', 'remarks', 'place')
            ->when($parseDate, function ($query, $parseDate) {
                $query->whereDate('issue', $parseDate)
                      ->orderBy('issue', 'asc');
            })
            ->when($query, function ($query, $queryValue) {
                $query->where('name', 'like', "%{$queryValue}%")
                      ->orWhere('nameKana', 'like', "%{$queryValue}%")
                      ->orWhere('number', 'like', "%{$queryValue}%");
            });
        // クエリ結果をマージ
        $receivingLoadings = $receivingQuery->get();
        $issueLoadings = $issueQuery->get();
        $mergedLoadings = $receivingLoadings->merge($issueLoadings);

        // TCPDF オブジェクトの作成
        $pdf = new TCPDF();
        
        // 日本語フォントを設定（必要に応じて）
        $pdf->SetFont('ipaexm', '', 12);

        // ページの追加
        $pdf->AddPage('L', 'A4');

        // HTML コンテンツの作成
        $html = '';
        $html .= '<table border="1" cellpadding="4">';
        $html .= '<thead>
                   <tr>
                     <th>日付</th>
                     <th>名前</th>
                     <th>名前(カナ)</th>
                     <th>ナンバー</th>
                     <th>修理内容</th>
                     <th>担当</th>
                     <th>出庫日</th>
                     <th>備考</th>
                     <th>入庫場所</th>
                   </tr>
                  </thead>
                  <tbody>';

        // 背景色を切り替えるフラグ
        $rowcolor = false;
        $previousDate = null;
        $parseDateFormatt = date('Y-m-d', strtotime($parseDate));
        $inserted = false;

        foreach ($mergedLoadings as $load) {

          $issueDate = date('Y-m-d', strtotime(substr($load->issue, 0, 10)));
          $currentDate = date('Y-m-d', strtotime(substr($load->receiving, 0, 10)));
          //dd($currentDate);
            //デフォルト背景色
            $bgcolor = '#ffffff';
            if (strpos($load->content, '待ち') !== false) {
              $bgcolor = '#fee2e2';
            } elseif (strpos($load->place, '品川') !== false) {
              $bgcolor = '#d1fae5';
            } elseif (strpos($load->remarks, 'WS') !== false || strpos($load->remarks, 'SC') !== false) {
              $bgcolor = '#dbeafe';
            }

            // 日付で検索した際に入庫日と出庫日の間にラインを入れる
          if (!$inserted && ($previousDate !== $parseDateFormatt || $issueDate === $parseDateFormatt) && $currentDate !== $parseDateFormatt) {
                $html .= '<tr><td colspan="9" style="background-color: yellow; padding: 0; line-height: 0;"></td></tr>';
                $inserted = true;
            }

            $html .= '<tr style="background-color:' . $bgcolor . ';">' .
                       '<td>' . htmlspecialchars($load->receiving) . '</td>' .
                       '<td>' . htmlspecialchars($load->name) . '</td>' .
                       '<td>' . htmlspecialchars($load->nameKana) . '</td>' .
                       '<td>' . htmlspecialchars($load->number) . '</td>' .
                       '<td>' . htmlspecialchars($load->content) . '</td>' .
                       '<td>' . htmlspecialchars($load->charge) . '</td>' .
                       '<td>' . htmlspecialchars($load->issue) . '</td>' .
                       '<td>' . htmlspecialchars($load->remarks) . '</td>' .
                       '<td>' . htmlspecialchars($load->place) . '</td>' .
                     '</tr>';

            $rowcolor = !$rowcolor;
            $previousDate = $currentDate;
        }

        $html .= '</tbody></table>';

        // HTML を PDF に変換
        $pdf->writeHTML($html, true, false, true, false, '');

        // PDF を出力
        return response($pdf->Output('入出庫.pdf', 'I'))
                ->header('Content-Type', 'application/pdf');
    }
}
