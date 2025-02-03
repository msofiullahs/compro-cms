<div>
    <x-slot:sidebar
        drawer="main-drawer"          {{-- Drawer ID trigger for mobile --}}
        collapsible                   {{-- Make it collapsible --}}
        collapse-text="Hide it"       {{-- Custom collapsible text --}}
        class="bg-base-100"           {{-- Any Tailwind class--}}
        right                         {{-- Move it to the right side --}}
        right-mobile                  {{-- Move the drawer to the right side only for mobile devices --}}
    >
        ...
    </x-slot:sidebar>
</div>
