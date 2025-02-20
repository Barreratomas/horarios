
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width" />

    <link rel="stylesheet" href="{{ asset('css/tabla.css') }}" />
    <link
        rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700&display=swap"
    />
    <link
        rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400&display=swap"
    />
    <link
        rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600;700&display=swap"
    />
</head>
<body class="bedelia-horario">
    @if (Session::get('userType') == 'bedelia' || Session::get('userType') == 'admin' && request()->route()->named('mostrarHorarioBedelia'))
        @foreach ($horariosAgrupados as $carrera => $horariosPorCarrera)
            @foreach ($horariosPorCarrera->groupBy('anio') as $anio => $divisionesPorAnio)
            <h3 class="mt-4" style="font-family: sans-serif; color:white;">Año: {{ $anio }}</h3>
                
                @foreach ($divisionesPorAnio->groupBy('division') as $division => $horariosPorDivision)
                    <h4 style="font-family: sans-serif; color:white;">Division: {{ $division }}</h4> 
                    <table class="planilla1">
                        <thead class="horarios">

                            @php
                                $inicio = [
                                    1 => '19:20',
                                    2 => '20:00',
                                    3 => '20:40',
                                    4 => '21:30',
                                    5 => '22:10',
                                    6 => '22:50'
                                ];

                                $fin = [
                                    1 => '20:00',
                                    2 => '20:40',
                                    3 => '21:20',
                                    4 => '22:10',
                                    5 => '22:50',
                                    6 => '23:30'
                                ];



                                $dias = [
                                    1 => 'lunes',
                                    2 => 'martes',
                                    3 => 'miercoles',
                                    4 => 'jueves',
                                    5 => 'viernes'
                                ];

                                $colores = [
                                    1 => 'rgba(250, 22, 22, 0.38)',
                                    2 => 'rgba(22, 72, 250, 0.28)',
                                    3 => 'rgba(54, 250, 22, 0.28)',
                                    4 => 'rgba(22, 250, 236, 0.28)',
                                    5 => 'rgba(246, 250, 22, 0.28)',
                                    6 => 'rgba(250, 22, 200, 0.28)',
                                    7 => 'rgba(122, 22, 250, 0.28)',
                                    8 => 'rgba(250, 131, 22, 0.28)'
                                ];
                            @endphp

                            <tr>
                                <th class="div">Días / Horarios</th>
                                @for($i = 1; $i < 7; $i++)
                                    <th class="p{{$i}}">{{$inicio[$i]}} - {{$fin[$i]}}</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($dias as  $dia)
                                <tr class="xd">
                                    <th class="dias" @if($dia == 'viernes') style="border-radius: 0 0 0 20px" @endif>{{$dia}}</th>
                                    
                                    @php
                                        $moduloAnterior=0
                                    @endphp
                                    @foreach ($horariosPorDivision as $horario)
                                        @if($horario->dia == $dia)
                                            @foreach ($inicio as $modulo =>$hora)
                                                @if( $modulo >= $horario->modulo_inicio && $modulo < $horario->modulo_fin )
                                                    @php
                                                    $moduloAnterior++;
                                                    @endphp                                            
                                                    <td class="thhh" style="background-color: {{$colores[rand(1, 8)]}}">
                                                        <div class="elementos">{{$horario->disponibilidad->docenteMateria->materia->nombre}}</div>
                                                        <div class="elementos" id="docente">{{$horario->disponibilidad->docenteMateria->docente->nombre}} {{$horario->disponibilidad->docenteMateria->docente->apellido}}</div>
                                                        <div class="elementos" id="aula">{{$horario->disponibilidad->docenteMateria->aula->nombre}}</div>
                                                        <div class="elementos">{{$horario->carrera->nombre}}</div>

                                                    </td>

                                                @elseif($modulo>=$horario->modulo_fin)
                                                        
                                                    @continue

                                                @elseif($moduloAnterior+$modulo<$horario->modulo_inicio)
                                                        
                                                            
                                                    <td class="thhh" style="background-color: {{$colores[rand(1, 8)]}}"></td>

                                                @endif
                                            @endforeach


                                        @endif
                                    @endforeach
                                </tr>
                            @endforeach

                        </tbody>
                    </table>

                @endforeach
            @endforeach
        @endforeach
    </tbody>
