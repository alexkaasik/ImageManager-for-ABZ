<x-layout>
    @section('title')
        Users Viewer
    @endsection
    <h1>Users Viewer</h1>

    <a href="{{ route('user.form') }}">Create User</a>
    <table>
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
