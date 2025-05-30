<x-Layout>
    <style>
    </style>
    @section('title')
        Users Viewer
    @endsection
    <h2 style="text-align: center; padding: 12px; color: #000000;">Users Viewer</h2>
    
    <table style="color: #000000;">
        <tr>
            <th class="tableTd">
                
            </th>
            <th class="tableTd">
                Full Name
            </th>
            <th class="tableTd">
                E-Mail
            </th>
            <th class="tableTd">
                Phone
            </th>
            <th class="tableTd">
                Position
            </th>
        </tr>
        @forelse  ($users as $user) 
            <tr>
                <td class="tableTd">
                    <img src="{{ asset($user['Photo']) }}" alt="User Profile">                     
                </td>
                <td class="tableTd">
                    {{ $user['FullName'] }}
                </td>
                <td class="tableTd">
                    {{ $user['E-Mail'] }}
                </td>
                <td class="tableTd">
                    {{ $user['Phone'] }}
                </td>
                <td class="tableTd">
                    {{ $user['PositionId'] }}
                </td>
            </tr>
        @empty
            <tr style="vertical-align: middle;"><td colspan="5" style="text-align:center;" >No users are available </td></tr>
        @endforelse
        </table>
        <nav style="margin-top: 20px; text-align: center;">
            @foreach  ($links as $link) 
                @if ($link['url'] != null)
                    <a href="{{ $link['url'] }}" style="text-decoration:none; padding: 4px;{{ $link['active'] ? 'font-weight: bold;' : '' }}">
                        {!! $link['label'] !!}
                    </a>
                @else
                    {!! $link['label'] !!}
                @endif
            @endforeach
        </nav>
</x-Layout>
