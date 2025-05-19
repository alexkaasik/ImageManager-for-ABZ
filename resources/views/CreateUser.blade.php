<div>
    <title>Users Viewer</title>
    <h1>Create New User</h1>

    <form action="{{ route('user.viewUserForm') }}" method="POST">
        @csrf
        <label>Name:</label><br>
        <input type="text" name="FullName"><br><br>

        <label>Email:</label><br>
        <input type="email" name="Email"><br><br>

        <label>Phone:</label><br>
        <input type="tel" id="phone" name="Phone"><br><br>

        <label>Position:</label><br>
        <select name="Position" id="position">
            @foreach ($positions['positions'] as $position)
                <option value="{{ $position['id'] }}">{{ $position['name'] }}</option>
            @endforeach
        </select><br><br>

        <button type="submit">Create User</button>
    </form>
</div>
