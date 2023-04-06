<button {{ $attributes->merge(['type' => 'submit', 'class' => 'w-full h-32 bg-white text-black rounded hover:bg-gray-200 focus:border-gray-900 disabled:text-gray-100']) }}>
    {{ $slot }}
</button>
