<div class="table-responsive">

    <table class="table table-striped table-hover">
        <thead>
            <th>@sortablelink('employeeName', 'Colaborador') </th>
            <th>@sortablelink('roleName', 'Função') </th>
            <th>@sortablelink('courseName', 'Curso') </th>
            <th>@sortablelink('file_name', 'Nome do arquivo')</th>
            <th>@sortablelink('typeName', 'Tipo')</th>
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
                        {{ $document->course_name ?? '-' }}
                    </td>
                    <td>
                        <a href="{{ route('documents.show', ['id' => $document->id, 'htmlTitle' => $document->file_name]) }}"
                            target="_blank">{{ $document->file_name }}</a>
                    </td>
                    <td>{{ $document->document_type }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<br />
{!! $documents->links() !!}
