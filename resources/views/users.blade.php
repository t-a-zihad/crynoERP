@extends('parts.main')

@section('main-section')

    <div class="page-header">
		<div class="row">
				<div class="col-md-6 col-sm-12">
					<div class="title">
						<h4>Users</h4>
					</div>
				</div>

			</div>
	</div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover  stripe">
            <thead>
                <tr>
                    <th class="table-plus datatable-nosort">Name</th>
                    <th>Employee ID</th>
                    <th>Email</th>
                    <th>Contact No</th>
                    <th>Role</th>
                    <th class="datatable-nosort">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($employees as $employee)
                <tr>
                    <td class="table-plus">{{ $employee->name }}</td>
                    <td>{{ $employee->employee_id }}</td>
                    <td>{{ $employee->email }}</td>
                    <td>{{ $employee->contact_no }}</td>
                    <td>{{ $employee->role }}</td>
                    <td>
                        <div class="dropdown">
                            <a
                                class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                href="#"
                                role="button"
                                data-toggle="dropdown"
                            >
                                <i class="dw dw-more"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                <a class="dropdown-item" href="{{ route('employee.view', $employee->id) }}">
                                    <i class="dw dw-eye"></i> View
                                </a>
                                <a class="dropdown-item" href="{{ route('employee.edit', $employee->id) }}">
                                    <i class="dw dw-edit2"></i> Edit
                                </a>
                                <form method="POST" action="{{ route('employee.delete', $employee->id) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="dropdown-item" type="submit" onclick="return confirm('Are you sure to delete?')">
                                        <i class="dw dw-delete-3"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>

@endsection
