@extends('layouts.basic')

@section('title', 'Fomento')

@section('content')
    <section>
        <strong>Fomento</strong>
    </section>
    <section id="pageContent">
        <main role="main">
            <nav>
                <h3 style="background-color: rosybrown;text-decoration:line-through">Bolsas</h3>
                <ul>
                    <li><a href="#">!Inserir bolsas do semestre!</a></li>
                    <li><a href="#">!Distribuir bolsas!</a></li>
                    <li><a href="#">!Remanejar bolsas!</a></li>
                </ul><br />
                @php
                    $course = App\Models\Course::find(session('sessionUser')->currentBond->course->id);
                    $employees = $course->employees()->get();
                @endphp
                <h3 style="background-color: rosybrown;text-decoration:line-through">Colaboradores do Curso [{{$course->name}}]</h3>
                <table>
                    <thead>
                        <th>Nome</th>
                        <th>Cidade</th>
                        <th>E-mail:</th>
                    </thead>
                    <tbody>
                        @foreach ($employees as $employee)
                            <tr>
                                <td>{{ $employee->name }}</td>
                                <td>{{ $employee->address_city }}</td>
                                <td>{{ $employee->email }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </nav>
            <br /><br />
        </main>
    </section>
@endsection
