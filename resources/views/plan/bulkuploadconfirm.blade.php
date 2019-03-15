@extends('Layouts.appmain')
<div class="container">
  <form method="post" action="/plan/confirmBulkDataImport">
    <input type="submit" class="btn btn-primary" value="Confirm Data Import">
    @csrf
    <table class="table table-striped table-sm">
      <thead>
        <tr>
          <th scope="col">Date</th>
          <th scope="col">Data Value</th>
          <th scope="col">Existing Data</th>
          <th scope="col">Use Data</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($unconfirmedData as $pd)
          <tr>
            <th scope="row">
              @if ($pd['existingData'])
              <input type="hidden" name="planIds[]" value="{{ $pd['existingData']->id }}">
              @else
              <input type="hidden" name="planIds[]" value="">
              @endif
              <input type="text" readonly="readonly" name="simpleDates[]" value="{{ $pd['importedData']->simple_date }}">
            </th>
            <td><input type="text" name="data[]" value="{{ $pd['importedData']->data }}"></td>
            <td>{{ $pd['existingData']->data }}</td>
            <td>
              @if ($pd['existingData'])
              <input type="checkbox" checked="checked" name="checkToUse[{{ $pd['existingData']->id }}]">
              @else
              <input type="checkbox" checked="checked" name="checkToUse[]">
              @endif
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </form>
</div>
