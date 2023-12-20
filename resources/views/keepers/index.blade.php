<app-layout>
    <h2>Keepers Index Page</h2>
    @if($leagues)
    @forelse($leagues as $league)
    print_r($league);
    @empty
    <p>No Leagues</p>
    @endforelse
    @endif
</app-layout>