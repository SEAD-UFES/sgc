@extends('layouts.basic')

@section('title', 'System Info')

@section('content')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb border-top border-bottom bg-light">
            <li class="breadcrumb-item">Sistema</li>
            <li class="breadcrumb-item active" aria-current="page">System Info</li>
        </ol>
    </nav>
    <section id="pageContent">
        <main role="main">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-xxl-8">
                    <div class="center">
                        <table>
                            <tbody>
                                <tr style="background-color: #ff7a7a; font-weight: bold;">
                                    <td>
                                        <a href="http://https://laravel.com//"><img border="0"
                                                src="https://laravel.com/img/logomark.min.svg" alt="Laravel logo"></a>
                                        <h1 class="p">Laravel Version {{ app()->version() }}</h1>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table>
                            <tbody>
                                <tr>
                                    <td style="background-color: #ffc8c8; width: 300px; font-weight: bold;">SGC Version</td>
                                    <td class="v">{{ config('app.version') }}</td>
                                </tr>
                                <tr>
                                    <td style="background-color: #ffc8c8; width: 300px; font-weight: bold;">App Base Path
                                    </td>
                                    <td class="v">{{ app()->basePath() }}</td>
                                </tr>
                                {{-- <tr>
                                    <td style="background-color: #ffc8c8; width: 300px; font-weight: bold;">App Enviroment Path</td>
                                    <td class="v">{{ app()->environmentPath() }}</td>
                                </tr> --}}
                                <tr>
                                    <td style="background-color: #ffc8c8; width: 300px; font-weight: bold;">App Enviroment
                                        File</td>
                                    <td class="v">{{ app()->environmentFile() }}</td>
                                </tr>
                                <tr>
                                    <td style="background-color: #ffc8c8; width: 300px; font-weight: bold;">App Current
                                        Locale</td>
                                    <td class="v">{{ app()->currentLocale() }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    @php
                        phpinfo();
                    @endphp
                </div>
            </div>
        </main>
    </section>
@endsection
