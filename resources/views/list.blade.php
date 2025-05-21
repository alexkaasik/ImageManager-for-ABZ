<x-layout>
    @section('title')
        Users Viewer
    @endsection
    <h2 style="text-align: center; padding: 12px; color: #000000 ;">Users Viewer</h2>

    <form action="{{ route('user.form') }}" method="get">
        <button type="submit">Create User</button>
    </form>
    
    <table style="color: #000000;">
        <tr>
            <th>
                Id
            </th>
            <th>
                Full Name
            </th>
            <th>
                E-Mail
            </th>
            <th>
                Phone
            </th>
            <th>
                Position
            </th>
        </tr>
        @forelse  ($users['users'] as $user) 
            <tr>
                <td>
                    {{ $user['id'] }}
                </td>
                <td>
                    {{ $user['FullName'] }}
                </td>
                <td>
                    {{ $user['E-Mail'] }}
                </td>
                <td>
                    {{ $user['Phone'] }}
                </td>
                <td>
                    {{ $user['PositionId'] }}
                </td>
            </tr>
        @empty
            <tr><td colspan="5" style="text-align:center;" >No users are available </td></tr>
        @endforelse
    <table>
</x-layout>
