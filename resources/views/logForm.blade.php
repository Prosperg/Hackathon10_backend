<form action="{{route('log')}}" method="POST">
    @csrf
    <input type="text" name="email" id="" placeholder="email">
    <input type="password" name="password" id="" placeholder="password">
    <button type="submit">Login</button>
</form>