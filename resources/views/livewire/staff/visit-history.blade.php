<div>
    <table class="table">
        <thead>
            <tr>
                <th>Visitor</th>
                <th>Status</th>
                <th>Date</th>
                <th>Checked Out</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($history as $visit)
                <tr>
                    <td>{{ $visit->visitor->name }}</td>
                    <td>{{ ucfirst($visit->status) }}</td>
                    <td>{{ $visit->visit_date }}</td>
                    <td>{{ $visit->is_checked_out ? 'Yes' : 'No' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $history->links() }}
</div>
