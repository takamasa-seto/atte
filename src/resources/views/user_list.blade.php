<x-app-layout>
  <div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-center h-28">
      <p class="w-36 text-center">ユーザ一覧</p>
    </div>
    <!-- UserList -->
    <div>
      <table class="w-11/12 table-fixed mx-auto">
        <tr class="border-t border-black [&>th]:text-left [&>th]:p-4">
          <th>名前</th>
          <th>E-mail</th>
          <th class="w-28"></th>
        </tr>
        @foreach ($users as $user)
        <tr class="border-t border-black [&>td]:text-left [&>td]:p-4">
          <td class="text-ellipsis overflow-hidden whitespace-nowrap hover:whitespace-normal hover:break-words">{{$user['name']}}</td>
          <td class="text-ellipsis overflow-hidden whitespace-nowrap hover:whitespace-normal hover:break-words">{{$user['email']}}</td>
          <td>
            <form action="/user_attendance" method="get">
              @csrf
              <input type="hidden" name="user_id" value="{{ $user['id'] }}">
              <button class="text-blue-800 bg-white border-solid border border-blue-800 hover:bg-gray-200 rounded w-20" type="submit">勤怠一覧</button>
            </form>
          </td>
        </tr>
        @endforeach
      </table>
      <div class="mt-8 mb-12">
        {{ $users->links('vendor.pagination.custom') }}
      </div>
    </div>
    
  </div>
</x-app-layout>
