<x-app-layout>
@include('layouts.navigation', ['badgeCount' => $badgeCount  ?? 0, 'salesBadgeCount' => $salesBadgeCount ?? 0, 'memoBadgeCount' => $memoBadgeCount ?? 0])
<x-flash-message status="session('status')" />

  <form action="{{ route('index') }}" method="get">
    <div class="lg:flex lg:justify-end items-center max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
      <div>
        <span class="text-sm">日付検索</span>
        <div class="lg:flex items-center space-x-2">
          <input type="date" name="date" id="date" value="{{ request('date') }}">
        </div>
      </div>
      <div class="ml-2">
        <span class="text-sm">検索</span>
        <div class="lg:flex items-center space-x-2">
          <input type="text" name="query" id="query" value="{{ request('query') }}" placeholder="名前、名前(カナ)、ナンバー検索" class="w-80">
        </div>
      </div>
      <div class="flex space-x-4 items-center mt-6 ml-2">
        <div class="items-center">
          <button type="submit" class="text-white bg-blue-500 border-0 py-2 px-8 focus:outline-none hover:bg-blue-600 rounded text-lg">検索</button>
        </div>
      </div>
      <div class="lg:flex ml-8">
        <div>
          <span class="text-sm">表示順</span>
          <br>
          <select name="sort" id="sort"  class="mr-4">
            <option value="{{ \App\Constants\Common::SORT_ORDER['receiving'] }}" 
              @if(\Request::get('sort') === \App\Constants\Common::SORT_ORDER['receiving'])
                  selected
                  @endif>
                  入庫日順
            </option>
            <option value="{{ \App\Constants\Common::SORT_ORDER['name'] }}" 
              @if(\Request::get('sort') === \App\Constants\Common::SORT_ORDER['name'])
                  selected
                  @endif>
                  名前順
            </option>
            <option value="{{ \App\Constants\Common::SORT_ORDER['charge'] }}" 
              @if(\Request::get('sort') === \App\Constants\Common::SORT_ORDER['charge'])
                  selected
                  @endif>
                  担当者順
            </option>
            <option value="{{ \App\Constants\Common::SORT_ORDER['issue'] }}" 
              @if(\Request::get('sort') === \App\Constants\Common::SORT_ORDER['issue'])
                  selected
                  @endif>
                  出庫日順
            </option>
            <option value="{{ \App\Constants\Common::SORT_ORDER['place'] }}" 
              @if(\Request::get('sort') === \App\Constants\Common::SORT_ORDER['place'])
                  selected
                  @endif>
                  入庫場所順
            </option>
          </select>
        </div>
        <div class="ml-4">
          <span class="text-sm">表示件数</span>
          <br>
          <select name="pagination" id="pagination">
            <option value="5" @if(\Request::get('pagination') === '5') selected @endif>
              5件
            </option>
            <option value="10" @if(\Request::get('pagination') === '10') selected @endif>
              10件
            </option>
            <option value="20" @if(\Request::get('pagination') === '20') selected @endif>
              20件
            </option>
            <option value="30" @if(\Request::get('pagination') === '30') selected @endif>
              30件
            </option>
          </select>
        </div>
      </div>
    </div>
  </form>
  
  
  <div class="mt-4 flex justify-center">
    <div class="w-11/12">
      <table class="min-w-full bg-white mb-4">
        <thead>
          <tr>
            <th class="px-4 py-2 w-1/12 font-semibold text-base text-gray-700 bg-gray-100 border border-gray-700"></th>
            <th class="px-4 py-2 w-1/12 font-semibold text-base text-gray-700 bg-gray-100 border border-gray-700">
              日付
            </th>
            <th class="px-4 py-2 w-1/12 font-semibold text-base text-gray-700 bg-gray-100 border border-gray-700">
              名前
            </th>
            <th class="px-4 py-2 w-1/12 font-semibold text-base text-gray-700 bg-gray-100 border border-gray-700">
              名前(カナ)
            </th>
            <th class="px-4 py-2 w-1/12 font-semibold text-base text-gray-700 bg-gray-100 border border-gray-700">
              ナンバー
            </th>
            <th class="px-4 py-2 w-1/12 font-semibold text-base text-gray-700 bg-gray-100 border border-gray-700">
              修理内容
            </th>
            <th class="px-4 py-2 w-1/12 font-semibold text-base text-gray-700 bg-gray-100 border border-gray-700">
              担当
            </th>
            <th class="px-4 py-2 w-1/12 font-semibold text-base text-gray-700 bg-gray-100 border border-gray-700">
              出庫日
            </th>
            <th class="px-4 py-2 w-1/12 font-semibold text-base text-gray-700 bg-gray-100 border border-gray-700">
              備考
            </th>
            <th class="px-4 py-2 w-1/12 font-semibold text-base text-gray-700 bg-gray-100 border border-gray-700">
              入庫場所
            </th>
          </tr>
        </thead>
        <tbody id="load">
          @foreach ($loading as $load)
            @php
                $isPending = strpos($load->content, '待ち') !== false;
                $isPlace = strpos($load->place, '品川') !== false;
                $isDelivery = strpos($load->remarks, 'WS') !== false || strpos($load->remarks, 'SC') !== false;
                $isBadge = $load->is_new == 1;

                $class = 'row-6 px-4 py-2 w-1/12 font-semibold text-base text-gray-700 border border-gray-700 text-center'; // デフォルトの背景色
                if ($isPending) {
                    $class .= ' bg-red-100';
                }
                if ($isPlace) {
                    $class .= ' bg-green-100';
                }
                if ($isDelivery) {
                    $class .= ' bg-blue-100';
                }
                if ($isBadge) {
                  $class .= ' bg-yellow-200';
                }
            @endphp
          <tr class="transition-colors duration-300">
              <td class="row-{{ $load->id }} {{ $class }}">
                  <button type="button" onclick="location.href='{{ route('edit', ['id' => $load->id])}}'" class="inline-flex ml-4 mb-2 text-white bg-gray-500 border-0 py-2 px-8 focus:outline-none hover:bg-gray-600 rounded text-lg">編集</button>
                  <button id="toggle-button-{{ $load->id }}" type="button" class="inline-flex ml-4 text-white bg-red-500 border-0 py-2 px-8 focus:outline-none hover:bg-red-600 rounded text-lg" onclick="toggleComplete('{{ $load->id }}')">完了</button>
              </td>
              <td class="row-{{ $load->id }} {{ $class }}">
                  {{ $load->receiving }}
              </td>
              <td class="row-{{ $load->id }} {{ $class }}">
                  {{ $load->name }}              
              </td>
              <td class="row-{{ $load->id }} {{ $class }}">
                  {{ $load->nameKana }}              
              </td>
              <td class="row-{{ $load->id }} {{ $class }}">
                  {{ $load->number }}
              </td>
              <td class="row-{{ $load->id }} {{ $class }}">
                  {{ $load->content }}
              </td>
              <td class="row-{{ $load->id }} {{ $class }}">
                  {{ $load->charge }}
              </td>
              <td class="row-{{ $load->id }} {{ $class }}">
                  {{ $load->issue }}
              </td>
              <td class="row-{{ $load->id }} {{ $class }}">
                  {{ $load->remarks }}
              </td>
              <td class="row-{{ $load->id }} {{ $class }}">
                  {{ $load->place }}
              </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      {{ $loading->appends([
        'sort' => \Request::get('sort'),
        'pagination' => \Request::get('pagination')
      ])->links() }}

      <div class="flex justify-end mt-4 mb-4">
        <div class="flex justify-end mt-4 mb-4">
          <button type="button" onclick="previewPDF()" data-url="{{ route('generate.pdf') }}" class="inline-flex text-white bg-yellow-500 border-0 py-2 px-8 focus:outline-none hover:bg-yellow-600 rounded text-lg">PDF</button>
        </div>
        <div class="flex justify-end mt-4 mb-4 ml-4">
          <button type="button" onclick="location.href='{{ route('create') }}'" class="inline-flex text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">登録</button>
        </div>
      </div>
    </div>
  </div>
  <script src="{{ asset('js/index.js') }}"></script>
  <script src="{{ asset('js/pagination.js') }}"></script>
  <script src="{{ asset('js/pdf.js') }}"></script>
  <script>
    let markBadgeSeenUrl = "{{ route('mark-badge-seen') }}";
  </script>
  <script src="{{ asset('js/badge.js') }}"></script>
  <script>

    function getHtml(load) {
      //console.log(load.id);
      // 1. クラスリストの初期定義
      // ★ ReferenceErrorを防ぐために'let'で変数を宣言し、初期クラスを設定
      let classList = 'row-6 px-4 py-2 w-1/12 font-semibold text-base text-gray-700 border border-gray-700 text-center'; 
      
      // 2. 編集URLの定義
      // ★ editUrlを定義。ルートは実際のアプリケーションに合わせて修正してください
      const editUrl = `/edit/${load.id}`; // 例: /edit/123 の形式
      
      // 3. クラス追加の条件判定ロジック (Bladeのロジックを再現)
      // ⚠️ 注意: JavaScriptのindexOfやincludesで文字列検索を行います
      const isPending = load.content && load.content.includes('待ち');
      const isPlace = load.place && load.place.includes('品川');
      const isDelivery = load.remarks && (load.remarks.includes('WS') || load.remarks.includes('SC'));
      const isBadge = load.is_new == 1; // APIのデータに is_new フィールドがある前提
      
      if (isPending) {
        classList += ' bg-red-100';
      }
      if (isPlace) {
        classList += ' bg-green-100';
      }
      if (isDelivery) {
        classList += ' bg-blue-100';
      }
      if (isBadge) {
        classList += ' bg-yellow-200';
      }

      //console.log(load);
      
      return `
            <tr class="transition-colors duration-300">
                <td class="row-${load.id} ${classList}">
                    <button type="button" onclick="location.href='${editUrl}'" class="inline-flex ml-4 mb-2 text-white bg-gray-500 border-0 py-2 px-8 focus:outline-none hover:bg-gray-600 rounded text-lg">編集</button>
                    <button id="toggle-button-${load.id}" type="button" class="inline-flex ml-4 text-white bg-red-500 border-0 py-2 px-8 focus:outline-none hover:bg-red-600 rounded text-lg" onclick="toggleComplete('${load.id}')">完了</button>
                </td>
                <td class="row-${load.id} ${classList}">${load.receiving || ''}</td>
                <td class="row-${load.id} ${classList}">${load.name || ''}</td>
                <td class="row-${load.id} ${classList}">${load.nameKana|| ''}</td>
                <td class="row-${load.id} ${classList}">${load.number|| ''}</td>
                <td class="row-${load.id} ${classList}">${load.content|| ''}</td>
                <td class="row-${load.id} ${classList}">${load.charge|| ''}</td>
                <td class="row-${load.id} ${classList}">${load.issue|| ''}</td>
                <td class="row-${load.id} ${classList}">${load.remarks|| ''}</td>
                <td class="row-${load.id} ${classList}">${load.place|| ''}</td>
            </tr>
        `;
    }

    const refresh = 5000;
    async function fetchUsers() {
      const currentParams = new URLSearchParams(window.location.search).toString();
      console.log(currentParams);
      const apiUrl = `http://127.0.0.1:8000/api/loadings/data?${currentParams}`;
      const response = await fetch(apiUrl);
      
      const json = await response.json();
      const data = json.loadings;
      const loadElement = document.getElementById('load');

      let rowData = '';

     //console.log(data[0]);

      for (const loadData of data) {
        //console.log(loadData);
        rowData += getHtml(loadData);
        //console.log(rowData);
      }

      //console.log(rowData);

      loadElement.innerHTML = rowData;
      
    }
    
    fetchUsers();

    setInterval(fetchUsers, refresh);
    
  </script>
</x-app-layout>
