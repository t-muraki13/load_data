<x-app-layout>
@include('layouts.navigation', ['badgeCount' => $badgeCount ?? 0, 'salesBadgeCount' => $salesBadgeCount ?? 0, 'memoBadgeCount' => $memoBadgeCount ?? 0])
<x-flash-message status="session('status')" />
  <form action="{{ route('sales.index') }}" method="get">
    <div class="lg:flex lg:justify-end items-center max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
      <div>
        <span class="text-sm">日付絞りこみ</span>
        <div class="lg:flex items-center space-x-2">
          <input type="date" name="date" id="date" value="{{ request('date') }}">
          <div class="flex space-x-4 items-center">
            <div class="items-center">
              <button type="submit" class="text-white bg-blue-500 border-0 py-2 px-8 focus:outline-none hover:bg-blue-600 rounded text-lg">検索</button>
            </div>
          </div>
        </div>
      </div>
      <div class="lg:flex ml-8">
        <div>
          <span class="text-sm">表示順</span>
          <br>
          <select name="sort" id="sort"  class="mr-4">
            <option value="{{ \App\Constants\SalesCommon::SORT_ORDER['receiving'] }}" 
              @if(\Request::get('sort') === \App\Constants\SalesCommon::SORT_ORDER['receiving'])
                  selected
                  @endif>
                  入庫日順
            </option>
            <option value="{{ \App\Constants\SalesCommon::SORT_ORDER['name'] }}" 
              @if(\Request::get('sort') === \App\Constants\SalesCommon::SORT_ORDER['name'])
                  selected
                  @endif>
                  名前順
            </option>
            <option value="{{ \App\Constants\SalesCommon::SORT_ORDER['charge'] }}" 
              @if(\Request::get('sort') === \App\Constants\SalesCommon::SORT_ORDER['charge'])
                  selected
                  @endif>
                  担当者順
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
          </select>
        </div>
      </div>
    </div>
  </form>
  <div class="mt-4 flex justify-center">
    <div class="w-10/12">
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
              ナンバー(車種)
            </th>
            <th class="px-4 py-2 w-1/12 font-semibold text-base text-gray-700 bg-gray-100 border border-gray-700">
              来店内容
            </th>
            <th class="px-4 py-2 w-1/12 font-semibold text-base text-gray-700 bg-gray-100 border border-gray-700">
              担当
            </th>
          </tr>
        </thead>
        <tbody>
          @foreach ($sales as $sale)
            @php
                $isBadge = $sale->is_new == 1;

                $class = 'px-4 py-2 w-1/12 font-semibold text-base text-gray-700 bg-gray-100 border border-gray-700 text-center'; // デフォルトの背景色
                if ($isBadge) {
                  $class .= ' bg-yellow-200';
                }
            @endphp
          <tr class="transition-colors duration-300" id="row-{{ $sale->id }}">
              <td name="row-{{ $sale->id }}" class="{{ $class }}">
                  <button type="button" onclick="location.href='{{ route('sales.edit', ['id' => $sale->id]) }}'" class="inline-flex ml-4 mb-2 text-white bg-gray-500 border-0 py-2 px-8 focus:outline-none hover:bg-gray-600 rounded text-lg">編集</button>
                  <form id="delete_{{ $sale->id }}" action="{{ route('sales.destroy', ['id' => $sale->id]) }}" method="post">
                    @csrf
                    @method('delete')
                    <a data-id="{{ $sale->id }}" onclick="deletePost(this)" href="#" class="inline-flex ml-4 text-white bg-red-500 border-0 py-2 px-8 focus:outline-none hover:bg-red-600 rounded text-lg">完了</a>
                  </form>
              </td>
              <td class="{{ $class }}">
                  {{ $sale->receiving }}
              </td>
              <td class="{{ $class }}">
                  {{ $sale->name }}              
              </td>
              <td class="{{ $class }}">
                  {{ $sale->nameKana }}              
              </td>
              <td class="{{ $class }}">
                  {{ $sale->number }}
              </td>
              <td class="{{ $class }}">
                  {{ $sale->content }}
              </td>
              <td class="{{ $class }}">
                  {{ $sale->charge }}
              </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      {{ $sales->appends([
        'sort' => \Request::get('sort'),
        'pagination' => \Request::get('pagination')
      ])->links() }}
      <div class="flex justify-end mt-4 mb-4">
        <button type="button" onclick="location.href='{{ route('sales.create') }}'" class="inline-flex text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">登録</button>
      </div>
    </div>
  </div>
  <script src="{{ asset('js/delete.js') }}"></script>
  <script src="{{ asset('js/pagination.js') }}"></script>
  <script>
    let markBadgeSeenUrl = "{{ route('sales.mark-badge-seen') }}";
  </script>
  <script src="{{ asset('js/salesbadge.js') }}"></script>
</x-app-layout>
