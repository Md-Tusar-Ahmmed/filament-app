<x-filament-panels::page>

    <form wire:submit.prevent="submit">

        {{$this->form}}

        <div class="mt-4" style="margin-top: 15px;">
            <x-filament::button type="submit"   >Send Message</x-filament::button>
        </div>

    </form>

</x-filament-panels::page>