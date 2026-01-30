<x-app-layout>
  <div class="container px-5 py-12 mx-auto">
    <div class="xl:w-1/2 lg:w-3/4 w-full mx-auto text-center">
      <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="inline-block w-8 h-8 text-gray-400 mb-8" viewBox="0 0 975.036 975.036">
        <path d="M925.036 57.197h-304c-27.6 0-50 22.4-50 50v304c0 27.601 22.4 50 50 50h145.5c-1.9 79.601-20.4 143.3-55.4 191.2-27.6 37.8-69.399 69.1-125.3 93.8-25.7 11.3-36.8 41.7-24.8 67.101l36 76c11.6 24.399 40.3 35.1 65.1 24.399 66.2-28.6 122.101-64.8 167.7-108.8 55.601-53.7 93.7-114.3 114.3-181.9 20.601-67.6 30.9-159.8 30.9-276.8v-239c0-27.599-22.401-50-50-50zM106.036 913.497c65.4-28.5 121-64.699 166.9-108.6 56.1-53.7 94.4-114.1 115-181.2 20.6-67.1 30.899-159.6 30.899-277.5v-239c0-27.6-22.399-50-50-50h-304c-27.6 0-50 22.4-50 50v304c0 27.601 22.4 50 50 50h145.5c-1.9 79.601-20.4 143.3-55.4 191.2-27.6 37.8-69.4 69.1-125.3 93.8-25.7 11.3-36.8 41.7-24.8 67.101l35.9 75.8c11.601 24.399 40.501 35.2 65.301 24.399z"></path>
      </svg>
      <p class="leading-relaxed text-lg">編集</p>
      <span class="inline-block h-1 w-10 rounded bg-indigo-500 mt-2"></span>
    </div>
  </div>
  <div class="mt-4 flex justify-center">
    <div class="w-2/5">
      <form action="{{ route('confirm', ['id' => $loading->id]) }}" method="post">
        @csrf
        <table class="w-full bg-white">
          <tr>
            <th class="px-4 py-2 w-1/5 font-semibold text-base text-gray-700 bg-blue-100 border border-gray-700">
              日付
            </th>
            <td class="px-4 py-2 w-2/3 font-semibold text-base text-gray-700 bg-gray-100 border border-gray-700">
                <input class="w-full bg-gray-100 focus:outline-none focus:bg-white border border-none rounded-md px-3 py-2 mt-2 text-center" type="datetime-local" name="receiving" id="receiving" value="{{ $loading->receiving }}" required>
            </td>
          </tr>
          <tr>
            <th class="px-4 py-2 w-1/5 font-semibold text-base text-gray-700 bg-blue-100 border border-gray-700">
              名前
            </th>
            <td class="px-4 py-2 w-2/3 font-semibold text-base text-gray-700 bg-gray-100 border border-gray-700">
                <input class="w-full bg-gray-100 focus:outline-none focus:bg-white border border-none rounded-md px-3 py-2 mt-2 text-center" type="text" name="name" id="name" value="{{ $loading->name }}" required>
            </td>
          </tr>
          <tr>
            <th class="px-4 py-2 w-1/5 font-semibold text-base text-gray-700 bg-blue-100 border border-gray-700">
              名前(カナ)
            </th>
            <td class="px-4 py-2 w-2/3 font-semibold text-base text-gray-700 bg-gray-100 border border-gray-700">
                <input class="w-full bg-gray-100 focus:outline-none focus:bg-white border border-none rounded-md px-3 py-2 mt-2 text-center" type="text" name="nameKana" id="nameKana" value="{{ $loading->nameKana }}" required>
            </td>
          </tr>
          <tr>
            <th class="px-4 py-2 w-1/5 font-semibold text-base text-gray-700 bg-blue-100 border border-gray-700">
              ナンバー
            </th>
            <td class="px-4 py-2 w-2/3 font-semibold text-base text-gray-700 bg-gray-100 border border-gray-700">
                <textarea name="number" id="number" class="w-full bg-gray-100 focus:outline-none focus:bg-white border border-none rounded-md px-3 py-2 mt-2 text-center" required>{{ $loading->number }}</textarea>
            </td>
          </tr>
          <tr>
            <th class="px-4 py-2 w-1/5 font-semibold text-base text-gray-700 bg-blue-100 border border-gray-700">
              修理内容
            </th>
            <td class="px-4 py-2 w-2/3 font-semibold text-base text-gray-700 bg-gray-100 border border-gray-700">
                <textarea name="content" id="content" class="w-full bg-gray-100 focus:outline-none focus:bg-white border border-none rounded-md px-3 py-2 mt-2 text-center" required>{{ $loading->content }}</textarea>
            </td>
          </tr>
          <tr>
            <th class="px-4 py-2 w-1/5 font-semibold text-base text-gray-700 bg-blue-100 border border-gray-700">
              担当
            </th>
            <td class="px-4 py-2 w-2/3 font-semibold text-base text-gray-700 bg-gray-100 border border-gray-700">
                <input class="w-full bg-gray-100 focus:outline-none focus:bg-white border border-none rounded-md px-3 py-2 mt-2 text-center" type="text" name="charge" id="charge" value="{{ $loading->charge }}" required>
            </td>
          </tr>
          <tr>
            <th class="px-4 py-2 w-1/5 font-semibold text-base text-gray-700 bg-blue-100 border border-gray-700">
              出庫日
            </th>
            <td class="px-4 py-2 w-2/3 font-semibold text-base text-gray-700 bg-gray-100 border border-gray-700">
                <input class="w-full bg-gray-100 focus:outline-none focus:bg-white border border-none rounded-md px-3 py-2 mt-2 text-center" type="datetime-local" name="issue" id="issue" value="{{ $loading->issue }}" required>
            </td>
          </tr>
          <tr>
            <th class="px-4 py-2 w-1/5 font-semibold text-base text-gray-700 bg-blue-100 border border-gray-700">
              備考
            </th>
            <td class="px-4 py-2 w-2/3 font-semibold text-base text-gray-700 bg-gray-100 border border-gray-700">
                <textarea name="remarks" id="remarks" class="w-full bg-gray-100 focus:outline-none focus:bg-white border border-none rounded-md px-3 py-2 mt-2 text-center" required>{{ $loading->remarks }}</textarea>
            </td>
          </tr>
          <tr>
            <th class="px-4 py-2 w-1/5 font-semibold text-base text-gray-700 bg-blue-100 border border-gray-700">
              入庫場所
            </th>
            <td class="px-4 py-2 w-2/3 font-semibold text-base text-gray-700 bg-gray-100 border border-gray-700">
                <textarea name="place" id="place" class="w-full bg-gray-100 focus:outline-none focus:bg-white border border-none rounded-md px-3 py-2 mt-2 text-center" required>{{ $loading->place }}</textarea>
            </td>
          </tr>
        </table>
        <div class="flex justify-end mt-4 mb-4">
          <button type="button" onclick="location.href='{{ route('index') }}'" class="inline-flex ml-4 text-white bg-gray-500 border-0 py-2 px-8 focus:outline-none hover:bg-gray-600 rounded text-lg">戻る</button>
          <button type="submit" class="text-white inline-flex ml-4 bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">確認</button>
        </div>
      </form>
      
    </div>
  </div>
</x-app-layout>
