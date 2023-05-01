<x-app-layout>
  <div class="max-w-7xl mx-auto">
    <!-- UserName -->
    <div class="flex items-center justify-center min-h-[7rem]">
      <p class="text-center w-10/12 break-words">{{ Session::get('user_name') }}さんの勤務状況</p>
    </div>
    <!-- AttendanceList -->
    <div>
      <table class="w-11/12 table-fixed break-words mx-auto">
        <tr class="border-t border-black [&>th]:text-left sm:[&>th]:p-4">
          <th>日付</th>
          <th>勤務開始</th>
          <th>勤務終了</th>
          <th>休憩時間</th>
          <th>勤務時間</th>
        </tr>
        @foreach ($attendances as $attendance)
        <tr class="border-t border-black [&>td]:text-left sm:[&>td]:p-4">
          <td>{{$attendance['date']}}</td>
          <td>{{$attendance['start_time']}}</td>
          <td>{{$attendance['end_time']}}</td>
          <td>{{$attendance['total_break_time']}}</td>
          <td>{{$attendance['time_worked']}}</td>
        </tr>
        @endforeach
      </table>
      <div class="mt-8 mb-12">
        {{ $attendances->links('vendor.pagination.custom') }}
      </div>
    </div>
    
  </div>
</x-app-layout>
