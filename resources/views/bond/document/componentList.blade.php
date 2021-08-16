<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <th>@sortablelink('bond_employee_name', 'Vínculo')</th>
            <th>@sortablelink('original_name', 'Nome do arquivo')</th>
            <th>@sortablelink('documentType.name', 'Tipo')</th>
        </thead>
        <tbody>
            @foreach ($documents as $document)
                <tr>
                    <td>{{ $document->bond->employee->name . '-' . $document->bond->role->name . '-' . $document->bond->course->name }}
                    </td>
                    <td><a href={{ route('documents.show', ['id' => $document->id, 'type' => 'BondDocument', 'htmlTitle' => $document->original_name]) }} target="_blank">{{ $document->original_name }}</a></td>
                    <td>{{ $document->documentType->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>