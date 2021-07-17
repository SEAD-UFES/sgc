@extends('layouts.basic')

@section('title', 'Revisão de Importação')

@section('content')
    <script>
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
                //alert("check_" + keyCount);
                var box = document.getElementById("check_" + i);
                box.checked = !box.checked;
            }
        }
    </script>
    <section>
        <strong>Revisão de Importação</strong>
    </section>
    <section id="pageContent">
        <main role="main" style="overflow-x:auto;">
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p style="color: green; font-weight: bold">{{ $message }}</p>
                </div><br />
            @endif
            <table style="border:none;">
                <thead>
                    <td colspan="3" style="width:280px; text-align:left;color:red;border:none;">
                        &nbsp;&dArr; Selecionar/Desselecionar todos</td>
                    <td colspan="3" style="width:280px; text-align:right;color:red;border:none;">
                        Atribuir em lote &rArr;&nbsp;</td>
                    <td style="overflow:hidden; white-space:nowrap;"><select name="courses_mass" id="courses_mass"
                            style="width:142px;"
                            onchange="selectRotateAssign('courses', {{ count($approveds) }}, document.getElementById('courses_mass').value)">
                            <option value="">Selecione o curso</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}">
                                    {{ $course->name }}</option>
                            @endforeach
                        </select></td>
                    <td style="overflow:hidden; white-space:nowrap;"><select name="roles_mass" id="roles_mass"
                            style="width:143px;"
                            onchange="selectRotateAssign('roles', {{ count($approveds) }}, document.getElementById('roles_mass').value)">
                            <option value="">Selecione a atribuição</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}
                                </option>
                            @endforeach
                        </select></td>
                    <td style="overflow:hidden; white-space:nowrap;"><select name="poles_mass" id="poles_mass"
                            style="width:143px;"
                            onchange="selectRotateAssign('poles', {{ count($approveds) }}, document.getElementById('poles_mass').value)">
                            <option value="">Selecione o polo</option>
                            @foreach ($poles as $pole)
                                <option value="{{ $pole->id }}">
                                    {{ $pole->name }}</option>
                            @endforeach
                        </select></td>
                </thead>
            </table>
            <br />
            <form action={{ route('approveds.massstore') }} method="POST">
                @csrf
                <input type="hidden" name="approvedsCount" value="{{ count($approveds) }}">
                <table style="border: none">
                    <thead>
                        <th style="width:16px;"><span onclick="toggleCheck({{ count($approveds) }})"
                                style="cursor: pointer;color:green;">&check;</span></th>
                        <th style="width:115px;">Nome</th>
                        <th style="width:115px">E-mail</th>
                        <th style="width:35px">DDD</th>
                        <th style="width:91px">Telefone</th>
                        <th style="width:101px">Celular</th>
                        <th style="width:68px">Edital</th>
                        <th>Curso</th>
                        <th>Atribuição</th>
                        <th>Polo</th>
                    </thead>
                    <tbody>
                        @foreach ($approveds as $key => $approved)
                            <tr>
                                <td style="overflow:hidden; white-space:nowrap; padding: 0px; border: none;"
                                    style="width:16px;padding: 0px"><input type="checkbox" name="check_{{ $key }}"
                                        id="check_{{ $key }}" style="margin-left: 3px;" checked="1" /></td>
                                <td style="overflow:hidden; white-space:nowrap; padding: 0px; border: none;"
                                    title="{{ $approved->name }}"><input type="text" name="name_{{ $key }}"
                                        value="{{ $approved->name }}" style="width:111px;" /></td>
                                <td style="overflow:hidden; white-space:nowrap; padding: 0px; border: none;"
                                    title="{{ $approved->email }}"><input type="text" name="email_{{ $key }}"
                                        value="{{ $approved->email }}" style="width:111px;" /></td>
                                <td style="overflow:hidden; white-space:nowrap; padding: 0px; border: none;"
                                    title="{{ $approved->area_code }}"><input type="text"
                                        name="area_{{ $key }}" value="{{ $approved->area_code }}"
                                        style="width:31px;" /></td>
                                <td style="overflow:hidden; white-space:nowrap; padding: 0px; border: none;"
                                    title="{{ $approved->phone }}"><input type="text" name="phone_{{ $key }}"
                                        value="{{ $approved->phone }}" style="width:87px;" /></td>
                                <td style="overflow:hidden; white-space:nowrap; padding: 0px; border: none;"
                                    title="{{ $approved->mobile }}"><input type="text" name="mobile_{{ $key }}"
                                        value="{{ $approved->mobile }}" style="width:97px;" /></td>
                                <td style="overflow:hidden; white-space:nowrap; padding: 0px; border: none;"
                                    title="{{ $approved->announcement }}"><input type="text"
                                        name="announcement_{{ $key }}" value="{{ $approved->announcement }}"
                                        style="width:64px;" /></td>
                                <td style="overflow:hidden; white-space:nowrap; padding: 0px; border: none;"><select
                                        name="courses_{{ $key }}" id="courses_{{ $key }}"
                                        style="width:147px;">
                                        <option value="">Selecione o curso</option>
                                        @foreach ($courses as $course)
                                            <option value="{{ $course->id }}">
                                                {{ $course->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td style="overflow:hidden; white-space:nowrap; padding: 0px; border: none;"><select
                                        name="roles_{{ $key }}" id="roles_{{ $key }}"
                                        style="width:146px;">
                                        <option value="">Selecione a atribuição</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td style="overflow:hidden; white-space:nowrap; padding: 0px; border: none;"><select
                                        name="poles_{{ $key }}" id="poles_{{ $key }}"
                                        style="width:146px;">
                                        <option value="">Selecione o polo</option>
                                        @foreach ($poles as $pole)
                                            <option value="{{ $pole->id }}" {{ $pole->id == 1 ? 'selected' : '' }}>
                                                {{ $pole->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <br /><br />
                <button type="submit">Importar</button> <button type="button" onclick="history.back()">Cancelar</button>
            </form>
            <br /><br />
        </main>
    </section>
@endsection
