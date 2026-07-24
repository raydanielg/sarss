@extends('layouts.dashboard')
@section('title', 'Candidates - ' . config('app.name'))
@section('page_title', 'Candidates')

@section('content')
<div class="mb-6 flex items-center justify-between flex-wrap gap-3">
    <p class="text-sm text-gray-500">Manage examination candidates.</p>
    <div class="flex gap-2">
        <button onclick="document.getElementById('modal-import').classList.toggle('hidden')" class="px-4 py-2 border border-gray-200 text-gray-600 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-colors inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
            Import CSV
        </button>
        <button onclick="document.getElementById('modal-create').classList.toggle('hidden')" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg transition-colors inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Candidate
        </button>
    </div>
</div>

<form method="GET" class="mb-4 flex gap-2 flex-wrap">
    <select name="examination_id" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-400 outline-none">
        <option value="">All Examinations</option>
        @foreach($examinations as $exam)<option value="{{ $exam->id }}" @selected(request('examination_id') == $exam->id)>{{ $exam->name }}</option>@endforeach
    </select>
    <select name="school_id" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-400 outline-none">
        <option value="">All Schools</option>
        @foreach($schools as $school)<option value="{{ $school->id }}" @selected(request('school_id') == $school->id)>{{ $school->name }}</option>@endforeach
    </select>
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or number..." class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-400 outline-none flex-1 min-w-[200px]">
    <button type="submit" class="px-4 py-2 bg-gray-50 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-100">Filter</button>
</form>

<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead><tr class="text-left text-xs text-gray-500 bg-gray-50/50 border-b">
            <th class="px-5 py-3 font-medium">Candidate No.</th>
            <th class="px-5 py-3 font-medium">Name</th>
            <th class="px-5 py-3 font-medium">Gender</th>
            <th class="px-5 py-3 font-medium">School</th>
            <th class="px-5 py-3 font-medium">Class</th>
            <th class="px-5 py-3 font-medium">Examination</th>
            <th class="px-5 py-3 font-medium text-right">Actions</th>
        </tr></thead>
        <tbody>
            @forelse($candidates as $candidate)
            <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors">
                <td class="px-5 py-3 font-mono text-xs text-gray-700">{{ $candidate->candidate_number }}</td>
                <td class="px-5 py-3 font-semibold text-gray-900">{{ $candidate->name }}</td>
                <td class="px-5 py-3 text-gray-600">{{ $candidate->gender ? ucfirst($candidate->gender) : '—' }}</td>
                <td class="px-5 py-3 text-gray-600">{{ $candidate->school->name ?? '—' }}</td>
                <td class="px-5 py-3 text-gray-600">{{ $candidate->class->name ?? '—' }}</td>
                <td class="px-5 py-3 text-gray-600 text-xs">{{ $candidate->examination->name ?? '—' }}</td>
                <td class="px-5 py-3 text-right">
                    <button onclick="editCandidate({{ $candidate->id }}, '{{ $candidate->candidate_number }}', '{{ $candidate->name }}', '{{ $candidate->gender }}', {{ $candidate->school_id }}, {{ $candidate->district_id }}, {{ $candidate->class_id }}, {{ $candidate->stream_id ?? 'null' }}, {{ $candidate->examination_id }})" class="text-emerald-600 hover:text-emerald-700 text-xs font-medium">Edit</button>
                    <form action="{{ route('candidates.destroy', $candidate) }}" method="POST" class="inline" onsubmit="return confirm('Delete this candidate?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-600 text-xs font-medium ml-3">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-5 py-8 text-center text-gray-400">No candidates found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $candidates->links() }}

{{-- Import Modal --}}
<div id="modal-import" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-2">Import Candidates (CSV)</h3>
        <p class="text-xs text-gray-400 mb-4">CSV format: candidate_number, name, gender(M/F), school_code, district_code, class_code</p>
        <form action="{{ route('candidates.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">Examination</label>
                    <select name="examination_id" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-emerald-400 outline-none">
                        <option value="">Select...</option>
                        @foreach($examinations as $exam)<option value="{{ $exam->id }}">{{ $exam->name }}</option>@endforeach
                    </select>
                </div>
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">CSV File</label><input type="file" name="file" accept=".csv,.txt,.xlsx,.xls" required class="w-full text-sm"></div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="document.getElementById('modal-import').classList.toggle('hidden')" class="flex-1 px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50">Cancel</button>
                <button type="submit" class="flex-1 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-semibold">Import</button>
            </div>
        </form>
    </div>
</div>

{{-- Create/Edit Modal --}}
<div id="modal-create" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Add Candidate</h3>
        <form id="form-candidate" method="POST" action="{{ route('candidates.store') }}">
            @csrf
            <div class="grid grid-cols-2 gap-3">
                <input type="hidden" name="id" id="c-id">
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">Examination</label><select name="examination_id" id="c-examination" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm outline-none">@foreach($examinations as $exam)<option value="{{ $exam->id }}">{{ $exam->name }}</option>@endforeach</select></div>
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">Candidate Number</label><input type="text" name="candidate_number" id="c-number" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm outline-none" placeholder="S0101"></div>
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">Name</label><input type="text" name="name" id="c-name" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm outline-none" placeholder="Joseph"></div>
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">Gender</label><select name="gender" id="c-gender" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm outline-none"><option value="">—</option><option value="male">Male</option><option value="female">Female</option></select></div>
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">School</label><select name="school_id" id="c-school" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm outline-none">@foreach($schools as $school)<option value="{{ $school->id }}">{{ $school->name }}</option>@endforeach</select></div>
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">District</label><select name="district_id" id="c-district" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm outline-none">@foreach(\App\Models\District::orderBy('name')->get() as $district)<option value="{{ $district->id }}">{{ $district->name }}</option>@endforeach</select></div>
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">Class</label><select name="class_id" id="c-class" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm outline-none">@foreach(\App\Models\Classes::orderBy('level')->get() as $class)<option value="{{ $class->id }}">{{ $class->name }}</option>@endforeach</select></div>
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">Stream</label><select name="stream_id" id="c-stream" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm outline-none"><option value="">—</option>@foreach(\App\Models\Stream::orderBy('name')->get() as $stream)<option value="{{ $stream->id }}">{{ $stream->name }}</option>@endforeach</select></div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="document.getElementById('modal-create').classList.toggle('hidden')" class="flex-1 px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50">Cancel</button>
                <button type="submit" id="c-submit" class="flex-1 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-semibold">Create</button>
            </div>
        </form>
    </div>
</div>

<script>
function editCandidate(id, number, name, gender, schoolId, districtId, classId, streamId, examId) {
    document.getElementById('form-candidate').action = '{{ route("candidates.update", "__ID__") }}'.replace('__ID__', id);
    var form = document.getElementById('form-candidate');
    if (!form.querySelector('input[name="_method"]')) {
        var method = document.createElement('input'); method.type = 'hidden'; method.name = '_method'; method.value = 'PUT'; form.appendChild(method);
    }
    document.getElementById('c-examination').value = examId;
    document.getElementById('c-number').value = number;
    document.getElementById('c-name').value = name;
    document.getElementById('c-gender').value = gender;
    document.getElementById('c-school').value = schoolId;
    document.getElementById('c-district').value = districtId;
    document.getElementById('c-class').value = classId;
    if (streamId) document.getElementById('c-stream').value = streamId;
    document.getElementById('c-submit').textContent = 'Update';
    document.getElementById('modal-create').classList.toggle('hidden');
}
</script>
@endsection
