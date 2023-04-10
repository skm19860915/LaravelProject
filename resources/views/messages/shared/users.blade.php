@if ($users->isEmpty())
    <p>No users</p>
@else
    @foreach ($users as $user)
        <li><a href="{{ route('messages.chat', [ 'ids' => auth()->user()->id  . '-' . $user->id ]) }}" class="list-group-item list-group-item-action">{{ $user->name }}</a></li>
    @endforeach
@endif