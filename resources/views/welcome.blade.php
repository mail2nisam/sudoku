@extends('app-layout')
@section('content')

    <input id="matrix-id" type="hidden" value="{{$matrix_id}}">
    <table id="sudoku">
        <tbody>
        @foreach($cells as $rowNumber=>$column)
            <tr>
                @foreach($column as $key=>$cell)
                    <td><input class="input-number {{($cell>0)?"initial-value":""}}" name="input_{{$rowNumber.$key}}" type="text" data-row="{{$rowNumber}}" data-column="{{$key}}" value="{{($cell>0)?$cell:""}}"/></td>
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="alert invisible validation-alert" role="alert">
        Sudoku is Valid
    </div>
@endsection