<x-filament-panels::page>   
    
    <form wire:submit.prevent="submit">
        @csrf
        {{ $this->form }}

        <x-filament::button type="submit">
            Ask
        </x-filament::button>
    </form>

    @if ($response)
        <div class="mt-4 p-4 bg-gray-100 border border-gray-200 rounded">
            <strong>Response:</strong>
            <p>{{ $response }}</p>
        </div>
    @endif

</x-filament-panels::page>
