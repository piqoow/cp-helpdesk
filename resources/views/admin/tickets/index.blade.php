@extends('layouts.halaman')
@section('content')
@can('ticket_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.tickets.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.ticket.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.ticket.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Ticket">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.ticket.fields.id') }}
                    </th>
                    <th>
                        {{ trans('Title') }}
                    </th>
                    <th>
                        {{ trans('Status') }}
                    </th>
                    <th>
                        {{ trans('Priority') }}
                    </th>
                    <th>
                        {{ trans('Crategory') }}
                    </th>
                    <th>
                        {{ trans('Created') }}
                    </th>
                    <th>
                        {{ trans('Updated') }}
                    </th>
                    <th>
                        {{ trans('Deadline') }}
                    </th>
                    <th>
                        {{ trans('Report Name') }}
                    </th>
                    <th>
                        {{ trans('Report Email') }}
                    </th>
                    <th>
                        {{ trans('Division') }}
                    </th>
                    <th>
                        &nbsp;
                    </th>
                </tr>
            </thead>
        </table>


    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {
let filters = `
<form class="form-inline" id="filtersForm">
  <div class="form-group mx-sm-3 mb-2">
    <select class="form-control" name="status">
      <option value="">All Statuses</option>
      @foreach($statuses as $status)
        <option value="{{ $status->id }}"{{ request('status') == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="form-group mx-sm-3 mb-2">
    <select class="form-control" name="priority">
      <option value="">All Priority</option>
      @foreach($priorities as $priority)
        <option value="{{ $priority->id }}"{{ request('priority') == $priority->id ? 'selected' : '' }}>{{ $priority->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="form-group mx-sm-3 mb-2">
    <select class="form-control" name="category">
      <option value="">All Categories</option>
      @foreach($categories as $category)
        <option value="{{ $category->id }}"{{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
      @endforeach
    </select>
  </div>
</form>`;
$('.card-body').on('change', 'select', function() {
  $('#filtersForm').submit();
})
  let dtButtons = []
@can('ticket_delete')
  let deleteButtonTrans= '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.tickets.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
          return entry.id
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan
  let searchParams = new URLSearchParams(window.location.search)
  let dtOverrideGlobals = {
    buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: {
      url: "{{ route('admin.tickets.index') }}",
      data: {
        'status'  : searchParams.get('status'),
        'priority': searchParams.get('priority'),
        'category': searchParams.get('category')
        // 'deadline': searchParams.get('deadline')
      }
    },
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{
    data: 'title',
    name: 'title', 
    render: function ( data, type, row) {
        return '<a href="'+row.view_link+'">'+data+' ('+row.comments_count+')</a>';
    }
},
{ 
  data: 'status_name', 
  name: 'status.name', 
  render: function ( data, type, row) {
      return '<span style="color:'+row.status_color+'">'+data+'</span>';
  }
},
{ 
  data: 'priority_name', 
  name: 'priority.name', 
  render: function ( data, type, row) {
      return '<span style="color:'+row.priority_color+'">'+data+'</span>';
  }
},
{ 
  data: 'category_name', 
  name: 'category.name', 
  render: function ( data, type, row) {
      return '<span style="color:'+row.category_color+'">'+data+'</span>';
  } 
},
{ 
  data: 'created_at',
  name: 'created_at', 
  render: function ( data, type, row) {
    const date = new Date (data);

    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');

    const formattedDate = `${year}-${month}-${day}`;

      return '<span style="color:'+row.created_at+'">'+formattedDate +'</span>';
  } 
},
{ 
  data: 'updated_at', 
  name: 'updated_at', 
  render: function ( data, type, row) {
      return '<span style="color:'+row.updated_at+'">'+data+'</span>';
  } 
},
{ 
  data: 'deadline', 
  name: 'deadline', 
  render: function ( data, type, row) {
      return '<span style="color:'+row.deadline+'">'+data+'</span>';
  } 
},


{ data: 'author_name', name: 'author_name' },
{ data: 'author_email', name: 'author_email' },
{ data: 'assigned_to_user_name', name: 'assigned_to_user.name' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  };    
$(".datatable-Ticket").one("preInit.dt", function () {
 $(".dataTables_filter").after(filters);
});
  $('.datatable-Ticket').DataTable(dtOverrideGlobals);
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
});

</script>
@endsection