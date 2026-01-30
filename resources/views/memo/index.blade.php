<x-app-layout>
@include('layouts.navigation', ['badgeCount' => $badgeCount ?? 0, 'salesBadgeCount' => $salesBadgeCount ?? 0, 'memoBadgeCount' => $memoBadgeCount ?? 0])
<x-flash-message status="session('status')" />
  <div class="container px-5 py-24 mx-auto">
    <div class="xl:w-1/2 lg:w-3/4 w-full mx-auto text-center">
      <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="inline-block w-8 h-8 text-gray-400 mb-2" viewBox="0 0 975.036 975.036">
        <path d="M925.036 57.197h-304c-27.6 0-50 22.4-50 50v304c0 27.601 22.4 50 50 50h145.5c-1.9 79.601-20.4 143.3-55.4 191.2-27.6 37.8-69.399 69.1-125.3 93.8-25.7 11.3-36.8 41.7-24.8 67.101l36 76c11.6 24.399 40.3 35.1 65.1 24.399 66.2-28.6 122.101-64.8 167.7-108.8 55.601-53.7 93.7-114.3 114.3-181.9 20.601-67.6 30.9-159.8 30.9-276.8v-239c0-27.599-22.401-50-50-50zM106.036 913.497c65.4-28.5 121-64.699 166.9-108.6 56.1-53.7 94.4-114.1 115-181.2 20.6-67.1 30.899-159.6 30.899-277.5v-239c0-27.6-22.399-50-50-50h-304c-27.6 0-50 22.4-50 50v304c0 27.601 22.4 50 50 50h145.5c-1.9 79.601-20.4 143.3-55.4 191.2-27.6 37.8-69.4 69.1-125.3 93.8-25.7 11.3-36.8 41.7-24.8 67.101l35.9 75.8c11.601 24.399 40.501 35.2 65.301 24.399z"></path>
      </svg>
      <p class="leading-relaxed text-lg">連絡事項</p>
      <span class="inline-block h-1 w-10 rounded bg-indigo-500 mt-8 mb-2"></span>
    </div>
  </div>
  <div class="flex justify-center">
    <div class="w-3/5">
      <table class="min-w-full bg-white mb-4">
        <thead>
          <tr>
            <th class="px-4 py-2 w-1/5 font-semibold text-base text-gray-700 bg-gray-100 border border-gray-700"></th>
            <th class="px-4 py-2 w-1/5 font-semibold text-base text-gray-700 bg-gray-100 border border-gray-700">
              内容
            </th>
          </tr>
        </thead>
        <tbody>
          @foreach ($memo as $memos)
            @php
                $isBadges = $memos->is_new == 1;
                
                $classes = 'px-4 py-2 w-1/5 font-semibold text-base text-gray-700 bg-gray-100 border border-gray-700 text-center'; // デフォルトの背景色
                if ($isBadges) {
                  $classes .= ' bg-yellow-200';
                }
            @endphp
            @php
                $isBadge = $memos->is_new == 1;
                
                $class = 'px-4 py-2 w-4/5 font-semibold text-base text-gray-700 bg-gray-100 border border-gray-700 text-left'; // デフォルトの背景色
                if ($isBadge) {
                  $class .= ' bg-yellow-200';
                }
            @endphp
          <tr class="transition-colors duration-300" id="row-{{ $memos->id }}">
              <td name="row-{{ $memos->id }}" class="{{ $classes }}">
                  <button type="button" onclick="location.href='{{ route('memo.edit', ['id' => $memos->id]) }}'" class="inline-flex ml-4 mb-2 text-white bg-gray-500 border-0 py-2 px-8 focus:outline-none hover:bg-gray-600 rounded text-lg">編集</button>
                  <form id="delete_{{ $memos->id }}" action="{{ route('memo.destroy', ['id' => $memos->id]) }}" method="post">
                    @csrf
                    @method('delete')
                    <a data-id="{{ $memos->id }}" onclick="deletePost(this)" href="#" class="inline-flex ml-4 text-white bg-red-500 border-0 py-2 px-8 focus:outline-none hover:bg-red-600 rounded text-lg">完了</a>
                  </form>
              </td>
              <td class="{{ $class }}">
                  {{ $memos->content }}
              </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      <div class="flex justify-end mt-4 mb-4">
        <button type="button" onclick="location.href='{{ route('memo.create') }}'" class="inline-flex text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">登録</button>
      </div>
    </div>
  </div>
  <script src="{{ asset('js/delete.js') }}"></script>
  <script>
    let markBadgeSeenUrl = "{{ route('memo.mark-badge-seen') }}";
  </script>
  <script src="{{ asset('js/memoBadge.js') }}"></script>
</x-app-layout>
