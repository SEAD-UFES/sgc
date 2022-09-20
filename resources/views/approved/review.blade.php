@extends('layouts.basic')

@section('title', 'Revisão de Importação')

@section('content')
    <script>
        let checkboxFlag = false;

        function selectAssign(id, valueToSelect) {
            let element = document.getElementById(id);
            element.value = valueToSelect;
        }

        function selectRotateAssign(idPrefix, keyCount, valueToSelect) {
            for (let i = 0; i < keyCount; i++)
                selectAssign(idPrefix + "_" + i, valueToSelect);
        }

        function toggleCheck(keyCount) {
            for (let i = 0; i < keyCount; i++) {
                var box = document.getElementById("check_" + i);

                if (checkboxFlag) {
                    box.checked = false;
                } else {
                    box.checked = true;
                }
            }

            checkboxFlag = !checkboxFlag;
        }
        
        $(document).ready(function(){
            Inputmask().mask(document.querySelectorAll("input"));
        });
    </script>
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">Colaboradores</li>
            <li class="breadcrumb-item">Importar planilha de Aprovados</li>
            <li class="breadcrumb-item active" aria-current="page">Revisão de Importação</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            <div class="row">
                <div class="col">
                    @include('_components.alerts')
                    <div class="d-xl-flex float-md-end">
                        <span class="text-danger my-auto">Atribuir em lote&nbsp;</span>
                        <span class="text-danger my-auto d-none d-xl-block"> &rArr;&nbsp;</span>
                        <span class="text-danger my-auto d-inline d-xl-none">&dArr;</span>
                        <select name="courses_mass" id="courses_mass"
                            onchange="selectRotateAssign('courses', {{ count($importedApproveds) }}, document.getElementById('courses_mass').value)"
                            class="form-select w-auto" data-live-search="true">
                            <option value="">Selecione o curso</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->name }}</option>
                            @endforeach
                        </select>
                        <select name="roles_mass" id="roles_mass"
                            onchange="selectRotateAssign('roles', {{ count($importedApproveds) }}, document.getElementById('roles_mass').value)"
                            class="form-select w-auto" data-live-search="true">
                            <option value="">Selecione a Função</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        <select name="poles_mass" id="poles_mass"
                            onchange="selectRotateAssign('poles', {{ count($importedApproveds) }}, document.getElementById('poles_mass').value)"
                            class="form-select w-auto" data-live-search="true">
                            <option value="">Selecione o polo</option>
                            @foreach ($poles as $pole)
                                <option value="{{ $pole->id }}">{{ $pole->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col">
                    <span class="text-danger">&dArr; Selecionar/Desselecionar todos</span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <form action={{ route('approveds.store_many.step_2') }} method="POST">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <th><span onclick="toggleCheck({{ count($importedApproveds) }})" style="cursor: pointer;"
                                            class="text-success">&check;</span></th>
                                    <th>Nome</th>
                                    <th>E-mail</th>
                                    <th>DDD</th>
                                    <th>Telefone</th>
                                    <th>Celular</th>
                                    <th>Edital</th>
                                    <th>Curso</th>
                                    <th>Função</th>
                                    <th>Polo</th>
                                </thead>
                                <tbody>
                                    @foreach ($importedApproveds as $key => $approved)
                                        <tr class="p-0">
                                            <td class="py-0 align-middle">
                                                <input type="checkbox" class="form-check-input"
                                                    name="approveds[{{ $key }}][check]" id="check_{{ $key }}"
                                                    {{ old('approveds') && Arr::exists(old('approveds')[$key], 'check')  ? ' checked' : '' }} />
                                            </td>
                                            <td title="{{ $approved->name }}" class="p-0">
                                                <input type="text" class="form-control w-100"
                                                    name="approveds[{{ $key }}][name]"
                                                    value="{{ old('approveds') ? old('approveds')[$key]['name'] : $approved->name }}" />
                                            </td>
                                            <td title="{{ $approved->email }}" class="p-0">
                                                <input type="text" class="form-control w-100"
                                                    name="approveds[{{ $key }}][email]"
                                                    value="{{ old('approveds') ? old('approveds')[$key]['email'] : $approved->email }}" />
                                            </td>
                                            <td title="{{ $approved->area_code }}" class="p-0">
                                                <input type="text" class="form-control" name="approveds[{{ $key }}][area_code]"
                                                    value="{{ old('approveds') ? old('approveds')[$key]['area_code'] : $approved->area_code }}" maxlength="2" size="2" />
                                            </td>
                                            <td title="{{ $approved->phone }}" class="p-0">
                                                <input type="text" class="form-control" name="approveds[{{ $key }}][phone]"
                                                value="{{ old('approveds') ? old('approveds')[$key]['phone'] : $approved->phone }}" maxlength="14" size="14"
                                                data-inputmask="'mask': '(99) 9999-9999', 'removeMaskOnSubmit': true" />
                                            </td>
                                            <td title="{{ $approved->mobile }}" class="p-0">
                                                <input type="text" class="form-control" name="approveds[{{ $key }}][mobile]"
                                                value="{{ old('approveds') ? old('approveds')[$key]['mobile'] : $approved->mobile }}" maxlength="16" size="16"
                                                data-inputmask="'mask': '(99) 9 9999-9999', 'removeMaskOnSubmit': true" />
                                            </td>
                                            <td title="{{ $approved->announcement }}" class="p-0">
                                                <input type="text" class="form-control"
                                                    name="approveds[{{ $key }}][announcement]"
                                                    value="{{ old('approveds') ? old('approveds')[$key]['announcement'] : $approved->announcement }}" maxlength="8" size="8" />
                                            </td>
                                            <td class="p-0">
                                                <select name="approveds[{{ $key }}][course_id]"
                                                    id="courses_{{ $key }}" class="form-select"
                                                    data-live-search="true">
                                                    <option value="">Selecione o curso</option>
                                                    @foreach ($courses as $course)
                                                        <option value="{{ $course->id }}"
                                                        {{ old('approveds') ? (old('approveds')[$key]['course_id'] == $course->id ? 'selected' : '') : ($course->id == 1 ? 'selected' : '') }}>{{ $course->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="p-0">
                                                <select name="approveds[{{ $key }}][role_id]" id="roles_{{ $key }}"
                                                    class="form-select" data-live-search="true">
                                                    <option value="">Selecione a Função</option>
                                                    @foreach ($roles as $role)
                                                        <option value="{{ $role->id }}"
                                                        {{ old('approveds') ? (old('approveds')[$key]['role_id'] == $role->id ? 'selected' : '') : ($role->id == 1 ? 'selected' : '') }}>{{ $role->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="p-0"><select name="approveds[{{ $key }}][pole_id]"
                                                    id="poles_{{ $key }}" class="form-select"
                                                    data-live-search="true">
                                                    <option value="">Selecione o polo</option>
                                                    @foreach ($poles as $pole)
                                                        <option value="{{ $pole->id }}"
                                                        {{ old('approveds') ? (old('approveds')[$key]['pole_id'] == $pole->id ? 'selected' : '') : ($pole->id == 1 ? 'selected' : '') }}>{{ $pole->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <br />
                        <button type="submit" class="btn btn-primary">Importar</button>
                        <button type="button" class="btn btn-secondary" onclick="history.back()">< Voltar</button>
                    </form>
                </div>
            </div>
            <br />
            <script>
            </script>
        </main>
    </section>
@endsection
