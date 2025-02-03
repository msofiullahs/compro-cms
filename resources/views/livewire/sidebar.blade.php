<div>
    <x-slot:sidebar drawer="main-drawer" collapsible class="dark:bg-gray-800 lg:bg-inherit">

        {{-- BRAND --}}
        <div class="px-5 pt-5">
            <x-application-logo />
        </div>

        {{-- MENU --}}
        <x-menu activate-by-route>
            @php
                $user = auth()->user();
            @endphp
            <x-menu-separator />
            <x-list-item :item="$user" value="name" sub-value="email" no-separator no-hover class="-mx-2 !-my-2 rounded">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <x-slot:avatar>
                        <img class="size-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                    </x-slot:avatar>
                @endif
                <x-slot:actions>
                    <x-button icon="o-power" class="btn-circle btn-ghost btn-xs" tooltip-left="Logout" no-wire-navigate link="{{ route('logout') }}" click.prevent="$root.submit();" />
                </x-slot:actions>
            </x-list-item>

            <x-menu-separator />

            <x-menu-item title="Dasboard" icon="o-sparkles" link="{{ route('dashboard') }}" route="dashboard" />
            <x-menu-item title="Page" icon="o-document" link="bb" />
            <x-menu-item title="Media" icon="s-radio" link="bb" />

            @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                <x-menu-sub title="{{ Auth::user()->currentTeam->name }}" icon="o-user-group">
                    <x-menu-item title="Team Settings" icon="s-cog" link="{{ route('teams.show', Auth::user()->currentTeam->id) }}" route="teams.show" />
                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                        <x-menu-item title="Create New Team" icon="o-rectangle-group" link="{{ route('teams.create') }}" route="teams.create" />
                    @endcan
                    @if (Auth::user()->allTeams()->count() > 1)
                        <x-menu-separator />
                        <x-menu-item title="Switch Team" icon="o-swatch" />
                        @foreach (Auth::user()->allTeams() as $team)
                            <x-switchable-team :team="$team" />
                        @endforeach
                    @endif
                </x-menu-sub>
            @endif

            <x-menu-sub title="Manage Account" icon="o-adjustments-horizontal">
                <x-menu-item title="Profile" icon="o-user-circle" link="{{ route('profile.show') }}" route="profile.show" />
                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <x-menu-item title="API Tokens" icon="m-arrow-path-rounded-square" link="{{ route('api-tokens.index') }}" route="api-tokens.index" />
                @endif
            </x-menu-sub>
        </x-menu>
    </x-slot:sidebar>
</div>
