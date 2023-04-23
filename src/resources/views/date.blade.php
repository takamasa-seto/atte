<x-app-layout>
  <div class="max-w-7xl mx-auto">  
    <!-- DateSelection -->
    <form action="/attendance" method="get">
      @csrf
      <div class="flex items-center justify-center h-28">
        <button type="submit" name='change_date' value='prev' class="text-blue-800 bg-white border-solid border border-blue-800 w-8 hover:bg-gray-200 disabled:text-gray-100"><</button>
        <p class="w-36 text-center">{{ Session::get('date') }}</p>
        <button type="submit" name='change_date' value='next' {{ Session::get('has_next')?'enabled':'disabled' }} class="text-blue-800 bg-white border-solid border border-blue-800 w-8 hover:enabled:bg-gray-200 disabled:text-gray-100 disabled:border-gray-100" >></button>
      </div>
    </form>
    <!-- AttendanceList -->
    <div>
      <table class="w-11/12 table-fixed mx-auto">
        <tr class="border-t border-black [&>th]:text-left [&>th]:p-4">
          <th>名前</th>
          <th>勤務開始</th>
          <th>勤務終了</th>
          <th>休憩時間</th>
          <th>勤務時間</th>
        </tr>
        @foreach ($attendances as $attendance)
        <tr class="border-t border-black [&>td]:text-left [&>td]:p-4">
          <td>{{$attendance['user']['name']}}</td>
          <td>{{$attendance['start_time']}}</td>
          <td>{{$attendance['end_time']}}</td>
          <td>{{$attendance['total_break_time']}}</td>
          <td>{{$attendance['time_worked']}}</td>
        </tr>
        @endforeach
      </table>
      {{ $attendances->links() }}
    </div>
    
  </div>
</x-app-layout>