</table>
@endforeach
@endif

@if (Session::get('userType') == 'estudiante' || Session::get('userType') == 'admin' && request()->route()->named('mostrarHorario'))
<table border="1">
    <thead>
        <tr>
            <th>Día</th>
            <th>Modulo Inicio</th>
            <th>Modulo Fin</th>
            <th>V/P</th>
            <th>Aula</th>
            <th>Materia</th>
            <th>Docente</th>
            <th>asdas</th>



        </tr>
    </thead>
    <tbody>
        @php

        $horasPermitidas = [
            1 => '19:20',
            2 => '20:00',
            3 => '20:40',
            4 => '21:20',
            5 => '21:30',
            6 => '22:10',
            7 => '22:50',
        ];
        @endphp
        {{-- verifico existen horarios --}}
        @foreach ($horarios ?? [] as $horario)
            <tr>
                {{-- si no existen los registros muestro na --}}
                <td>{{ $horario->dia ? $horario->dia : 'N/A' }}</td>
                <td>{{ isset($horario->modulo_inicio) && isset($horasPermitidas[$horario->modulo_inicio]) ? $horasPermitidas[$horario->modulo_inicio] : 'N/A' }}</td>
                <td>{{ isset($horario->modulo_fin) && isset($horasPermitidas[$horario->modulo_fin]) ? $horasPermitidas[$horario->modulo_fin] : 'N/A' }}</td>
                <td>{{ $horario->v_p == 'p' ? 'Presencial' : 'Virtual' }}</td>
                <td>{{ $horario->aula ? $horario->aula : 'N/A' }}</td>
                <td>{{  $horario->materia? $horario->materia  : 'N/A' }}</td>
                <td>{{  $horario->disponibilidad->docenteMateria->docente->nombre ? $horario->disponibilidad->docenteMateria->docente->nombre   : 'N/A' }} {{  $horario->disponibilidad->docenteMateria->docente->apellido ? $horario->disponibilidad->docenteMateria->docente->apellido : 'N/A'}} </td>


            </tr>
        @endforeach
    </tbody>
</table>
@endif

@if (Session::get('userType') == 'docente' || Session::get('userType') == 'admin' && request()->route()->named('mostrarHorarioDocente'))
@foreach ($horariosAgrupados as $anio => $divisionesPorAnio)
<h2>Año: {{ $anio }}</h2>
<table border="1">
    <thead>
        <tr>
            <th>Día</th>
            <th>Modulo Inicio</th>
            <th>Modulo Fin</th>
            <th>V/P</th>
            <th>Aula</th>
            <th>Materia</th>
            <th>comision</th>
        </tr>
    </thead>
    <tbody>
        @php

        $horasPermitidas = [
            1 => '19:20',
            2 => '20:00',
            3 => '20:40',
            4 => '21:20',
            5 => '21:30',
            6 => '22:10',
            7 => '22:50',
        ];
        @endphp
        @foreach ($divisionesPorAnio as $division => $horariosPorDivision)
            <tr>
                <td colspan="3"><strong>División:</strong> {{ $division }}</td>
            </tr>
            @foreach ($horariosPorDivision as $horario)
                <tr>
                     {{-- si no existen los registros muestro na --}}
                <td>{{ $horario->dia ? $horario->dia : 'N/A' }}</td>
                <td>{{ isset($horario->modulo_inicio) && isset($horasPermitidas[$horario->modulo_inicio]) ? $horasPermitidas[$horario->modulo_inicio] : 'N/A' }}</td>
                <td>{{ isset($horario->modulo_fin) && isset($horasPermitidas[$horario->modulo_fin]) ? $horasPermitidas[$horario->modulo_fin] : 'N/A' }}</td>
                <td>{{ $horario->v_p == 'p' ? 'Presencial' : 'Virtual' }}</td>
                <td>{{ $horario->aula ? $horario->aula : 'N/A' }}</td>
                <td>{{  $horario->materia? $horario->materia  : 'N/A' }}</td>
                <td>{{  $horario->anio ? $horario->anio  : 'N/A' }}°{{  $horario->division ? $horario->division  : 'N/A'}} </td>


                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
@endforeach
@endif
