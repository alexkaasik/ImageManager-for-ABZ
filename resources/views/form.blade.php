<x-Layout>
    @section('title')
        Create New User
    @endsection
    <h2 style="text-align: center; padding: 12px; color: #000000;">Create New User</h2>

    @if ($errors->any())
        <div style="color:red; background-color:rgb(217, 217, 217);">
            <strong>There were some problems with your input:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <br>
    <form action="{{ route('user.formhandler') }}" method="POST">
        @csrf
        <h2>Name:</h2>
        <input type="text" name="FullName" placeholder="Full name"/><br><br>

        <h2>Email:</h2>
        <input type="text" name="E-Mail" placeholder='Email addrss' /><br><br>

        <h2>Phone:</h2>
        <input type="text" id="phone" name="Phone" placeholder='+123 4567890' /><br><br>

        <h2>Position:</h2>
        <select name="PositionId" id="position">
            <option value="0">Select position</option>
            @foreach ($positions as $position)
                <option value="{{ $position['id'] }}">{{ $position['name'] }}</option>
            @endforeach
        </select><br><br>

        <button type="submit">Create User</button>
    </form>
</x-Layout>
