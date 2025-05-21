<div>
    <title>Create New User</title>
    <h1>Create New User</h1>

    @if ($errors->any())
        <div style="color:red;">
            <strong>There were some problems with your input:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('user.formhandler') }}" method="POST">
        @csrf
        <label>Name:</label><br>
        <input type="text" name="FullName"><br><br>

        <label>Email:</label><br>
        <input type="email" name="E-Mail"><br><br>

        <label>Phone:</label><br>
        <input type="tel" id="phone" name="Phone"><br><br>

        <label>Position:</label><br>
        <select name="PositionId" id="position">
            @foreach ($positions['positions'] as $position)
                <option value="{{ $position['id'] }}">{{ $position['name'] }}</option>
            @endforeach
        </select><br><br>

        <button type="submit">Create User</button>
    </form>
</div>
