@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'text-gray-900 bg-white border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 focus:text-gray-900 placeholder-gray-400 rounded-md shadow-sm']) }}>
