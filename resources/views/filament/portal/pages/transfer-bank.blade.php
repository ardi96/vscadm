<x-filament-panels::page>

    {{ $this->table }}

    <div class="justify-end mt-4">
    
        <p class="font-bold text-md mb-2">
            Total : IDR {{ number_format($total_amount, 2, '.', ',') }}
        </p>
        
        {{-- <p class="text-sm text-gray-600 mt-2">
            Klik tombol "Lanjut ke Pembayaran" untuk melanjutkan ke proses pembayaran secara online.
        </p> --}}

        <hr class="my-4">

        @if( $total_amount > 0 )

        <div class="mt-2">
        
            <x-filament-panels::form wire:submit="proceedToPayment">
        
                {{ $this->form }}   
        
                <x-filament-panels::form.actions 
                    :actions="$this->getFormActions()"
                />
        
            </x-filament-panels::form>
        
        </div>

        @endif 
    
    </div>   


</x-filament-panels::page>
