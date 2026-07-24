<?php

namespace App\Http\Controllers;

use App\Models\Stream;
use App\Traits\Auditable;
use Illuminate\Http\Request;

class StreamController extends Controller
{
    use Auditable;

    public function index()
    {
        $streams = Stream::orderBy('name')->get();
        return view('setup.streams.index', compact('streams'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:streams,code',
        ]);
        $stream = Stream::create($data);
        $this->logAction('create', 'Streams', "Created stream {$stream->name}");
        return redirect()->route('streams.index')->with('status', 'Stream created successfully.');
    }

    public function update(Request $request, Stream $stream)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:streams,code,' . $stream->id,
        ]);
        $old = $stream->toArray();
        $stream->update($data);
        $this->logAction('update', 'Streams', "Updated stream {$stream->name}", $old, $stream->toArray());
        return redirect()->route('streams.index')->with('status', 'Stream updated successfully.');
    }

    public function destroy(Stream $stream)
    {
        $stream->delete();
        $this->logAction('delete', 'Streams', "Deleted stream {$stream->name}");
        return redirect()->route('streams.index')->with('status', 'Stream deleted.');
    }
}
