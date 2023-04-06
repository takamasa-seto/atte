<x-app-layout>
  <div class="max-w-7xl mx-auto">  
    <!-- Message -->
    <div class="flex items-center h-28">
      <p class="m-auto">
        {{ Auth::user()->name }}さんお疲れさまです！
      </p>
    </div>
    
    <!-- Buttons -->
    <form action="" method="POST">
      <div class="grid grid-cols-2 gap-8 px-28 pb-28">
          <div>
            <x-stamp-button disabled>勤務開始</x-stamp-button>
          </div>
          <div>
            <x-stamp-button>勤務終了</x-stamp-button>
          </div>
          <div>
            <x-stamp-button>休憩開始</x-stamp-button>
          </div>
          <div>
            <x-stamp-button disabled>休憩終了</x-stamp-button>
          </div>
      </div>
    </form>
  </div>
</x-app-layout>
