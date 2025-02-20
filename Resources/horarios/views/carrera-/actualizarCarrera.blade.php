@extends('layouts.base')

@section('title','actualizar carrera')

@section('content')
<div class="container py-3">
    <div class="row align-items-center justify-content-center">
        <div class="col-6 text-center"> 
            <form action="{{ route('actualizarCarrera',$carrera->id_carrera) }}" method="post">
                @method('put')
                @csrf
                

                <label for="nombre">Ingrese el nombre</label><br>
                <input type="text" name="nombre"><br><br>
                @error('nombre')
                <div class="text-danger">{{ $message }}</div>
                @enderror
                <br>

                
                <button type="submit" class="btn btn-primary me-2">Actualizar</button> <!-- Agregada clase me-2 para espacio entre botones -->
            </form>

            
        </div>
    </div>
</div>

<div class="container" style="width: 500px;"> 
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
       
</div>

@endsection
