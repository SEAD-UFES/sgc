<div class="card mb-3">
    <div class="card-header" data-bs-toggle="collapse" href="#employeeDataContent" role="button" aria-expanded="true" aria-controls="employeeDataContent">
        <h4 class='mb-0'>Dados do Colaborador</h4>
    </div>
    
    <div class="collapse show" id="employeeDataContent" >
        <div class="card-body" >
            @if($employee)
                <div class="mb-2 row">
                    <div class="col-sm-4 col-lg-3"><strong>Nome:</strong></div>
                    <div class="col-sm-8 col-lg-9">{{ $employee->name ?? '-' }}</div>
                </div>
                <div class="mb-2 row">
                    <div class="col-sm-4 col-lg-3"><strong>CPF:</strong></div>
                    <div class="col-sm-8 col-lg-9">{{ isset($employee->cpf) ? preg_replace('~(\d{3})(\d{3})(\d{3})(\d{2})~', '$1.$2.$3-$4', $employee->cpf) : '-' }}</div>
                </div>
                <div class="">
                    @can('employee-show')
                    <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-primary btn-sm">
                        <i class="bi-eye-fill"></i> Consultar colaborador
                    </a>&nbsp;
                    @endcan
                </div>
            @else
                <p class="mb-0">{{$no_employee_message ? $no_employee_message : 'Não existe colaborador associado.'}}</p>
            @endif
        </div> 
    </div>
</div>