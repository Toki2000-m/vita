@props(['text', 'type' => 'button'])

<button {{ $attributes->merge([
  'type' => $type,
  'class' => 'bg-[#B497BD] text-white px-4 py-2 rounded hover:bg-[#9A76B0]'
]) }}>
  {{ $text }}
</button>
