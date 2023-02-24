@extends('layouts.basic')

@section('title', 'Documentos')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">Estrutura</li>
            <li class="breadcrumb-item">Curso ({{ $course->name }})</li>
            <li class="breadcrumb-item active" aria-current="page">Listar Colaboradores</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-xxl-8">
                    @include('_components.alerts')

                    <form action={{ route('courses.create_employee_files_zip') }} method="POST">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    @can('employee-show')
                                    <th></th>
                                    @endcan
                                    <th>Nome</th>
                                    @can('employee-show')
                                        <th>CPF</th>
                                    @endcan
                                    <th>Função</th>
                                    <th>Início</th>
                                    <th>Polo</th>
                                    <th>Curso</th>
                                </thead>
                                <tbody>
                                    @foreach ($bonds as $bond)
                                        <tr>
                                            @can('employee-show')
                                            <td class="py-0 align-middle">
                                                <input type="checkbox" class="form-check-input"
                                                    name="bonds[{{ $bond->id }}]"
                                                    id="check_{{ $bond->id }}" />
                                            </td>
                                            @endcan
                                            <td>{{ $bond->employee->name }}</td>
                                            @can('employee-show')
                                                <td>{{ preg_replace('~(\d{3})(\d{3})(\d{3})(\d{2})~', '$1.$2.$3-$4', $bond->employee->cpf) }}</td>
                                            @endcan
                                            <td>{{ $bond->role->name }}</td>
                                            <td>{{ $bond->begin != null ? \Carbon\Carbon::parse($bond->begin)->isoFormat('DD/MM/Y') : '-' }}</td>
                                            <td>{{ $bond->pole?->name ?? '-' }}</td>
                                            <td>{{ $bond->course->name }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <br />
                        <a href="{{ route('courses.index') }}" class="btn btn-secondary">Voltar para a lista de Cursos</a>
                        @can('employee-show')
                            <button type="submit" class="btn btn-primary">Fazer download dos selecionados</button>
                        @endcan
                    </form>
                    <br /><br />
                </div>
            </div>
        </main>
    </section>
@endsection

@section('scripts')
    <script src="{{ asset('js/enable_tooltip_popover.js') }}"></script>
@endsection
