<div class="flex items-center space-x-2">
    <img src="{{ captcha_src() }}" id="captcha-image" class="h-12 rounded border" alt="captcha">

    <button
        type="button"
        class="px-3 py-2 bg-gray-200 rounded hover:bg-gray-300"
        onclick="document.getElementById('captcha-image').src = '{{ captcha_src() }}' + '&' + Math.random()"
    >
        ↻
    </button>
</div>
