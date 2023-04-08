<x-app-layout>
  <div class="max-w-7xl mx-auto">  
    <!-- Message -->
    <div class="flex items-center h-28">
      <p class="m-auto">
        {{ Auth::user()->name }}さんお疲れさまです！
      </p>
    </div>
    <!-- Buttons -->
    <form action="/" method="post">
      @csrf
      <div class="grid grid-cols-2 gap-8 px-28 pb-28">
          <div>
            <x-stamp-button options="{{($user_state == 'off')?'enabled':'disabled'}}" name="change_state" value="start_work">
              勤務開始
            </x-stamp-button>
          </div>
          <div>
            <x-stamp-button options="{{($user_state != 'off')?'enabled':'disabled'}}" name="change_state" value="end_work">
              勤務終了
            </x-stamp-button>
          </div>
          <div>
            <x-stamp-button options="{{($user_state == 'working')?'enabled':'disabled'}}" name="change_state" value="start_rest">
              休憩開始
            </x-stamp-button>
          </div>
          <div>
            <x-stamp-button options="{{($user_state == 'resting')?'enabled':'disabled'}}" name="change_state" value="end_rest">
              休憩終了
            </x-stamp-button>
          </div>
      </div>
    </form>
  </div>
</x-app-layout>
