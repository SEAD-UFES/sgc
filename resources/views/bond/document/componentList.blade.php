<div class="table-responsive">

    <table class="table table-striped table-hover">
        <thead>
            <th>@sortablelink('employee_name', 'Colaborador') </th>
            <th>@sortablelink('role_name', 'Cargo') </th>
            <th>@sortablelink('course_name', 'Curso') </th>
            <th>@sortablelink('original_name', 'Nome do arquivo')</th>
            <th>@sortablelink('document_type', 'Tipo')</th>
        </thead>
        <tbody>
            @foreach ($documents as $document)
                <tr>
                    <td>
                        <a href="{{ route('employees.show', $document->employee_id) }}" target="_blank">
                            {{ $document->employee_name }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('bonds.show', $document->bond_id) }}" target="_blank">
                            {{ $document->role_name }}
                        </a>
                    </td>
                    <td>
                        {{ $document->course_name }}
                    </td>
                    <td>
                        <a href="{{ route('bonds_documents.show', ['id' => $document->id, 'htmlTitle' => $document->original_name]) }}"
                            target="_blank">{{ $document->original_name }}</a>
                    </td>
                    <td>{{ $document->document_type }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<br />
{!! $documents->links() !!}
