<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-2 py-2 rounded-md bg-[#534ce6] border border-transparent font-semibold text-sm text-white tracking-wide shadow-md shadow-purple-900/30 hover:bg-[#635cff] hover:shadow-purple-600/20 active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-purple-500/50 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>


